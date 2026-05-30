<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PHPUnit\Framework\Constraint\IsFalse;
use App\Models\SelectModel;
use App\Models\OrganizationModel;
use App\Models\DivisionsModel;
use App\Models\UnitsModel;

class ConfigController extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->renderError(
                $this->errorHandler->unauthorized()
            );
        }
        if (session()->get('role') !== "admin") {
            return $this->renderError(
                $this->errorHandler->forbidden('Admins only.')
            );
        }

        $selectModel = new SelectModel();

        $data = [
            'title'        => 'Travel Order | Configurations',
            'page'         => 'Configuration',
            'orgstructure' => $selectModel->organizationStructure(),
            'organizations' => $selectModel->selectOrganization(),
            'divisions'    => $selectModel->selectDivisions(),
            'units'        => $selectModel->selectUnits(),
            'unitusers'    => $selectModel->getUnitUsers(),
            'divisionusers' => $selectModel->getDivisionUsers(),
            'penrouser' => $selectModel->getPenroUsers(),
            'orgData' => $selectModel->getOrganizationData()
        ];

        return view('admin/config', $data);
    }

    public function addDivision()
    {
        $data = [
            'parent_organization' => $this->request->getPost('parent_organization'),
            'division_name' => $this->request->getPost('division_name'),
            'division_head_position' => $this->request->getPost('division_head_position'),
            'division_head' => $this->request->getPost('division_head'),
            'linked_units' => $this->request->getPost('linked_units') ?? []
        ];

        $model = new DivisionsModel();
        $model->insertDivision(
            $data['parent_organization'],
            $data['division_name'],
            $data['division_head_position'],
            $data['division_head'] ?? null,
            $data['linked_units'] ?? []
        );

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => "Division '{$data['division_name']}' created successfully"
        ]);
    }

    public function addUnit()
    {
        $data = [
            'parent_division' => $this->request->getPost('parent_division'),
            'unit_name' => $this->request->getPost('unit_name'),
            'unit_head_position' => $this->request->getPost('unit_head_position'),
            'unit_head' => $this->request->getPost('unit_head'),
        ];

        $model = new UnitsModel();
        $model->insertUnit(
            $data['parent_division'],
            $data['unit_name'],
            $data['unit_head_position'],
            $data['unit_head'] ?? null
        );
        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => "Unit '{$data['unit_name']}' created successfully"
        ]);
    }

    public function updateOrganization()
    {

        $data = [
            'organization_id' => $this->request->getPost('organization_id'),
            'organization_name' => $this->request->getPost('organization_name'),
            'organization_head_position' => $this->request->getPost('organization_head_position'),
            'organization_head' => $this->request->getPost('organization_head'),
            'linked_divisions' => $this->request->getPost('linked_divisions') ?? []
        ];
        $model = new OrganizationModel();
        $model->updateOrganization(
            $data['organization_id'] ?? 1,
            $data['organization_name'],
            $data['organization_head_position'],
            $data['organization_head'] ?? null,
            $data['linked_divisions'] ?? []
        );
        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => "Organization '{$data['organization_name']}' updated successfully"
        ]);
    }

    /**
     * AJAX — fetch details for view modal
     */
    public function detailsConfig($type, $id)
    {
        try {
            switch ($type) {
                case 'organization':
                    $model = new OrganizationModel();
                    break;
                case 'division':
                    $model = new DivisionsModel();
                    break;
                case 'unit':
                    $model = new UnitsModel();
                    break;
                default:
                    throw new \Exception('Invalid type');
            }

            $data = $model->getDetails($id);

            if (!$data) {
                throw new \Exception(ucfirst($type) . ' not found');
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX — update organization / division / unit
     */
    public function updateConfig(string $type, int $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request.'
            ]);
        }

        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Forbidden.'
            ]);
        }

        try {
            switch ($type) {

                // ── ORGANIZATION ──────────────────────────────────────────
                case 'organization':
                    $orgModel = new OrganizationModel();
                    $orgModel->update($id, [
                        'organization_name'          => $this->request->getPost('organization_name'),
                        'organization_head_position' => $this->request->getPost('organization_head_position'),
                        'organization_head_id'       => $this->request->getPost('organization_head') ?: null,
                    ]);

                    // Re-link selected divisions to this organization
                    $linked = $this->request->getPost('linked_divisions') ?? [];
                    if (!empty($linked)) {
                        $batch = array_map(
                            fn($did) => ['division_id' => (int)$did, 'organization_id' => $id],
                            $linked
                        );
                        $this->db->table('divisions')->updateBatch($batch, 'division_id');
                    }
                    break;

                // ── DIVISION ──────────────────────────────────────────────
                case 'division':
                    $divModel = new DivisionsModel();
                    $divModel->update($id, [
                        'division_name'          => $this->request->getPost('division_name'),
                        'division_head_position' => $this->request->getPost('division_head_position'),
                        'division_head_id'       => $this->request->getPost('division_head') ?: null,
                        'organization_id'        => $this->request->getPost('parent_organization'),
                    ]);

                    // Re-link selected units to this division
                    $linked = $this->request->getPost('linked_units') ?? [];
                    if (!empty($linked)) {
                        $batch = array_map(
                            fn($uid) => ['unit_id' => (int)$uid, 'division_id' => $id],
                            $linked
                        );
                        $this->db->table('units')->updateBatch($batch, 'unit_id');
                    }
                    break;

                // ── UNIT ──────────────────────────────────────────────────
                case 'unit':
                    $unitModel = new UnitsModel();
                    $unitModel->update($id, [
                        'unit_name'          => $this->request->getPost('unit_name'),
                        'unit_head_position' => $this->request->getPost('unit_head_position'),
                        'unit_head_id'       => $this->request->getPost('unit_head') ?: null,
                        'division_id'        => $this->request->getPost('parent_division'),
                    ]);
                    break;

                default:
                    throw new \Exception('Invalid type.');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => ucfirst($type) . ' updated successfully.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}