<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\TravelOrderModel;
use App\Models\SelectModel;
use App\Services\GoogleDriveService;

class TravelOrderController extends BaseController
{
    public function index()
    {
        //
    }


    
    public function createTravelOrder()
    {

        $folderId = getenv('drive.folderId');
        // Get user ID
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->back()->with('toast', [
                'type' => 'warning',
                'message' => 'User not logged in.'
            ]);
        }

        // Generate new travel order number
        $newTravelOrderNumber = (new SelectModel())->generateNextTravelOrderID();

        // Initialize GDriveService
        $drive = new GoogleDriveService();
        try {
            $drive->setUser($userId);
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', [
                'type' => 'warning',
                'message' => 'Google Drive not connected.'
            ]);
        }

        // TEMPORARY DEBUG - remove after fixing
        $files = $this->request->getFiles();
        log_message('debug', 'FILES RECEIVED: ' . print_r(array_map(function ($f) {
            if (is_array($f)) {
                return array_map(fn($ff) => [
                    'name'    => $ff->getClientName(),
                    'size'    => $ff->getSize(),
                    'valid'   => $ff->isValid(),
                    'error'   => $ff->getError(),
                ], $f);
            }
            return [
                'name'  => $f->getClientName(),
                'size'  => $f->getSize(),
                'valid' => $f->isValid(),
                'error' => $f->getError(),
            ];
        }, $files), true));

        $requestMemo = $this->request->getFile('request_memo');
        $requestMemoFileId = null;
        if ($requestMemo && $requestMemo->isValid()) {
            $content = file_get_contents($requestMemo->getTempName());
            $extension = $requestMemo->getExtension();
            $fileName = $newTravelOrderNumber . '_REQUEST_MEMO.' . $extension;
            $requestMemoFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $requestMemo->getMimeType(),
                '1EzPQVobKIZy7NvndgDzTObZ0Lj3UBuEP'
            );
        }
        log_message('debug', 'Request Memo File ID: ' . $requestMemoFileId);

        $specialOrder = $this->request->getFile('special_order');
        $specialOrderFileId = null;
        if ($specialOrder && $specialOrder->isValid()) {
            $content = file_get_contents($specialOrder->getTempName());
            $extension = $specialOrder->getExtension();
            $fileName = $newTravelOrderNumber . '_SPECIAL_ORDER.' . $extension;
            $specialOrderFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $specialOrder->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Special Order File ID: ' . $specialOrderFileId);

        $requestLetter = $this->request->getFile('request_letter');
        $requestLetterFileId = null;
        if ($requestLetter && $requestLetter->isValid()) {
            $content = file_get_contents($requestLetter->getTempName());
            $extension = $requestLetter->getExtension();
            $fileName = $newTravelOrderNumber . '_REQUEST_LETTER.' . $extension;
            $requestLetterFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $requestLetter->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Request Letter File ID: ' . $requestLetterFileId);

        $invitationLetter = $this->request->getFile('invitation_letter');
        $invitationLetterFileId = null;
        if ($invitationLetter && $invitationLetter->isValid()) {
            $content = file_get_contents($invitationLetter->getTempName());
            $extension = $invitationLetter->getExtension();
            $fileName = $newTravelOrderNumber . '_INVITATION_LETTER.' . $extension;
            $invitationLetterFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $invitationLetter->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Invitation Letter File ID: ' . $invitationLetterFileId);


        $trainingNotification = $this->request->getFile('training_notification');
        $trainingNotificationFileId = null;
        if ($trainingNotification && $trainingNotification->isValid()) {
            $content = file_get_contents($trainingNotification->getTempName());
            $extension = $trainingNotification->getExtension();
            $fileName = $newTravelOrderNumber . '_TRAINING_NOTIFICATION.' . $extension;
            $trainingNotificationFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $trainingNotification->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Training Notification File ID: ' . $trainingNotificationFileId);

        $meetingNotice = $this->request->getFile('meeting_notice');
        $meetingNoticeFileId = null;
        if ($meetingNotice && $meetingNotice->isValid()) {
            $content = file_get_contents($meetingNotice->getTempName());
            $extension = $meetingNotice->getExtension();
            $fileName = $newTravelOrderNumber . '_MEETING_NOTICE.' . $extension;
            $meetingNoticeFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $meetingNotice->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Meeting Notice File ID: ' . $meetingNoticeFileId);

        $conferenceProgram = $this->request->getFile('conference_program');
        $conferenceProgramFileId = null;
        if ($conferenceProgram && $conferenceProgram->isValid()) {
            $content = file_get_contents($conferenceProgram->getTempName());
            $extension = $conferenceProgram->getExtension();
            $fileName = $newTravelOrderNumber . '_CONFERENCE_PROGRAM.' . $extension;
            $conferenceProgramFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $conferenceProgram->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Conference Program File ID: ' . $conferenceProgramFileId);

        $otherDocument = $this->request->getFile('other_document');
        $otherDocumentFileId = null;
        if ($otherDocument && $otherDocument->isValid()) {
            $content = file_get_contents($otherDocument->getTempName());
            $extension = $otherDocument->getExtension();
            $fileName = $newTravelOrderNumber . '_OTHER_DOCUMENT.' . $extension;
            $otherDocumentFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $otherDocument->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Other Document File ID: ' . $otherDocumentFileId);


        $model = new TravelOrderModel();
        $data = [
            // Travel Order Number
            'travel_order_number' => $newTravelOrderNumber,
            // Personal Information
            'persons' => $this->request->getPost('persons'),
            // Travel Details
            'departure_date' => $this->request->getPost('departure_date'),
            'arrival_date' => $this->request->getPost('arrival_date'),
            'destination' => $this->request->getPost('destination'),
            'travel_purpose' => $this->request->getPost('travel_purpose'),
            // Supporting Documents
            'request_memo' => $requestMemoFileId ?? null,
            'special_order' => $specialOrderFileId ?? null,
            'request_letter' => $requestLetterFileId ?? null,
            'invitation_letter' => $invitationLetterFileId ?? null,
            'training_notification' => $trainingNotificationFileId ?? null,
            'meeting_notice' => $meetingNoticeFileId ?? null,
            'conference_program' => $conferenceProgramFileId ?? null,
            'other_document' => $otherDocumentFileId ?? null
        ];

        $result = $model->insertTravelOrder(
            $data['travel_order_number'],
            $data['persons'],
            $data['departure_date'],
            $data['arrival_date'],
            $data['destination'],
            $data['travel_purpose'],
            $data['request_memo'],
            $data['special_order'],
            $data['request_letter'],
            $data['invitation_letter'],
            $data['training_notification'],
            $data['meeting_notice'],
            $data['conference_program'],
            $data['other_document']
        );
        return redirect()->back()->with('toast', [
            'type' => $result ? 'success' : 'danger',
            'message' => $result
                ? "'{$newTravelOrderNumber}' created successfully."
                : "Failed to create '{$newTravelOrderNumber}'."
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
                'status'              => '<span class="badge bg-warning">' . esc($row['status']) . '</span>',
                'actions'             => '
                                        <a href="' . route_to('travel-orders.show', $row['travel_order_id']) . '" 
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> 
                                        </a>
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
}
