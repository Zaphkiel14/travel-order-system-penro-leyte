<?php

namespace App\Models;

use CodeIgniter\Model;

class TravelOrderModel extends Model
{
    protected $table            = 'travel_orders';
    protected $primaryKey       = 'travel_order_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'travel_order_number',
        'user_id',
        'departure_date',
        'arrival_date',
        'destination',
        'purpose_of_travel',
        'status',
        'unit_id',
        'approved_by_supervisor',
        'supervisor_remarks',
        'division_id',
        'approved_by_division_head',
        'division_head_remarks',
        'organization_id',
        'approved_by_organization_head',
        'organization_head_remarks'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


public function insertTravelOrder(
    string $travel_order_number,
    array  $persons,
    string $departure_date,
    string $arrival_date,
    string $destination,
    string $travel_purpose,
    array  $attachments       // ['request_memo' => ['file_id' => ..., 'file_name' => ...], ...]
): int|false {

    $this->db->transStart();

    // ── Travel order record ────────────────────────────────────────────
    $this->insert([
        'travel_order_number' => $travel_order_number,
        'user_id'             => session()->get('user_id'),
        'departure_date'      => $departure_date,
        'arrival_date'        => $arrival_date,
        'destination'         => $destination,
        'purpose_of_travel'   => $travel_purpose,
    ]);
    $travelOrderId = $this->getInsertID();

    // ── Persons ────────────────────────────────────────────────────────
    foreach ($persons as $person) {
        $this->db->table('travel_order_users')->insert([
            'travel_order_id' => $travelOrderId,
            'user_id'         => null,
            'name'            => trim($person['name']),
            'salary_grade'    => trim($person['salary_grade']),
            'position'        => trim($person['position']),
        ]);
    }

    // ── Attachments ────────────────────────────────────────────────────
    foreach ($attachments as $type => $file) {
        // Skip fields where no file was uploaded
        if (!$file || empty($file['file_id'])) {
            continue;
        }

        $this->db->table('travel_order_attachments')->insert([
            'travel_order_id' => $travelOrderId,
            'file_id'         => $file['file_id'],
            'attachment_name' => $file['file_name'],
            'attachment_type' => $type,
        ]);
    }

    $this->db->transComplete();

    return $this->db->transStatus() ? $travelOrderId : false;
}
/**
 * Fetches full detail of a travel order including persons, attachments,
 * and all data needed for the view modal (signatories, unit/division names).
 */
public function getTravelOrderDetails(int $travelOrderId): ?array
{
    $order = $this->db->table('travel_orders tord')
        ->select('
            tord.travel_order_id,
            tord.travel_order_number,
            tord.departure_date,
            tord.arrival_date,
            tord.destination,
            tord.purpose_of_travel,
            tord.status,
            tord.user_id,
            tord.created_at,
            CONCAT(applicant.first_name, " ", applicant.last_name) AS applicant_name,
            applicant.position AS applicant_position,
            tord.unit_id,
            un.unit_name,
            tord.approved_by_supervisor,
            tord.supervisor_remarks,
            tord.division_id,
            div.division_name,
            CONCAT(div_head.first_name, " ", div_head.last_name) AS division_head_name,
            div_head.position AS division_head_position,
            tord.approved_by_division_head,
            tord.division_head_remarks,
            tord.organization_id,
            org.organization_name,
            CONCAT(org_head.first_name, " ", org_head.last_name) AS organization_head_name,
            org_head.position AS organization_head_position,
            tord.approved_by_organization_head,
            tord.organization_head_remarks
        ')
        ->join('users applicant',  'applicant.user_id = tord.user_id',              'left')
        ->join('units un',         'un.unit_id = tord.unit_id',                     'left')
        ->join('divisions div',    'div.division_id = tord.division_id',            'left')
        ->join('users div_head',   'div_head.user_id = div.division_head_id',       'left')
        ->join('organization org', 'org.organization_id = tord.organization_id',    'left')
        ->join('users org_head',   'org_head.user_id = org.organization_user_id',   'left')
        ->where('tord.travel_order_id', $travelOrderId)
        ->where('tord.deleted_at IS NULL', null, false)
        ->get()
        ->getRowArray();

    if (!$order) {
        return null;
    }

    $order['persons'] = $this->db
        ->table('travel_order_users')
        ->select('name, position, salary_grade')
        ->where('travel_order_id', $travelOrderId)
        ->get()
        ->getResultArray();

    $order['attachments'] = $this->db
        ->table('travel_order_attachments')
        ->select('file_id, attachment_name, attachment_type')
        ->where('travel_order_id', $travelOrderId)
        ->get()
        ->getResultArray();

    return $order;
}
    /**
     * Summary of getMyTravelOrders
     * - fetches travel orders of the currently logged in user
     * @param mixed $userId
     * @return static
     */

public function getMyTravelOrdersQuery($userId): static
{
    return $this->select('
            travel_order_id,
            travel_order_number,
            departure_date,
            arrival_date,
            destination,
            purpose_of_travel,
            status,
            created_at
        ')
        ->where('user_id', $userId);
    // Returns the model itself (query builder state), NOT results yet
    // Caller decides whether to count, paginate, or fetch
}


    /**
     * Summary of getSupervisorTravelOrders
     * - fetches travel orders for a supervisor based on the unit they are supervising
     * @param mixed $unitId
     * @return array
     */
    public function getSupervisorTravelOrders($unitId)
    {
        $this->select('
            travel_order_id,
            travel_order_number,
            departure_date,
            arrival_date,
            destination,
            purpose_of_travel,
            status,
            unit_id,
            approved_by_supervisor,
            supervisor_remarks,
            division_id,
            approved_by_division_head,
            division_head_remarks,
            organization_id,
            approved_by_organization_head,
            organization_head_remarks,
            created_at
        ')
            ->where('unit_id', $unitId);
        return $this->get()->getResult();
    }

    /**
     * Summary of getDivisionHeadTravelOrders
     * - fetches travel orders for a division head based on the division they are heading
     * @param mixed $divisionId
     * @return array
     */
    public function getDivisionHeadTravelOrders($divisionId)
    {
        $this->select('
            travel_order_id,
            travel_order_number,
            departure_date,
            arrival_date,
            destination,
            purpose_of_travel,
            status,
            unit_id,
            approved_by_supervisor,
            supervisor_remarks,
            division_id,
            approved_by_division_head,
            division_head_remarks,
            organization_id,
            approved_by_organization_head,
            organization_head_remarks,
            created_at
        ')
            ->where('division_id', $divisionId);
        return $this->get()->getResult();
    }

    /**
     * Summary of getOrganizationHeadTravelOrders
     * - fetches travel orders for an organization head based on the organization they are heading
     * @param mixed $organizationId
     * @return array
     */
    public function getOrganizationHeadTravelOrders($organizationId)
    {
        $this->select('
                travel_order_id,
                travel_order_number,
                departure_date,
                arrival_date,
                destination,
                purpose_of_travel,
                status,
                unit_id,
                approved_by_supervisor,
                supervisor_remarks,
                division_id,
                approved_by_division_head,
                division_head_remarks,
                organization_id,
                approved_by_organization_head,
                organization_head_remarks,
                created_at
            ')
            ->where('organization_id', $organizationId);
        return $this->get()->getResult();
    }

    public function approveBySupervisor($travelOrderId, $supervisorName, $remarks)
    {
        $this->db->transStart();
        $data = [
            'status' => 'Approved by Supervisor',
            'approved_by_supervisor' => $supervisorName,
            'supervisor_remarks' => $remarks
        ];
        $this->update($travelOrderId, $data);
        $this->db->transComplete();
        return $this->db->transStatus() ? $travelOrderId : false;
    }

    public function rejectBySupervisor($travelOrderId, $supervisorName, $remarks)
    {
        $this->db->transStart();
        $data = [
            'status' => 'Rejected by Supervisor',
            'approved_by_supervisor' => $supervisorName,
            'supervisor_remarks' => $remarks
        ];
        $this->update($travelOrderId, $data);
        $this->db->transComplete();
        return $this->db->transStatus() ? $travelOrderId : false;
    }

    public function approveByDivisionHead($travelOrderId, $divisionHeadName, $remarks)
    {
        $this->db->transStart();
        $data = [
            'status' => 'Approved by Division Head',
            'approved_by_division_head' => $divisionHeadName,
            'division_head_remarks' => $remarks
        ];
        $this->update($travelOrderId, $data);
        $this->db->transComplete();
        return $this->db->transStatus() ? $travelOrderId : false;
    }

    public function rejectByDivisionHead($travelOrderId, $divisionHeadName, $remarks)
    {
        $this->db->transStart();
        $data = [
            'status' => 'Rejected by Division Head',
            'approved_by_division_head' => $divisionHeadName,
            'division_head_remarks' => $remarks
        ];
        $this->update($travelOrderId, $data);
        $this->db->transComplete();
        return $this->db->transStatus() ? $travelOrderId : false;
    }

    public function approveByOrganizationHead($travelOrderId, $organizationHeadName, $remarks)
    {
        $this->db->transStart();
        $data = [
            'status' => 'Approved by Organization Head',
            'approved_by_organization_head' => $organizationHeadName,
            'organization_head_remarks' => $remarks
        ];
        $this->update($travelOrderId, $data);
        $this->db->transComplete();
        return $this->db->transStatus() ? $travelOrderId : false;
    }
    public function rejectByOrganizationHead($travelOrderId, $organizationHeadName, $remarks)
    {
        $this->db->transStart();
        $data = [
            'status' => 'Rejected by Organization Head',
            'approved_by_organization_head' => $organizationHeadName,
            'organization_head_remarks' => $remarks
        ];
        $this->update($travelOrderId, $data);
        $this->db->transComplete();
        return $this->db->transStatus() ? $travelOrderId : false;
    }
}
