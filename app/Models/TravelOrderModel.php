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
    protected $useTimestamps = true;
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
        array  $attachments,
        ?int $unit_id,
        ?int $division_id,
        int $organization_id     // ['request_memo' => ['file_id' => ..., 'file_name' => ...], ...]
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
            'unit_id'              => $unit_id,
            'division_id'          => $division_id,
            'organization_id' => $organization_id
        ]);
        $travelOrderId = $this->getInsertID();

        // ── Persons ────────────────────────────────────────────────────────
        foreach ($persons as $person) {
            $this->db->table('travel_order_users')->insert([
                'travel_order_id' => $travelOrderId,
                'user_id'         => null,
                'name'            => trim($person['name']),
                'salary_grade'    => trim($person['salary_grade']),
                // 'division'        => trim($person['division_section_unit']),
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
     *  Summary of getTravelOrderDetails
     * - fetches full detail of a travel order including its attachments
     * @param int $travelOrderId
     * @return array
     */

    public function getTravelOrderDetails($travelOrderId)
    {
        $order = $this->db->table('travel_orders to')
            ->select('
            to.travel_order_id,
            to.travel_order_number,
            to.user_id,
            to.departure_date,
            to.arrival_date,
            to.destination,
            to.purpose_of_travel,
            to.status,
            to.unit_id,
            u.unit_supervisor_position,
            to.approved_by_supervisor,
            to.supervisor_remarks,
            to.division_id,
            d.division_head_position,
            to.approved_by_division_head,
            to.division_head_remarks,
            to.organization_id,
            o.organization_head_position,
            to.approved_by_organization_head,
            to.organization_head_remarks,
            CONCAT(us.first_name, " ", us.last_name) AS applicant_name,
            us.position AS applicant_position,
            to.created_at
        ')
            ->join('units u', 'u.unit_id = to.unit_id', 'left')
            ->join('divisions d', 'd.division_id = to.division_id', 'left')
            ->join('organization o', 'o.organization_id = to.organization_id', 'left')
            ->join('users us', 'us.user_id = to.user_id', 'left')
            ->where('to.travel_order_id', $travelOrderId)
            ->get()->getRowArray();

        if (!$order) {
            return null;
        }

        // Fetch persons
        $order['persons'] = $this->db->table('travel_order_users')
            ->select('name, position, salary_grade')
            ->where('travel_order_id', $travelOrderId)
            ->get()->getResultArray();

        // Fetch attachments — join to get file_id from google drive
        $order['attachments'] = $this->db->table('travel_order_attachments ta')
            ->select('ta.attachment_type, ta.file_id, ta.attachment_name')
            ->where('ta.travel_order_id', $travelOrderId)
            ->get()->getResultArray();

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

public function printTO($travelOrderId)
{
    $travel_order = $this->db->table('travel_orders to')
        ->select('
            to.travel_order_id,
            to.travel_order_number,
            to.departure_date,
            to.arrival_date,
            to.destination,
            to.purpose_of_travel,
            d.division_head_position,
            CONCAT(dh.first_name, " ", dh.last_name) AS division_head_name,
            o.organization_head_position,
            CONCAT(oh.first_name, " ", oh.last_name) AS organization_head_name
        ')
        ->join('divisions d', 'd.division_id = to.division_id', 'left')
        ->join('users dh', 'dh.user_id = d.division_head_id', 'left')
        ->join('organization o', 'o.organization_id = to.organization_id', 'left')
        ->join('users oh', 'oh.user_id = o.organization_head_id', 'left')
        ->where('to.travel_order_id', $travelOrderId)
        ->get()->getRowArray();

    if (!$travel_order) {
        return null;
    }

    $travel_order['persons'] = $this->db->table('travel_order_users')
        ->select('name, position, salary_grade')
        ->where('travel_order_id', $travelOrderId)
        ->get()->getResultArray();

    return $travel_order;
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
