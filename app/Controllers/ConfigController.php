<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PHPUnit\Framework\Constraint\IsFalse;
use App\Models\SelectModel;

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
            'units'        => $selectModel->selectUnits()
        ];

        return view('admin/config', $data);
    }


    public function addOrganization() {}
    public function addDivision() {}
    public function addUnit() {}

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
