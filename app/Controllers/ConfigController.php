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

    

    //     public function viewOrganization($id)
    // {
    //     if (!session()->get('isLoggedIn')) {
    //         return $this->renderError($this->errorHandler->unauthorized());
    //     }

    //     $orgBuilder = $this->db->table('organization');
    //     $organization = $orgBuilder
    //         ->where('organization_id', $id)
    //         ->get()
    //         ->getRow();

    //     $divBuilder = $this->db->table('divisions');
    //     $organization->divisions = $divBuilder
    //         ->where('organization_id', $organization->organization_id)
    //         ->get()
    //         ->getResult();

    //     foreach ($organization->divisions as $division) {
    //         $unitBuilder = $this->db->table('units');
    //         $division->units = $unitBuilder
    //             ->where('division_id', $division->division_id)
    //             ->get()
    //             ->getResult();
    //     }

    //     $data = [
    //         'title'        => 'Travel Order | View Organization',
    //         'page'         => 'Configuration',
    //         'organization' => $organization,
    //     ];

    //     return view('admin/modals/view_organization', $data);
    // }

    // public function editOrganization($id)
    // {
    //     if (!session()->get('isLoggedIn')) {
    //         return $this->renderError($this->errorHandler->unauthorized());
    //     }

    //     $orgBuilder = $this->db->table('organization');
    //     $organization = $orgBuilder
    //         ->where('organization_id', $id)
    //         ->get()
    //         ->getRow();

    //     $divBuilder = $this->db->table('divisions');
    //     $organization->divisions = $divBuilder
    //         ->where('organization_id', $organization->organization_id)
    //         ->get()
    //         ->getResult();

    //     $data = [
    //         'title'        => 'Travel Order | Edit Organization',
    //         'page'         => 'Configuration',
    //         'organization' => $organization,
    //     ];

    //     return view('admin/modals/edit_organization', $data);
    // }

    // public function viewDivision($id)
    // {
    //     if (!session()->get('isLoggedIn')) {
    //         return $this->renderError($this->errorHandler->unauthorized());
    //     }

    //     $divBuilder = $this->db->table('divisions');
    //     $division = $divBuilder
    //         ->where('division_id', $id)
    //         ->get()
    //         ->getRow();

    //     // Parent
    //     $orgBuilder = $this->db->table('organization');
    //     $division->organization = $orgBuilder
    //         ->where('organization_id', $division->organization_id)
    //         ->get()
    //         ->getRow();

    //     // Children
    //     $unitBuilder = $this->db->table('units');
    //     $division->units = $unitBuilder
    //         ->where('division_id', $division->division_id)
    //         ->get()
    //         ->getResult();

    //     $data = [
    //         'title'    => 'Travel Order | View Division',
    //         'page'     => 'Configuration',
    //         'division' => $division,
    //     ];

    //     return view('admin/modals/view_division', $data);
    // }

    // public function editDivision($id)
    // {
    //     if (!session()->get('isLoggedIn')) {
    //         return $this->renderError($this->errorHandler->unauthorized());
    //     }

    //     $divBuilder = $this->db->table('divisions');
    //     $division = $divBuilder
    //         ->where('division_id', $id)
    //         ->get()
    //         ->getRow();

    //     // Parent — for dropdown or display
    //     $orgBuilder = $this->db->table('organization');
    //     $division->organization = $orgBuilder
    //         ->where('organization_id', $division->organization_id)
    //         ->get()
    //         ->getRow();

    //     // All organizations for dropdown
    //     $allOrgsBuilder = $this->db->table('organization');
    //     $organizations = $allOrgsBuilder->get()->getResult();

    //     $data = [
    //         'title'         => 'Travel Order | Edit Division',
    //         'page'          => 'Configuration',
    //         'division'      => $division,
    //         'organizations' => $organizations,
    //     ];

    //     return view('admin/modals/edit_division', $data);
    // }

    // public function viewUnit($id)
    // {
    //     if (!session()->get('isLoggedIn')) {
    //         return $this->renderError($this->errorHandler->unauthorized());
    //     }

    //     $unitBuilder = $this->db->table('units');
    //     $unit = $unitBuilder
    //         ->where('unit_id', $id)
    //         ->get()
    //         ->getRow();

    //     // Parent division
    //     $divBuilder = $this->db->table('divisions');
    //     $unit->division = $divBuilder
    //         ->where('division_id', $unit->division_id)
    //         ->get()
    //         ->getRow();

    //     // Grandparent organization
    //     $orgBuilder = $this->db->table('organization');
    //     $unit->division->organization = $orgBuilder
    //         ->where('organization_id', $unit->division->organization_id)
    //         ->get()
    //         ->getRow();

    //     $data = [
    //         'title' => 'Travel Order | View Unit',
    //         'page'  => 'Configuration',
    //         'unit'  => $unit,
    //     ];

    //     return view('admin/modals/view_unit', $data);
    // }

    // public function editUnit($id)
    // {
    //     if (!session()->get('isLoggedIn')) {
    //         return $this->renderError($this->errorHandler->unauthorized());
    //     }

    //     $unitBuilder = $this->db->table('units');
    //     $unit = $unitBuilder
    //         ->where('unit_id', $id)
    //         ->get()
    //         ->getRow();

    //     // Parent division
    //     $divBuilder = $this->db->table('divisions');
    //     $unit->division = $divBuilder
    //         ->where('division_id', $unit->division_id)
    //         ->get()
    //         ->getRow();

    //     // Grandparent organization
    //     $orgBuilder = $this->db->table('organization');
    //     $unit->division->organization = $orgBuilder
    //         ->where('organization_id', $unit->division->organization_id)
    //         ->get()
    //         ->getRow();

    //     // All divisions for dropdown
    //     $allDivsBuilder = $this->db->table('divisions');
    //     $divisions = $allDivsBuilder->get()->getResult();

    //     $data = [
    //         'title'     => 'Travel Order | Edit Unit',
    //         'page'      => 'Configuration',
    //         'unit'      => $unit,
    //         'divisions' => $divisions,
    //     ];

    //     return view('admin/modals/edit_unit', $data);
    // }
}
