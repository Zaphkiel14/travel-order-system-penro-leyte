<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TravelOrderModel;
use App\Models\SelectModel;
use App\Services\GoogleDriveService;
use App\Services\PrintService;

class TravelOrderController extends BaseController
{

    private function getUserScope(): array
    {
        $userId = session()->get('user_id');
        $role   = session()->get('role');

        $model = new SelectModel();
        $data  = $model->getManagedByRole($role, $userId);

        if (!$data) {
            throw new \RuntimeException('User is not assigned to any ' . $role);
        }

        return [
            'level' => $role,
            'name' => $data->name,
            'id'    => $data->id
        ];
    }

    public function incomingTravelOrders()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->renderError(
                $this->errorHandler->unauthorized()
            );
        }
        $data = [
            'title' => 'Travel Order | Incoming Travel Orders',
            'page' => 'Incoming Travel Orders',
        ];
        $role = session()->get('role');
        if ($role === 'penro') {
            return view('organization/incoming-travel-orders', $data);
        } else if ($role === 'division') {
            return view('division/incoming-travel-orders', $data);
        } else if ($role === 'unit') {
            return view('unit/incoming-travel-orders', $data);
        } else {
            return $this->renderError(
                $this->errorHandler->forbidden('Unauthorized Access')
            );
        }
    }

    public function incomingTravelOrderData()
    {
        try {
            $scope = $this->getUserScope();
        } catch (\RuntimeException $e) {
            return $this->response->setJSON([
                'draw' => 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }

        $draw     = (int) ($this->request->getPost('draw') ?? 1);
        $start    = (int) ($this->request->getPost('start') ?? 0);
        $length   = (int) ($this->request->getPost('length') ?? 10);
        $search   = $this->request->getPost('search')['value'] ?? '';
        $orderCol = (int) ($this->request->getPost('order')[0]['column'] ?? 0);
        $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'desc';

        $columns = [
            0 => 'created_at',
            1 => 'travel_order_number',
            2 => 'destination',
            3 => 'travel_dates',
            4 => 'status',
        ];
        $orderBy = $columns[$orderCol] ?? 'created_at';

        $model = new TravelOrderModel();



        // Total
        $total = $model->getIncomingByScopeQuery($scope['level'], $scope['id'])
            ->countAllResults(false);


        $query = $model->getIncomingByScopeQuery($scope['level'], $scope['id']);
        if ($search !== '') {
            $query->groupStart()
                ->like('travel_order_number', $search)
                ->orLike('destination', $search)
                ->groupEnd();
        }

        // Filtered
        $filtered = $query->countAllResults(false);

        $rows = $query->orderBy($orderBy, $orderDir)
            ->findAll($length, $start);

        $data = array_map(function ($row) {
            return [
                'created_at'          => date('M j, Y', strtotime($row['created_at'])),
                'travel_order_number' => esc($row['travel_order_number']),
                'destination'         => esc($row['destination']),
                'travel_dates'        => date('M j, Y', strtotime($row['departure_date']))
                    . ' – '
                    . date('M j, Y', strtotime($row['arrival_date'])),
                'status'              => '<span class="badge bg-warning">' . esc($row['current_status']) . '</span>',
                'actions' => '<button type="button"
                                class="btn btn-sm btn-primary btn-view-travel-order"
                                data-id="' . $row['travel_order_id'] . '">
                                <i class="bi bi-eye"></i> View
                                </button>',
            ];
        }, $rows);
        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }

    public function index()
    {
        //
    }

    public function createTravelOrder()
    {
        $folderId = getenv('drive.folderId');
        $userId   = session()->get('user_id');

        if (!$userId) {
            return redirect()->back()->with('toast', [
                'type'    => 'warning',
                'message' => 'User not logged in.',
            ]);
        }
        $selectModel = new SelectModel();
        $newTravelOrderNumber = $selectModel->generateNextTravelOrderID();

        $drive = new GoogleDriveService();
        try {
            $drive->setUser($userId);
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', [
                'type'    => 'warning',
                'message' => 'Google Drive not connected. Please re-link your Google account.',
            ]);
        }

        // ── File field config ──────────────────────────────────────────────
        // field name => filename suffix used for both the Drive filename and DB record
        $fileFields = [
            'request_memo'          => 'REQUEST_MEMO',
            'special_order'         => 'SPECIAL_ORDER',
            'request_letter'        => 'REQUEST_LETTER',
            'invitation_letter'     => 'INVITATION_LETTER',
            'training_notification' => 'TRAINING_NOTIFICATION',
            'meeting_notice'        => 'MEETING_NOTICE',
            'conference_program'    => 'CONFERENCE_PROGRAM',
            'other_document'        => 'OTHER_DOCUMENT',
        ];

        // ── Upload loop — builds ['field_name' => ['file_id' => ..., 'file_name' => ...]] ──
        $attachments = [];

        foreach ($fileFields as $fieldName => $suffix) {
            $file = $this->request->getFile($fieldName);
            $attachments[$fieldName] = null;

            if (!$file || !$file->isValid()) {
                continue;
            }

            try {
                $fileName = $newTravelOrderNumber . '_' . $suffix . '.' . $file->getExtension();

                $fileId = $drive->uploadFileFromContent(
                    file_get_contents($file->getTempName()),
                    $fileName,
                    $file->getClientMimeType(),
                    $folderId
                );

                $attachments[$fieldName] = [
                    'file_id'   => $fileId,
                    'file_name' => $fileName,
                ];

                log_message('debug', "[TO Upload] {$fieldName} → file_id: {$fileId}, name: {$fileName}");
            } catch (\Exception $e) {
                log_message('error', "[TO Upload] Failed uploading {$fieldName}: " . $e->getMessage());
            }
        }



        $selection = $this->request->getPost('unit_division_organization');
        [$type,, $id] = explode('-', $selection);

        $hierarchy = $selectModel->resolveHierarchy($type, (int)$id);
        // ── Insert ─────────────────────────────────────────────────────────
        $model  = new TravelOrderModel();
        $result = $model->insertTravelOrder(
            $newTravelOrderNumber,
            $this->request->getPost('persons'),
            $this->request->getPost('departure_date'),
            $this->request->getPost('arrival_date'),
            $this->request->getPost('destination'),
            $this->request->getPost('travel_purpose'),
            $attachments,
            $hierarchy['current_status'],
            $hierarchy['current_level'],
            $hierarchy['unit_id'],
            $hierarchy['division_id'],
            $hierarchy['organization_id']
        );

        return redirect()->back()->with('toast', [
            'type'    => $result ? 'success' : 'danger',
            'message' => $result
                ? "'{$newTravelOrderNumber}' created successfully."
                : "Failed to create '{$newTravelOrderNumber}'.",
        ]);
    }

    public function travelOrdersData()
    {
        $draw     = (int) ($this->request->getPost('draw') ?? 1);
        $start    = (int) ($this->request->getPost('start') ?? 0);
        $length   = (int) ($this->request->getPost('length') ?? 10);
        $search   = $this->request->getPost('search')['value'] ?? '';
        $orderCol = (int) ($this->request->getPost('order')[0]['column'] ?? 0);
        $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'desc';

        // Map DataTable column index → actual DB column
        $columns = [
            0 => 'created_at',
            1 => 'travel_order_number',
            2 => 'destination',
            3 => 'travel_dates',
            4 => 'status',
        ];
        $orderBy = $columns[$orderCol] ?? 'created_at';

        $userId = session()->get('user_id');
        $model  = new TravelOrderModel();

        // Total records for this user (no filters)
        $total = $model->getMyTravelOrdersQuery($userId)
            ->countAllResults(false);

        // Apply search filter on top of the base query
        $query = $model->getMyTravelOrdersQuery($userId);
        if ($search !== '') {
            $query->groupStart()
                ->like('travel_order_number', $search)
                ->orLike('destination', $search)
                ->orLike('status', $search)
                ->groupEnd();
        }

        // Filtered count (before pagination)
        $filtered = $query->countAllResults(false);

        // Paginated results
        $rows = $query->orderBy($orderBy, $orderDir)
            ->findAll($length, $start);

        $data = array_map(function ($row) {
            return [
                'created_at'          => date('M j, Y', strtotime($row['created_at'])),
                'travel_order_number' => esc($row['travel_order_number']),
                'destination'         => esc($row['destination']),
                'travel_dates'        => date('M j, Y', strtotime($row['departure_date']))
                    . ' – '
                    . date('M j, Y', strtotime($row['arrival_date'])),
                'status' => $this->getStatusBadge($row['current_status']),
                'actions' => '<button type="button"
                                class="btn btn-primary btn-view-travel-order"
                                data-id="' . $row['travel_order_id'] . '">
                                <i class="bi bi-eye"></i>
                                </button>
                                <button type="button"
                                class="btn btn-success btn-edit-travel-order"
                                data-id="' . $row['travel_order_id'] . '">
                                <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                ',
            ];
        }, $rows);
        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }

    public function travelOrderDetails(int $travelOrderId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->renderError(
                $this->errorHandler->unauthorized()
            );
        }
        if (!$this->request->isAJAX()) {
            return $this->renderError(
                $this->errorHandler->forbidden('You are Accessing a Restricted Route.')
            );
        }

        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');
        $model  = new TravelOrderModel();
        $order  = $model->getTravelOrderDetails($travelOrderId);

        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Travel order not found.',
            ]);
        }

        $privilegedRoles = ['admin', 'penro', 'division_head', 'unit', 'records'];

        if (!in_array($role, $privilegedRoles) && (int) $order['user_id'] !== $userId) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Access denied.',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $order,
        ]);
    }

    public function downloadAttachment(string $fileId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        try {
            $drive = new GoogleDriveService();
            $drive->setUser($userId);
            $file = $drive->downloadFile($fileId);
        } catch (\Exception $e) {
            log_message('error', 'Attachment download failed: ' . $e->getMessage());
            return redirect()->back()->with('toast', [
                'type'    => 'danger',
                'message' => 'Could not download file: ' . $e->getMessage()
            ]);
        }

        return $this->response
            ->setHeader('Content-Type', $file['mimeType'])
            ->setHeader('Content-Disposition', 'attachment; filename="' . $file['name'] . '"')
            ->setHeader('Content-Length', (string) strlen($file['content']))
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->setBody($file['content']);
    }
    public function printTO($travelOrderId)
    {
        $printService = new PrintService();
        return $printService->previewPrintTO($travelOrderId);
    }
    private function getStatusBadge($status)
    {
        if (!$status) {
            return '<span class="badge bg-secondary text-white">—</span>';
        }

        $s = strtolower($status);

        if (str_starts_with($s, 'forwarded to')) {
            $class = 'bg-info-subtle text-info-emphasis';
        } elseif (str_starts_with($s, 'rejected by')) {
            $class = 'bg-danger-subtle text-danger-emphasis';
        } elseif (str_starts_with($s, 'approved by')) {
            $class = 'bg-success-subtle text-success-emphasis';
        } elseif ($s === 'pending') {
            $class = 'bg-warning-subtle text-warning-emphasis';
        } elseif ($s === 'approved') {
            $class = 'bg-success-subtle text-success-emphasis';
        } elseif ($s === 'rejected') {
            $class = 'bg-danger-subtle text-danger-emphasis';
        } else {
            $class = 'bg-secondary-subtle text-white-emphasis';
        }

        return '<span class="badge ' . $class . '">' . esc($status) . '</span>';
    }
}
