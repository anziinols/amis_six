<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ActivitiesModel
 *
 * Handles database operations for the activities table
 * which represents activities related to performance outputs
 */
class ActivitiesModel extends Model
{
    protected $table            = 'activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'supervisor_id',
        'action_officer_id',
        'activity_title',
        'activity_description',
        'province_id',
        'district_id',
        'date_start',
        'date_end',
        'total_cost',
        'location',
        'type',
        'status',
        'status_by',
        'status_at',
        'status_remarks',
        'rating_score',
        'rated_at',
        'rated_by',
        'rate_remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation rules
    protected $validationRules = [
        'supervisor_id'         => 'permit_empty|integer',
        'action_officer_id'     => 'permit_empty|integer',
        'activity_title'        => 'required|max_length[500]',
        'activity_description'  => 'required',
        'province_id'           => 'required|integer',
        'district_id'           => 'required|integer',
        'date_start'            => 'required|valid_date',
        'date_end'              => 'required|valid_date',
        'total_cost'            => 'permit_empty|decimal',
        'location'              => 'permit_empty|max_length[255]',
        'type'                  => 'required|in_list[documents,trainings,meetings,agreements,inputs,infrastructures,outputs]',
        'status'                => 'permit_empty|in_list[pending,active,submitted,approved,rated]',
        'status_by'             => 'permit_empty|integer',
        'status_at'             => 'permit_empty|valid_date',
        'status_remarks'        => 'permit_empty',
        'rating_score'          => 'permit_empty|decimal',
        'rated_at'              => 'permit_empty|valid_date',
        'rated_by'              => 'permit_empty|integer',
        'rate_remarks'          => 'permit_empty',
        'created_by'            => 'permit_empty|integer',
        'updated_by'            => 'permit_empty|integer',
        'deleted_by'            => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setDefaultStatus'];
    protected $afterInsert  = [];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];

    /**
     * Set default status for new activities
     */
    protected function setDefaultStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'pending';
            $data['data']['status_at'] = date('Y-m-d H:i:s');
            $data['data']['status_by'] = session()->get('user_id') ?? null;
        }

        return $data;
    }



    /**
     * Get activities by type
     *
     * @param string $type
     * @param int $performanceOutputId
     * @return array
     */
    public function getByType($type)
    {
        return $this->where('type', $type)
                    ->orderBy('date_start', 'ASC')
                    ->findAll();
    }

    /**
     * Get activities by status
     *
     * @param string $status
     * @return array
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('date_start', 'ASC')
                    ->findAll();
    }

    /**
     * Get activity with detailed information
     *
     * @param int $id
     * @return array|null
     */
    public function getActivityWithDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities a')
            ->select('a.*,
                     CONCAT(u1.fname, " ", u1.lname) as supervisor_name,
                     CONCAT(u2.fname, " ", u2.lname) as action_officer_name,
                     CONCAT(u3.fname, " ", u3.lname) as created_by_name,
                     CONCAT(u4.fname, " ", u4.lname) as status_by_name,
                     CONCAT(u5.fname, " ", u5.lname) as rated_by_name,
                     p.name as province_name,
                     d.name as district_name')
            ->join('users u1', 'a.supervisor_id = u1.id', 'left')
            ->join('users u2', 'a.action_officer_id = u2.id', 'left')
            ->join('users u3', 'a.created_by = u3.id', 'left')
            ->join('users u4', 'a.status_by = u4.id', 'left')
            ->join('users u5', 'a.rated_by = u5.id', 'left')
            ->join('gov_structure p', 'a.province_id = p.id AND p.level = "province"', 'left')
            ->join('gov_structure d', 'a.district_id = d.id AND d.level = "district"', 'left')
            ->where('a.id', $id)
            ->where('a.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all activities with detailed information
     * Sorted by: 3 upcoming activities (date_start >= today) in ASC order, then past activities in DESC order
     *
     * @return array
     */
    public function getAllWithDetails()
    {
        $db = \Config\Database::connect();

        // Get current date
        $currentDate = date('Y-m-d');

        // Query for upcoming activities (3 closest to today)
        $upcomingQuery = "
            SELECT a.*,
                   CONCAT(u1.fname, ' ', u1.lname) as supervisor_name,
                   CONCAT(u2.fname, ' ', u2.lname) as action_officer_name,
                   CONCAT(u3.fname, ' ', u3.lname) as created_by_name,
                   CONCAT(u4.fname, ' ', u4.lname) as status_by_name,
                   CONCAT(u5.fname, ' ', u5.lname) as rated_by_name,
                   p.name as province_name,
                   d.name as district_name
            FROM activities a
            LEFT JOIN users u1 ON a.supervisor_id = u1.id
            LEFT JOIN users u2 ON a.action_officer_id = u2.id
            LEFT JOIN users u3 ON a.created_by = u3.id
            LEFT JOIN users u4 ON a.status_by = u4.id
            LEFT JOIN users u5 ON a.rated_by = u5.id
            LEFT JOIN gov_structure p ON a.province_id = p.id AND p.level = 'province'
            LEFT JOIN gov_structure d ON a.district_id = d.id AND d.level = 'district'
            WHERE a.deleted_at IS NULL AND a.date_start >= ?
            ORDER BY a.date_start ASC
            LIMIT 3
        ";

        // Query for past activities
        $pastQuery = "
            SELECT a.*,
                   CONCAT(u1.fname, ' ', u1.lname) as supervisor_name,
                   CONCAT(u2.fname, ' ', u2.lname) as action_officer_name,
                   CONCAT(u3.fname, ' ', u3.lname) as created_by_name,
                   CONCAT(u4.fname, ' ', u4.lname) as status_by_name,
                   CONCAT(u5.fname, ' ', u5.lname) as rated_by_name,
                   p.name as province_name,
                   d.name as district_name
            FROM activities a
            LEFT JOIN users u1 ON a.supervisor_id = u1.id
            LEFT JOIN users u2 ON a.action_officer_id = u2.id
            LEFT JOIN users u3 ON a.created_by = u3.id
            LEFT JOIN users u4 ON a.status_by = u4.id
            LEFT JOIN users u5 ON a.rated_by = u5.id
            LEFT JOIN gov_structure p ON a.province_id = p.id AND p.level = 'province'
            LEFT JOIN gov_structure d ON a.district_id = d.id AND d.level = 'district'
            WHERE a.deleted_at IS NULL AND a.date_start < ?
            ORDER BY a.date_start DESC
        ";

        // Execute queries
        $upcoming = $db->query($upcomingQuery, [$currentDate])->getResultArray();
        $past = $db->query($pastQuery, [$currentDate])->getResultArray();

        // Merge results: upcoming first, then past
        return array_merge($upcoming, $past);
    }

    /**
     * Get activity types
     *
     * @return array
     */
    public function getActivityTypes()
    {
        return [
            'documents' => 'Documents',
            'trainings' => 'Trainings',
            'meetings' => 'Meetings',
            'agreements' => 'Agreements',
            'inputs' => 'Inputs',
            'infrastructures' => 'Infrastructures',
            'outputs' => 'Outputs'
        ];
    }

    /**
     * Get activity statuses
     *
     * @return array
     */
    public function getActivityStatuses()
    {
        return [
            'pending' => 'Pending',
            'active' => 'Active',
            'submitted' => 'Submitted',
            'approved' => 'Approved',
            'rated' => 'Rated'
        ];
    }

    /**
     * Update activity status
     *
     * @param int $id
     * @param string $status
     * @param string $remarks
     * @return bool
     */
    public function updateStatus($id, $status, $remarks = '')
    {
        $data = [
            'status' => $status,
            'status_at' => date('Y-m-d H:i:s'),
            'status_by' => session()->get('user_id') ?? null,
            'status_remarks' => $remarks,
            'updated_by' => session()->get('user_id') ?? null
        ];

        return $this->update($id, $data);
    }

    /**
     * Rate an activity
     *
     * @param int $id
     * @param float $score
     * @param string $remarks
     * @return bool
     */
    public function rateActivity($id, $score, $remarks = '')
    {
        $data = [
            'rating_score' => $score,
            'rated_at' => date('Y-m-d H:i:s'),
            'rated_by' => session()->get('user_id') ?? null,
            'rate_remarks' => $remarks,
            'status' => 'rated',
            'status_at' => date('Y-m-d H:i:s'),
            'status_by' => session()->get('user_id') ?? null,
            'updated_by' => session()->get('user_id') ?? null
        ];

        return $this->update($id, $data);
    }

    /**
     * Get activities count by status
     *
     * @return array
     */
    public function getStatusCounts()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get activities by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getByDateRange($startDate, $endDate)
    {
        return $this->where('date_start >=', $startDate)
                    ->where('date_end <=', $endDate)
                    ->orderBy('date_start', 'ASC')
                    ->findAll();
    }

    /**
     * Get activities by supervisor
     * Sorted by: 3 upcoming activities (date_start >= today) in ASC order, then past activities in DESC order
     *
     * @param int $supervisorId
     * @return array
     */
    public function getBySupervisor($supervisorId)
    {
        $db = \Config\Database::connect();

        // Get current date
        $currentDate = date('Y-m-d');

        // Query for upcoming activities (3 closest to today)
        $upcomingQuery = "
            SELECT a.*,
                   CONCAT(u1.fname, ' ', u1.lname) as supervisor_name,
                   CONCAT(u2.fname, ' ', u2.lname) as action_officer_name,
                   CONCAT(u3.fname, ' ', u3.lname) as created_by_name,
                   CONCAT(u4.fname, ' ', u4.lname) as status_by_name,
                   CONCAT(u5.fname, ' ', u5.lname) as rated_by_name,
                   p.name as province_name,
                   d.name as district_name
            FROM activities a
            LEFT JOIN users u1 ON a.supervisor_id = u1.id
            LEFT JOIN users u2 ON a.action_officer_id = u2.id
            LEFT JOIN users u3 ON a.created_by = u3.id
            LEFT JOIN users u4 ON a.status_by = u4.id
            LEFT JOIN users u5 ON a.rated_by = u5.id
            LEFT JOIN gov_structure p ON a.province_id = p.id AND p.level = 'province'
            LEFT JOIN gov_structure d ON a.district_id = d.id AND d.level = 'district'
            WHERE a.deleted_at IS NULL AND a.supervisor_id = ? AND a.date_start >= ?
            ORDER BY a.date_start ASC
            LIMIT 3
        ";

        // Query for past activities
        $pastQuery = "
            SELECT a.*,
                   CONCAT(u1.fname, ' ', u1.lname) as supervisor_name,
                   CONCAT(u2.fname, ' ', u2.lname) as action_officer_name,
                   CONCAT(u3.fname, ' ', u3.lname) as created_by_name,
                   CONCAT(u4.fname, ' ', u4.lname) as status_by_name,
                   CONCAT(u5.fname, ' ', u5.lname) as rated_by_name,
                   p.name as province_name,
                   d.name as district_name
            FROM activities a
            LEFT JOIN users u1 ON a.supervisor_id = u1.id
            LEFT JOIN users u2 ON a.action_officer_id = u2.id
            LEFT JOIN users u3 ON a.created_by = u3.id
            LEFT JOIN users u4 ON a.status_by = u4.id
            LEFT JOIN users u5 ON a.rated_by = u5.id
            LEFT JOIN gov_structure p ON a.province_id = p.id AND p.level = 'province'
            LEFT JOIN gov_structure d ON a.district_id = d.id AND d.level = 'district'
            WHERE a.deleted_at IS NULL AND a.supervisor_id = ? AND a.date_start < ?
            ORDER BY a.date_start DESC
        ";

        // Execute queries
        $upcoming = $db->query($upcomingQuery, [$supervisorId, $currentDate])->getResultArray();
        $past = $db->query($pastQuery, [$supervisorId, $currentDate])->getResultArray();

        // Merge results: upcoming first, then past
        return array_merge($upcoming, $past);
    }

    /**
     * Get activities by action officer
     * Sorted by: 3 upcoming activities (date_start >= today) in ASC order, then past activities in DESC order
     *
     * @param int $actionOfficerId
     * @return array
     */
    public function getByActionOfficer($actionOfficerId)
    {
        $db = \Config\Database::connect();

        // Get current date
        $currentDate = date('Y-m-d');

        // Query for upcoming activities (3 closest to today)
        $upcomingQuery = "
            SELECT a.*,
                   CONCAT(u1.fname, ' ', u1.lname) as supervisor_name,
                   CONCAT(u2.fname, ' ', u2.lname) as action_officer_name,
                   CONCAT(u3.fname, ' ', u3.lname) as created_by_name,
                   CONCAT(u4.fname, ' ', u4.lname) as status_by_name,
                   CONCAT(u5.fname, ' ', u5.lname) as rated_by_name,
                   p.name as province_name,
                   d.name as district_name
            FROM activities a
            LEFT JOIN users u1 ON a.supervisor_id = u1.id
            LEFT JOIN users u2 ON a.action_officer_id = u2.id
            LEFT JOIN users u3 ON a.created_by = u3.id
            LEFT JOIN users u4 ON a.status_by = u4.id
            LEFT JOIN users u5 ON a.rated_by = u5.id
            LEFT JOIN gov_structure p ON a.province_id = p.id AND p.level = 'province'
            LEFT JOIN gov_structure d ON a.district_id = d.id AND d.level = 'district'
            WHERE a.deleted_at IS NULL AND a.action_officer_id = ? AND a.date_start >= ?
            ORDER BY a.date_start ASC
            LIMIT 3
        ";

        // Query for past activities
        $pastQuery = "
            SELECT a.*,
                   CONCAT(u1.fname, ' ', u1.lname) as supervisor_name,
                   CONCAT(u2.fname, ' ', u2.lname) as action_officer_name,
                   CONCAT(u3.fname, ' ', u3.lname) as created_by_name,
                   CONCAT(u4.fname, ' ', u4.lname) as status_by_name,
                   CONCAT(u5.fname, ' ', u5.lname) as rated_by_name,
                   p.name as province_name,
                   d.name as district_name
            FROM activities a
            LEFT JOIN users u1 ON a.supervisor_id = u1.id
            LEFT JOIN users u2 ON a.action_officer_id = u2.id
            LEFT JOIN users u3 ON a.created_by = u3.id
            LEFT JOIN users u4 ON a.status_by = u4.id
            LEFT JOIN users u5 ON a.rated_by = u5.id
            LEFT JOIN gov_structure p ON a.province_id = p.id AND p.level = 'province'
            LEFT JOIN gov_structure d ON a.district_id = d.id AND d.level = 'district'
            WHERE a.deleted_at IS NULL AND a.action_officer_id = ? AND a.date_start < ?
            ORDER BY a.date_start DESC
        ";

        // Execute queries
        $upcoming = $db->query($upcomingQuery, [$actionOfficerId, $currentDate])->getResultArray();
        $past = $db->query($pastQuery, [$actionOfficerId, $currentDate])->getResultArray();

        // Merge results: upcoming first, then past
        return array_merge($upcoming, $past);
    }

    /**
     * Get activities by province
     *
     * @param int $provinceId
     * @return array
     */
    public function getByProvince($provinceId)
    {
        return $this->where('province_id', $provinceId)
                    ->orderBy('date_start', 'ASC')
                    ->findAll();
    }

    /**
     * Get activities by district
     *
     * @param int $districtId
     * @return array
     */
    public function getByDistrict($districtId)
    {
        return $this->where('district_id', $districtId)
                    ->orderBy('date_start', 'ASC')
                    ->findAll();
    }

    /**
     * Search activities
     *
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm)
    {
        return $this->like('activity_title', $searchTerm)
                    ->orLike('activity_description', $searchTerm)
                    ->orLike('location', $searchTerm)
                    ->orderBy('date_start', 'ASC')
                    ->findAll();
    }



    /**
     * Get activities summary by type
     *
     * @return array
     */
    public function getSummaryByType()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities')
            ->select('type, COUNT(*) as count, SUM(total_cost) as total_cost')
            ->where('deleted_at', null)
            ->groupBy('type')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get upcoming activities (starting within next 30 days)
     *
     * @param int $days
     * @return array
     */
    public function getUpcomingActivities($days = 30)
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$days} days"));

        return $this->where('date_start >=', $startDate)
                    ->where('date_start <=', $endDate)
                    ->where('status !=', 'rated')
                    ->orderBy('date_start', 'ASC')
                    ->findAll();
    }

    /**
     * Get overdue activities (end date passed but not rated)
     *
     * @return array
     */
    public function getOverdueActivities()
    {
        $currentDate = date('Y-m-d');

        return $this->where('date_end <', $currentDate)
                    ->where('status !=', 'rated')
                    ->orderBy('date_end', 'ASC')
                    ->findAll();
    }
}
