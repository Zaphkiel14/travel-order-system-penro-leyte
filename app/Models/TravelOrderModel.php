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
        $travel_order_number,
        $persons,
        $departure_date,
        $arrival_date,
        $destination,
        $travel_purpose,
        $request_memo,
        $special_order,
        $request_letter,
        $invitation_letter,
        $training_notification,
        $meeting_notice,
        $conference_program,
        $other_document
    )
    {

        $this->db->transStart();

        // insert travel order information
        $this->insert([
            'travel_order_number' => $travel_order_number,
            'user_id' => session()->get('user_id'),
            'departure_date' => $departure_date,
            'arrival_date' => $arrival_date,
            'destination' => $destination,
            'purpose_of_travel' => $travel_purpose,
        ]);
        $travelOrderId = $this->getInsertID();

    // Step 2: Insert each person into travel_order_users
    foreach ($persons as $person) {
        $this->db->table('travel_order_users')->insert([
            'travel_order_id' => $travelOrderId,
            'user_id'         => null,                              // null = manually typed person, not a system user
            'name'            => trim($person['name']),
            'salary_grade'    => trim($person['salary_grade']),
            'division'        => trim($person['division_section_unit']), // map form field -> column name
            'position'        => trim($person['position']),
        ]);
    }

        // insert attachments
        $attachments = [
            'request_memo' => $request_memo,
            'special_order' => $special_order,
            'request_letter' => $request_letter,
            'invitation_letter' => $invitation_letter,
            'training_notification' => $training_notification,
            'meeting_notice' => $meeting_notice,
            'conference_program' => $conference_program,
            'other_document' => $other_document
        ];
        foreach ($attachments as $type => $fileId) {
            if ($fileId) {
                $this->db->table('travel_order_attachments')->insert([
                    'travel_order_id' => $travelOrderId,
                    'attachment_id' => $fileId,
                    'attachment_type' => $type
                ]);
            }
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
        $builder = $this->db->table('travel_orders');
        $builder->select('
            to.travel_order_id,
            to.travel_order_number,
            to.departure_date,
            to.arrival_date,
            to.destination,
            to.purpose_of_travel,
            to.status,
            to.approved_by_supervisor,
            to.supervisor_remarks,
            to.approved_by_division_head,
            to.division_head_remarks,
            to.approved_by_organization_head,
            to.organization_head_remarks,
            to.created_at
            ')
            ->from('travel_orders to')
            ->where('to.travel_order_id', $travelOrderId);
        $travel_order_items = $builder->get()->getResult();
        foreach ($travel_order_items as $item) {
            $attachmentBuilder = $this->db->table('travel_order_attachments ta');
            $attachmentBuilder->select('
                to.travel_order_id,
                a.attachment_id,
                a.file_id,
                a.attachment_name,
                a.attachment_type,
            ');
            $attachmentBuilder->join('attachments a', 'a.attachment_id = ta.attachment_id', 'left');
            $attachmentBuilder->join('travel_orders to', 'to.travel_order_id = ta.travel_order_id', 'left');
            $attachmentBuilder->where('ta.travel_order_id', $item->travel_order_id);
            $item->attachments = $attachmentBuilder->get()->getResult();
        }
        return $travel_order_items;
    }

    /**
     * Summary of getMyTravelOrders
     * - fetches travel orders of the currently logged in user
     * @param mixed $userId
     * @return array
     */
    public function getMyTravelOrders($userId)
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
            ->where('user_id', $userId);
        return $this->get()->getResult();
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
