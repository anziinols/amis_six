<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BranchesModel;
use App\Models\ProposalModel;
use App\Models\WorkplanModel;
use App\Models\WorkplanActivityModel;

use CodeIgniter\Controller;

/**
 * HRReportsController
 *
 * Handles the reporting functionality for Human Resources analytics.
 * Displays comprehensive HR reports including age distribution, gender distribution,
 * staff strength, workload distribution, financial accountability, and performance metrics.
 *
 * @package App\Controllers
 */
class HRReportsController extends Controller
{
    protected $userModel;
    protected $branchesModel;
    protected $proposalModel;
    protected $workplanModel;
    protected $workplanActivityModel;

    /**
     * Constructor initializes models
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->branchesModel = new BranchesModel();
        $this->proposalModel = new ProposalModel();
        $this->workplanModel = new WorkplanModel();
        $this->workplanActivityModel = new WorkplanActivityModel();
    }

    /**
     * Display the HR Reports Dashboard (Read-only)
     */
    public function index()
    {
        // Get all users with branch information
        $users = $this->userModel->getAllUsersWithBranches();

        // Get all branches
        $branches = $this->branchesModel->findAll();

        // Get proposals and workplans for workload and financial analysis
        $proposals = $this->proposalModel->getProposalsWithDetails();
        $workplans = $this->workplanModel->findAll();
        $activities = $this->workplanActivityModel->findAll();

        // Prepare comprehensive analytics
        $summaryStats = $this->prepareSummaryStats($users);
        $ageDistribution = $this->prepareAgeDistribution($users, $branches);
        $genderDistribution = $this->prepareGenderDistribution($users, $branches);
        $staffStrength = $this->prepareStaffStrength($users, $branches);
        $workloadDistribution = $this->prepareWorkloadDistribution($users, $proposals, $workplans, $activities);
        $financialAccountability = $this->prepareFinancialAccountability($users, $proposals);
        $performanceMetrics = $this->preparePerformanceMetrics($users, $proposals);

        // Prepare data for charts
        $chartData = $this->prepareComprehensiveChartData($users, $branches, $proposals, $workplans, $activities);

        // Pass all data to the view
        return view('reports_hr/reports_hr_comprehensive', [
            'title' => 'Comprehensive HR Analytics Dashboard',
            'users' => $users,
            'branches' => $branches,
            'proposals' => $proposals,
            'summaryStats' => $summaryStats,
            'ageDistribution' => $ageDistribution,
            'genderDistribution' => $genderDistribution,
            'staffStrength' => $staffStrength,
            'workloadDistribution' => $workloadDistribution,
            'financialAccountability' => $financialAccountability,
            'performanceMetrics' => $performanceMetrics,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Prepare summary statistics for HR dashboard
     *
     * @param array $users
     * @return array
     */
    private function prepareSummaryStats($users)
    {
        $totalUsers = count($users);
        $maleCount = 0;
        $femaleCount = 0;
        $evaluatorCount = 0;
        $totalTenure = 0;
        $usersWithJoinDate = 0;

        foreach ($users as $user) {
            // Gender count
            if (strtolower($user['gender']) === 'male') {
                $maleCount++;
            } elseif (strtolower($user['gender']) === 'female') {
                $femaleCount++;
            }

            // Evaluator count
            if ($user['is_evaluator'] == 1) {
                $evaluatorCount++;
            }

            // Tenure calculation
            if (!empty($user['joined_date'])) {
                $joinDate = new \DateTime($user['joined_date']);
                $currentDate = new \DateTime();
                $tenure = $currentDate->diff($joinDate)->days / 365.25; // Convert to years
                $totalTenure += $tenure;
                $usersWithJoinDate++;
            }
        }

        $averageTenure = $usersWithJoinDate > 0 ? $totalTenure / $usersWithJoinDate : 0;

        return [
            'total_users' => $totalUsers,
            'male_count' => $maleCount,
            'female_count' => $femaleCount,
            'male_percentage' => $totalUsers > 0 ? round(($maleCount / $totalUsers) * 100, 1) : 0,
            'female_percentage' => $totalUsers > 0 ? round(($femaleCount / $totalUsers) * 100, 1) : 0,
            'evaluator_count' => $evaluatorCount,
            'evaluator_percentage' => $totalUsers > 0 ? round(($evaluatorCount / $totalUsers) * 100, 1) : 0,
            'average_tenure' => round($averageTenure, 1),
            'users_with_join_date' => $usersWithJoinDate,
        ];
    }

    /**
     * Prepare overview chart data
     *
     * @param array $users
     * @param array $branches
     * @return array
     */
    private function prepareOverviewChartData($users, $branches)
    {
        // Gender distribution
        $genderData = ['male' => 0, 'female' => 0, 'unspecified' => 0];
        
        // Role distribution
        $roleData = [];
        
        // Branch distribution
        $branchData = [];
        
        foreach ($users as $user) {
            // Gender distribution
            $gender = strtolower($user['gender'] ?? '');
            if ($gender === 'male') {
                $genderData['male']++;
            } elseif ($gender === 'female') {
                $genderData['female']++;
            } else {
                $genderData['unspecified']++;
            }

            // Role distribution
            $role = $user['role'] ?? 'unspecified';
            $roleData[$role] = ($roleData[$role] ?? 0) + 1;

            // Branch distribution
            $branchName = $user['branch_name'] ?? 'Unassigned';
            $branchData[$branchName] = ($branchData[$branchName] ?? 0) + 1;
        }

        return [
            'gender' => $genderData,
            'roles' => $roleData,
            'branches' => $branchData,
        ];
    }

    /**
     * Prepare gender analytics data
     *
     * @param array $users
     * @param array $branches
     * @return array
     */
    private function prepareGenderAnalytics($users, $branches)
    {
        $analytics = [
            'total_users' => count($users),
            'gender_summary' => ['male' => 0, 'female' => 0, 'unspecified' => 0],
            'gender_by_role' => [],
            'gender_by_branch' => [],
            'gender_by_grade' => [],
            'evaluator_by_gender' => ['male' => 0, 'female' => 0, 'unspecified' => 0],
        ];

        foreach ($users as $user) {
            $gender = strtolower($user['gender'] ?? '');
            $genderKey = in_array($gender, ['male', 'female']) ? $gender : 'unspecified';

            // Gender summary
            $analytics['gender_summary'][$genderKey]++;

            // Gender by role
            $role = $user['role'] ?? 'unspecified';
            if (!isset($analytics['gender_by_role'][$role])) {
                $analytics['gender_by_role'][$role] = ['male' => 0, 'female' => 0, 'unspecified' => 0];
            }
            $analytics['gender_by_role'][$role][$genderKey]++;

            // Gender by branch
            $branchName = $user['branch_name'] ?? 'Unassigned';
            if (!isset($analytics['gender_by_branch'][$branchName])) {
                $analytics['gender_by_branch'][$branchName] = ['male' => 0, 'female' => 0, 'unspecified' => 0];
            }
            $analytics['gender_by_branch'][$branchName][$genderKey]++;

            // Gender by grade
            $grade = $user['grade'] ?? 'Unspecified';
            if (!isset($analytics['gender_by_grade'][$grade])) {
                $analytics['gender_by_grade'][$grade] = ['male' => 0, 'female' => 0, 'unspecified' => 0];
            }
            $analytics['gender_by_grade'][$grade][$genderKey]++;

            // Evaluator by gender
            if ($user['is_evaluator'] == 1) {
                $analytics['evaluator_by_gender'][$genderKey]++;
            }
        }

        // Calculate percentages
        $total = $analytics['total_users'];
        $analytics['gender_percentages'] = [
            'male' => $total > 0 ? round(($analytics['gender_summary']['male'] / $total) * 100, 1) : 0,
            'female' => $total > 0 ? round(($analytics['gender_summary']['female'] / $total) * 100, 1) : 0,
            'unspecified' => $total > 0 ? round(($analytics['gender_summary']['unspecified'] / $total) * 100, 1) : 0,
        ];

        return $analytics;
    }

    /**
     * Prepare gender chart data
     *
     * @param array $users
     * @param array $branches
     * @return array
     */
    private function prepareGenderChartData($users, $branches)
    {
        $analytics = $this->prepareGenderAnalytics($users, $branches);

        return [
            'gender_summary' => $analytics['gender_summary'],
            'gender_by_role' => $analytics['gender_by_role'],
            'gender_by_branch' => $analytics['gender_by_branch'],
            'gender_by_grade' => $analytics['gender_by_grade'],
            'evaluator_by_gender' => $analytics['evaluator_by_gender'],
        ];
    }

    /**
     * Prepare date joined analytics data
     *
     * @param array $users
     * @param array $branches
     * @return array
     */
    private function prepareDateJoinedAnalytics($users, $branches)
    {
        $analytics = [
            'total_users' => count($users),
            'users_with_join_date' => 0,
            'hiring_by_year' => [],
            'hiring_by_month' => [],
            'tenure_distribution' => [],
            'average_tenure' => 0,
            'tenure_by_role' => [],
            'tenure_by_branch' => [],
            'recent_hires' => [],
            'long_tenure' => [],
        ];

        $totalTenure = 0;
        $usersWithJoinDate = 0;
        $currentDate = new \DateTime();

        foreach ($users as $user) {
            if (!empty($user['joined_date'])) {
                $usersWithJoinDate++;
                $joinDate = new \DateTime($user['joined_date']);

                // Hiring by year
                $year = $joinDate->format('Y');
                $analytics['hiring_by_year'][$year] = ($analytics['hiring_by_year'][$year] ?? 0) + 1;

                // Hiring by month (for current year and last year)
                $monthKey = $joinDate->format('Y-m');
                $analytics['hiring_by_month'][$monthKey] = ($analytics['hiring_by_month'][$monthKey] ?? 0) + 1;

                // Calculate tenure
                $tenure = $currentDate->diff($joinDate);
                $tenureYears = $tenure->y + ($tenure->m / 12) + ($tenure->d / 365);
                $totalTenure += $tenureYears;

                // Tenure distribution
                $tenureRange = $this->getTenureRange($tenureYears);
                $analytics['tenure_distribution'][$tenureRange] = ($analytics['tenure_distribution'][$tenureRange] ?? 0) + 1;

                // Tenure by role
                $role = $user['role'] ?? 'unspecified';
                if (!isset($analytics['tenure_by_role'][$role])) {
                    $analytics['tenure_by_role'][$role] = ['total_tenure' => 0, 'count' => 0];
                }
                $analytics['tenure_by_role'][$role]['total_tenure'] += $tenureYears;
                $analytics['tenure_by_role'][$role]['count']++;

                // Tenure by branch
                $branchName = $user['branch_name'] ?? 'Unassigned';
                if (!isset($analytics['tenure_by_branch'][$branchName])) {
                    $analytics['tenure_by_branch'][$branchName] = ['total_tenure' => 0, 'count' => 0];
                }
                $analytics['tenure_by_branch'][$branchName]['total_tenure'] += $tenureYears;
                $analytics['tenure_by_branch'][$branchName]['count']++;

                // Recent hires (less than 1 year)
                if ($tenureYears < 1) {
                    $analytics['recent_hires'][] = array_merge($user, ['tenure_years' => $tenureYears]);
                }

                // Long tenure (more than 10 years)
                if ($tenureYears > 10) {
                    $analytics['long_tenure'][] = array_merge($user, ['tenure_years' => $tenureYears]);
                }
            }
        }

        $analytics['users_with_join_date'] = $usersWithJoinDate;
        $analytics['average_tenure'] = $usersWithJoinDate > 0 ? $totalTenure / $usersWithJoinDate : 0;

        // Calculate average tenure by role
        foreach ($analytics['tenure_by_role'] as $role => &$data) {
            $data['average_tenure'] = $data['count'] > 0 ? $data['total_tenure'] / $data['count'] : 0;
        }

        // Calculate average tenure by branch
        foreach ($analytics['tenure_by_branch'] as $branch => &$data) {
            $data['average_tenure'] = $data['count'] > 0 ? $data['total_tenure'] / $data['count'] : 0;
        }

        // Sort arrays
        ksort($analytics['hiring_by_year']);
        ksort($analytics['hiring_by_month']);

        return $analytics;
    }

    /**
     * Get tenure range for grouping
     *
     * @param float $tenureYears
     * @return string
     */
    private function getTenureRange($tenureYears)
    {
        if ($tenureYears < 1) return '0-1 years';
        if ($tenureYears < 3) return '1-3 years';
        if ($tenureYears < 5) return '3-5 years';
        if ($tenureYears < 10) return '5-10 years';
        if ($tenureYears < 15) return '10-15 years';
        return '15+ years';
    }

    /**
     * Prepare date joined chart data
     *
     * @param array $users
     * @param array $branches
     * @return array
     */
    private function prepareDateJoinedChartData($users, $branches)
    {
        $analytics = $this->prepareDateJoinedAnalytics($users, $branches);

        return [
            'hiring_by_year' => $analytics['hiring_by_year'],
            'hiring_by_month' => $analytics['hiring_by_month'],
            'tenure_distribution' => $analytics['tenure_distribution'],
            'tenure_by_role' => $analytics['tenure_by_role'],
            'tenure_by_branch' => $analytics['tenure_by_branch'],
        ];
    }

    /**
     * Prepare age distribution analytics
     *
     * @param array $users
     * @param array $branches
     * @return array
     */
    private function prepareAgeDistribution($users, $branches)
    {
        $ageDistribution = [
            'total_users' => count($users),
            'users_with_dob' => 0,
            'age_ranges' => [
                '18-25' => 0,
                '26-35' => 0,
                '36-45' => 0,
                '46-55' => 0,
                '56-65' => 0,
                '65+' => 0
            ],
            'age_by_branch' => [],
            'age_by_gender' => [
                'male' => [],
                'female' => [],
                'unspecified' => []
            ],
            'average_age' => 0,
            'youngest_employee' => null,
            'oldest_employee' => null
        ];

        $totalAge = 0;
        $usersWithDob = 0;
        $currentDate = new \DateTime();

        // Initialize branch age data
        foreach ($branches as $branch) {
            $ageDistribution['age_by_branch'][$branch['id']] = [
                'branch_name' => $branch['name'],
                'age_ranges' => [
                    '18-25' => 0,
                    '26-35' => 0,
                    '36-45' => 0,
                    '46-55' => 0,
                    '56-65' => 0,
                    '65+' => 0
                ],
                'average_age' => 0,
                'total_staff' => 0
            ];
        }

        foreach ($users as $user) {
            if (!empty($user['dobirth'])) {
                $usersWithDob++;
                $birthDate = new \DateTime($user['dobirth']);
                $age = $currentDate->diff($birthDate)->y;
                $totalAge += $age;

                // Age range classification
                $ageRange = $this->getAgeRange($age);
                $ageDistribution['age_ranges'][$ageRange]++;

                // Track youngest and oldest
                if ($ageDistribution['youngest_employee'] === null || $age < $ageDistribution['youngest_employee']['age']) {
                    $ageDistribution['youngest_employee'] = [
                        'name' => $user['fname'] . ' ' . $user['lname'],
                        'age' => $age,
                        'branch' => $user['branch_name'] ?? 'Unassigned'
                    ];
                }

                if ($ageDistribution['oldest_employee'] === null || $age > $ageDistribution['oldest_employee']['age']) {
                    $ageDistribution['oldest_employee'] = [
                        'name' => $user['fname'] . ' ' . $user['lname'],
                        'age' => $age,
                        'branch' => $user['branch_name'] ?? 'Unassigned'
                    ];
                }

                // Age by branch
                if (!empty($user['branch_id'])) {
                    $ageDistribution['age_by_branch'][$user['branch_id']]['age_ranges'][$ageRange]++;
                    $ageDistribution['age_by_branch'][$user['branch_id']]['total_staff']++;
                }

                // Age by gender
                $gender = $user['gender'] ?? 'unspecified';
                if (!isset($ageDistribution['age_by_gender'][$gender][$ageRange])) {
                    $ageDistribution['age_by_gender'][$gender][$ageRange] = 0;
                }
                $ageDistribution['age_by_gender'][$gender][$ageRange]++;
            }
        }

        $ageDistribution['users_with_dob'] = $usersWithDob;
        $ageDistribution['average_age'] = $usersWithDob > 0 ? round($totalAge / $usersWithDob, 1) : 0;

        // Calculate average age by branch
        foreach ($ageDistribution['age_by_branch'] as $branchId => &$branchData) {
            if ($branchData['total_staff'] > 0) {
                $branchTotalAge = 0;
                $branchStaffCount = 0;

                foreach ($users as $user) {
                    if ($user['branch_id'] == $branchId && !empty($user['dobirth'])) {
                        $birthDate = new \DateTime($user['dobirth']);
                        $age = $currentDate->diff($birthDate)->y;
                        $branchTotalAge += $age;
                        $branchStaffCount++;
                    }
                }

                $branchData['average_age'] = $branchStaffCount > 0 ? round($branchTotalAge / $branchStaffCount, 1) : 0;
            }
        }

        return $ageDistribution;
    }

    /**
     * Get age range for classification
     *
     * @param int $age
     * @return string
     */
    private function getAgeRange($age)
    {
        if ($age >= 18 && $age <= 25) return '18-25';
        if ($age >= 26 && $age <= 35) return '26-35';
        if ($age >= 36 && $age <= 45) return '36-45';
        if ($age >= 46 && $age <= 55) return '46-55';
        if ($age >= 56 && $age <= 65) return '56-65';
        return '65+';
    }

    /**
     * Prepare gender distribution analytics
     *
     * @param array $users
     * @param array $branches
     * @return array
     */
    private function prepareGenderDistribution($users, $branches)
    {
        $genderDistribution = [
            'total_users' => count($users),
            'gender_summary' => [
                'male' => 0,
                'female' => 0,
                'unspecified' => 0
            ],
            'gender_by_branch' => [],
            'gender_by_role' => [
                'admin' => ['male' => 0, 'female' => 0, 'unspecified' => 0],
                'supervisor' => ['male' => 0, 'female' => 0, 'unspecified' => 0],
                'user' => ['male' => 0, 'female' => 0, 'unspecified' => 0],
                'guest' => ['male' => 0, 'female' => 0, 'unspecified' => 0]
            ],
            'gender_percentages' => []
        ];

        // Initialize branch gender data
        foreach ($branches as $branch) {
            $genderDistribution['gender_by_branch'][$branch['id']] = [
                'branch_name' => $branch['name'],
                'male' => 0,
                'female' => 0,
                'unspecified' => 0,
                'total' => 0
            ];
        }

        foreach ($users as $user) {
            $gender = !empty($user['gender']) ? $user['gender'] : 'unspecified';

            // Overall gender count
            $genderDistribution['gender_summary'][$gender]++;

            // Gender by branch
            if (!empty($user['branch_id'])) {
                $genderDistribution['gender_by_branch'][$user['branch_id']][$gender]++;
                $genderDistribution['gender_by_branch'][$user['branch_id']]['total']++;
            }

            // Gender by role
            $role = $user['role'] ?? 'user';
            if (!isset($genderDistribution['gender_by_role'][$role])) {
                $genderDistribution['gender_by_role'][$role] = ['male' => 0, 'female' => 0, 'unspecified' => 0];
            }
            if (!isset($genderDistribution['gender_by_role'][$role][$gender])) {
                $genderDistribution['gender_by_role'][$role][$gender] = 0;
            }
            $genderDistribution['gender_by_role'][$role][$gender]++;
        }

        // Calculate percentages
        $total = $genderDistribution['total_users'];
        if ($total > 0) {
            $genderDistribution['gender_percentages'] = [
                'male' => round(($genderDistribution['gender_summary']['male'] / $total) * 100, 1),
                'female' => round(($genderDistribution['gender_summary']['female'] / $total) * 100, 1),
                'unspecified' => round(($genderDistribution['gender_summary']['unspecified'] / $total) * 100, 1)
            ];
        }

        return $genderDistribution;
    }

    /**
     * Prepare staff strength analytics
     *
     * @param array $users
     * @param array $branches
     * @return array
     */
    private function prepareStaffStrength($users, $branches)
    {
        $staffStrength = [
            'total_staff' => count($users),
            'active_staff' => 0,
            'inactive_staff' => 0,
            'staff_by_branch' => [],
            'staff_by_role' => [
                'admin' => 0,
                'supervisor' => 0,
                'user' => 0,
                'guest' => 0
            ],
            'staff_by_designation' => [],
            'staff_by_grade' => []
        ];

        // Initialize branch data
        foreach ($branches as $branch) {
            $staffStrength['staff_by_branch'][$branch['id']] = [
                'branch_name' => $branch['name'],
                'total_staff' => 0,
                'active_staff' => 0,
                'inactive_staff' => 0,
                'roles' => [
                    'admin' => 0,
                    'supervisor' => 0,
                    'user' => 0,
                    'guest' => 0
                ]
            ];
        }

        foreach ($users as $user) {
            // Active/Inactive status
            if ($user['user_status'] == 1) {
                $staffStrength['active_staff']++;
            } else {
                $staffStrength['inactive_staff']++;
            }

            // Staff by role
            $role = $user['role'] ?? 'user';
            if (!isset($staffStrength['staff_by_role'][$role])) {
                $staffStrength['staff_by_role'][$role] = 0;
            }
            $staffStrength['staff_by_role'][$role]++;

            // Staff by designation
            $designation = $user['designation'] ?? 'Unspecified';
            if (!isset($staffStrength['staff_by_designation'][$designation])) {
                $staffStrength['staff_by_designation'][$designation] = 0;
            }
            $staffStrength['staff_by_designation'][$designation]++;

            // Staff by grade
            $grade = $user['grade'] ?? 'Unspecified';
            if (!isset($staffStrength['staff_by_grade'][$grade])) {
                $staffStrength['staff_by_grade'][$grade] = 0;
            }
            $staffStrength['staff_by_grade'][$grade]++;

            // Staff by branch
            if (!empty($user['branch_id'])) {
                $staffStrength['staff_by_branch'][$user['branch_id']]['total_staff']++;
                if (!isset($staffStrength['staff_by_branch'][$user['branch_id']]['roles'][$role])) {
                    $staffStrength['staff_by_branch'][$user['branch_id']]['roles'][$role] = 0;
                }
                $staffStrength['staff_by_branch'][$user['branch_id']]['roles'][$role]++;

                if ($user['user_status'] == 1) {
                    $staffStrength['staff_by_branch'][$user['branch_id']]['active_staff']++;
                } else {
                    $staffStrength['staff_by_branch'][$user['branch_id']]['inactive_staff']++;
                }
            }
        }

        return $staffStrength;
    }

    /**
     * Prepare workload distribution analytics
     *
     * @param array $users
     * @param array $proposals
     * @param array $workplans
     * @param array $activities
     * @return array
     */
    private function prepareWorkloadDistribution($users, $proposals, $workplans, $activities)
    {
        $workloadDistribution = [
            'total_workplans' => count($workplans),
            'total_activities' => count($activities),
            'total_proposals' => count($proposals),
            'workload_by_user' => [],
            'supervisor_workload' => [],
            'action_officer_workload' => [],
            'top_performers' => [],
            'workload_summary' => [
                'high_workload' => 0,    // >10 items
                'medium_workload' => 0,  // 5-10 items
                'low_workload' => 0,     // 1-4 items
                'no_workload' => 0       // 0 items
            ]
        ];

        // Initialize user workload data
        foreach ($users as $user) {
            $userId = $user['id'];
            $workloadDistribution['workload_by_user'][$userId] = [
                'user_name' => $user['fname'] . ' ' . $user['lname'],
                'branch_name' => $user['branch_name'] ?? 'Unassigned',
                'role' => $user['role'],
                'designation' => $user['designation'] ?? 'Unspecified',
                'supervised_workplans' => 0,
                'supervised_activities' => 0,
                'assigned_proposals' => 0,
                'completed_proposals' => 0,
                'pending_proposals' => 0,
                'total_workload' => 0,
                'workload_category' => 'no_workload'
            ];
        }

        // Count supervised workplans
        foreach ($workplans as $workplan) {
            if (!empty($workplan['supervisor_id'])) {
                $supervisorId = $workplan['supervisor_id'];
                if (isset($workloadDistribution['workload_by_user'][$supervisorId])) {
                    $workloadDistribution['workload_by_user'][$supervisorId]['supervised_workplans']++;
                }
            }
        }

        // Count supervised activities
        foreach ($activities as $activity) {
            if (!empty($activity['supervisor_id'])) {
                $supervisorId = $activity['supervisor_id'];
                if (isset($workloadDistribution['workload_by_user'][$supervisorId])) {
                    $workloadDistribution['workload_by_user'][$supervisorId]['supervised_activities']++;
                }
            }
        }

        // Count assigned proposals
        foreach ($proposals as $proposal) {
            // Supervisor workload
            if (!empty($proposal['supervisor_id'])) {
                $supervisorId = $proposal['supervisor_id'];
                if (isset($workloadDistribution['workload_by_user'][$supervisorId])) {
                    $workloadDistribution['workload_by_user'][$supervisorId]['assigned_proposals']++;

                    if ($proposal['status'] == 'approved' || $proposal['status'] == 'rated') {
                        $workloadDistribution['workload_by_user'][$supervisorId]['completed_proposals']++;
                    } else {
                        $workloadDistribution['workload_by_user'][$supervisorId]['pending_proposals']++;
                    }
                }
            }

            // Action officer workload
            if (!empty($proposal['action_officer_id'])) {
                $actionOfficerId = $proposal['action_officer_id'];
                if (isset($workloadDistribution['workload_by_user'][$actionOfficerId])) {
                    $workloadDistribution['workload_by_user'][$actionOfficerId]['assigned_proposals']++;

                    if ($proposal['status'] == 'approved' || $proposal['status'] == 'rated') {
                        $workloadDistribution['workload_by_user'][$actionOfficerId]['completed_proposals']++;
                    } else {
                        $workloadDistribution['workload_by_user'][$actionOfficerId]['pending_proposals']++;
                    }
                }
            }
        }

        // Calculate total workload and categorize
        foreach ($workloadDistribution['workload_by_user'] as $userId => &$userData) {
            $userData['total_workload'] = $userData['supervised_workplans'] +
                                        $userData['supervised_activities'] +
                                        $userData['assigned_proposals'];

            // Categorize workload
            if ($userData['total_workload'] == 0) {
                $userData['workload_category'] = 'no_workload';
                $workloadDistribution['workload_summary']['no_workload']++;
            } elseif ($userData['total_workload'] <= 4) {
                $userData['workload_category'] = 'low_workload';
                $workloadDistribution['workload_summary']['low_workload']++;
            } elseif ($userData['total_workload'] <= 10) {
                $userData['workload_category'] = 'medium_workload';
                $workloadDistribution['workload_summary']['medium_workload']++;
            } else {
                $userData['workload_category'] = 'high_workload';
                $workloadDistribution['workload_summary']['high_workload']++;
            }
        }

        // Sort by total workload to get top performers
        $sortedUsers = $workloadDistribution['workload_by_user'];
        uasort($sortedUsers, function($a, $b) {
            return $b['total_workload'] <=> $a['total_workload'];
        });

        $workloadDistribution['top_performers'] = array_slice($sortedUsers, 0, 10, true);

        return $workloadDistribution;
    }

    /**
     * Prepare financial accountability analytics
     *
     * @param array $users
     * @param array $proposals
     * @return array
     */
    private function prepareFinancialAccountability($users, $proposals)
    {
        $financialAccountability = [
            'total_budget_allocated' => 0,
            'budget_by_user' => [],
            'budget_by_branch' => [],
            'budget_by_status' => [
                'pending' => 0,
                'submitted' => 0,
                'approved' => 0,
                'rated' => 0,
                'other' => 0
            ],
            'top_budget_managers' => [],
            'budget_utilization' => [
                'high_budget' => 0,     // >1M
                'medium_budget' => 0,   // 100K-1M
                'low_budget' => 0,      // <100K
                'no_budget' => 0        // 0
            ]
        ];

        // Initialize user budget data
        foreach ($users as $user) {
            $userId = $user['id'];
            $financialAccountability['budget_by_user'][$userId] = [
                'user_name' => $user['fname'] . ' ' . $user['lname'],
                'branch_name' => $user['branch_name'] ?? 'Unassigned',
                'role' => $user['role'],
                'designation' => $user['designation'] ?? 'Unspecified',
                'total_budget' => 0,
                'approved_budget' => 0,
                'pending_budget' => 0,
                'proposal_count' => 0,
                'average_proposal_cost' => 0
            ];
        }

        // Calculate budget allocations from proposals
        foreach ($proposals as $proposal) {
            $totalCost = floatval($proposal['total_cost'] ?? 0);
            $financialAccountability['total_budget_allocated'] += $totalCost;

            // Budget by status
            $status = $proposal['status'] ?? 'other';
            if (isset($financialAccountability['budget_by_status'][$status])) {
                $financialAccountability['budget_by_status'][$status] += $totalCost;
            } else {
                $financialAccountability['budget_by_status']['other'] += $totalCost;
            }

            // Budget by supervisor
            if (!empty($proposal['supervisor_id'])) {
                $supervisorId = $proposal['supervisor_id'];
                if (isset($financialAccountability['budget_by_user'][$supervisorId])) {
                    $financialAccountability['budget_by_user'][$supervisorId]['total_budget'] += $totalCost;
                    $financialAccountability['budget_by_user'][$supervisorId]['proposal_count']++;

                    if ($proposal['status'] == 'approved' || $proposal['status'] == 'rated') {
                        $financialAccountability['budget_by_user'][$supervisorId]['approved_budget'] += $totalCost;
                    } else {
                        $financialAccountability['budget_by_user'][$supervisorId]['pending_budget'] += $totalCost;
                    }
                }
            }

            // Budget by action officer
            if (!empty($proposal['action_officer_id'])) {
                $actionOfficerId = $proposal['action_officer_id'];
                if (isset($financialAccountability['budget_by_user'][$actionOfficerId])) {
                    $financialAccountability['budget_by_user'][$actionOfficerId]['total_budget'] += $totalCost;
                    $financialAccountability['budget_by_user'][$actionOfficerId]['proposal_count']++;

                    if ($proposal['status'] == 'approved' || $proposal['status'] == 'rated') {
                        $financialAccountability['budget_by_user'][$actionOfficerId]['approved_budget'] += $totalCost;
                    } else {
                        $financialAccountability['budget_by_user'][$actionOfficerId]['pending_budget'] += $totalCost;
                    }
                }
            }
        }

        // Calculate average proposal costs and categorize budget levels
        foreach ($financialAccountability['budget_by_user'] as $userId => &$userData) {
            if ($userData['proposal_count'] > 0) {
                $userData['average_proposal_cost'] = $userData['total_budget'] / $userData['proposal_count'];
            }

            // Categorize budget levels
            if ($userData['total_budget'] == 0) {
                $financialAccountability['budget_utilization']['no_budget']++;
            } elseif ($userData['total_budget'] < 100000) {
                $financialAccountability['budget_utilization']['low_budget']++;
            } elseif ($userData['total_budget'] < 1000000) {
                $financialAccountability['budget_utilization']['medium_budget']++;
            } else {
                $financialAccountability['budget_utilization']['high_budget']++;
            }
        }

        // Sort by total budget to get top budget managers
        $sortedUsers = $financialAccountability['budget_by_user'];
        uasort($sortedUsers, function($a, $b) {
            return $b['total_budget'] <=> $a['total_budget'];
        });

        $financialAccountability['top_budget_managers'] = array_slice($sortedUsers, 0, 10, true);

        return $financialAccountability;
    }

    /**
     * Prepare performance metrics analytics
     *
     * @param array $users
     * @param array $proposals
     * @return array
     */
    private function preparePerformanceMetrics($users, $proposals)
    {
        $performanceMetrics = [
            'total_rated_proposals' => 0,
            'average_rating' => 0,
            'performance_by_user' => [],
            'top_performers' => [],
            'performance_distribution' => [
                'excellent' => 0,    // 4.5-5.0
                'good' => 0,         // 3.5-4.4
                'average' => 0,      // 2.5-3.4
                'below_average' => 0, // 1.5-2.4
                'poor' => 0          // 0-1.4
            ],
            'completion_rates' => [],
            'efficiency_metrics' => []
        ];

        // Initialize user performance data
        foreach ($users as $user) {
            $userId = $user['id'];
            $performanceMetrics['performance_by_user'][$userId] = [
                'user_name' => $user['fname'] . ' ' . $user['lname'],
                'branch_name' => $user['branch_name'] ?? 'Unassigned',
                'role' => $user['role'],
                'designation' => $user['designation'] ?? 'Unspecified',
                'total_proposals' => 0,
                'rated_proposals' => 0,
                'total_rating' => 0,
                'average_rating' => 0,
                'completed_proposals' => 0,
                'completion_rate' => 0,
                'performance_category' => 'no_data'
            ];
        }

        $totalRatings = 0;
        $ratedProposalsCount = 0;

        // Calculate performance metrics from proposals
        foreach ($proposals as $proposal) {
            $rating = floatval($proposal['rating'] ?? 0);
            $status = $proposal['status'] ?? '';

            // Only consider proposals with status 'rated' and rating > 0
            if ($rating > 0 && $status == 'rated') {
                $performanceMetrics['total_rated_proposals']++;
                $totalRatings += $rating;
                $ratedProposalsCount++;
            }

            // Performance by supervisor (only for rated proposals)
            if (!empty($proposal['supervisor_id'])) {
                $supervisorId = $proposal['supervisor_id'];
                if (isset($performanceMetrics['performance_by_user'][$supervisorId])) {
                    // Count all proposals assigned to supervisor
                    $performanceMetrics['performance_by_user'][$supervisorId]['total_proposals']++;

                    // Only count rated proposals for performance metrics
                    if ($rating > 0 && $status == 'rated') {
                        $performanceMetrics['performance_by_user'][$supervisorId]['rated_proposals']++;
                        $performanceMetrics['performance_by_user'][$supervisorId]['total_rating'] += $rating;
                    }

                    if ($proposal['status'] == 'approved' || $proposal['status'] == 'rated') {
                        $performanceMetrics['performance_by_user'][$supervisorId]['completed_proposals']++;
                    }
                }
            }

            // Performance by action officer (only for rated proposals)
            if (!empty($proposal['action_officer_id'])) {
                $actionOfficerId = $proposal['action_officer_id'];
                if (isset($performanceMetrics['performance_by_user'][$actionOfficerId])) {
                    // Count all proposals assigned to action officer
                    $performanceMetrics['performance_by_user'][$actionOfficerId]['total_proposals']++;

                    // Only count rated proposals for performance metrics
                    if ($rating > 0 && $status == 'rated') {
                        $performanceMetrics['performance_by_user'][$actionOfficerId]['rated_proposals']++;
                        $performanceMetrics['performance_by_user'][$actionOfficerId]['total_rating'] += $rating;
                    }

                    if ($proposal['status'] == 'approved' || $proposal['status'] == 'rated') {
                        $performanceMetrics['performance_by_user'][$actionOfficerId]['completed_proposals']++;
                    }
                }
            }
        }

        // Calculate averages and categorize performance
        foreach ($performanceMetrics['performance_by_user'] as $userId => &$userData) {
            // Calculate average rating
            if ($userData['rated_proposals'] > 0) {
                $userData['average_rating'] = $userData['total_rating'] / $userData['rated_proposals'];
            }

            // Calculate completion rate
            if ($userData['total_proposals'] > 0) {
                $userData['completion_rate'] = ($userData['completed_proposals'] / $userData['total_proposals']) * 100;
            }

            // Categorize performance
            if ($userData['average_rating'] == 0) {
                $userData['performance_category'] = 'no_data';
            } elseif ($userData['average_rating'] >= 4.5) {
                $userData['performance_category'] = 'excellent';
                $performanceMetrics['performance_distribution']['excellent']++;
            } elseif ($userData['average_rating'] >= 3.5) {
                $userData['performance_category'] = 'good';
                $performanceMetrics['performance_distribution']['good']++;
            } elseif ($userData['average_rating'] >= 2.5) {
                $userData['performance_category'] = 'average';
                $performanceMetrics['performance_distribution']['average']++;
            } elseif ($userData['average_rating'] >= 1.5) {
                $userData['performance_category'] = 'below_average';
                $performanceMetrics['performance_distribution']['below_average']++;
            } else {
                $userData['performance_category'] = 'poor';
                $performanceMetrics['performance_distribution']['poor']++;
            }
        }

        // Calculate overall average rating
        $performanceMetrics['average_rating'] = $ratedProposalsCount > 0 ? $totalRatings / $ratedProposalsCount : 0;

        // Sort by average rating to get top performers
        $sortedUsers = array_filter($performanceMetrics['performance_by_user'], function($user) {
            return $user['average_rating'] > 0;
        });

        uasort($sortedUsers, function($a, $b) {
            if ($a['average_rating'] == $b['average_rating']) {
                return $b['completion_rate'] <=> $a['completion_rate'];
            }
            return $b['average_rating'] <=> $a['average_rating'];
        });

        $performanceMetrics['top_performers'] = array_slice($sortedUsers, 0, 10, true);

        return $performanceMetrics;
    }

    /**
     * Prepare comprehensive chart data for all analytics
     *
     * @param array $users
     * @param array $branches
     * @param array $proposals
     * @param array $workplans
     * @param array $activities
     * @return array
     */
    private function prepareComprehensiveChartData($users, $branches, $proposals, $workplans, $activities)
    {
        $ageDistribution = $this->prepareAgeDistribution($users, $branches);
        $genderDistribution = $this->prepareGenderDistribution($users, $branches);
        $staffStrength = $this->prepareStaffStrength($users, $branches);
        $workloadDistribution = $this->prepareWorkloadDistribution($users, $proposals, $workplans, $activities);
        $financialAccountability = $this->prepareFinancialAccountability($users, $proposals);
        $performanceMetrics = $this->preparePerformanceMetrics($users, $proposals);

        return [
            // Age Distribution Charts
            'age_ranges' => $ageDistribution['age_ranges'],
            'age_by_branch' => $ageDistribution['age_by_branch'],
            'age_by_gender' => $ageDistribution['age_by_gender'],

            // Gender Distribution Charts
            'gender_summary' => $genderDistribution['gender_summary'],
            'gender_by_branch' => $genderDistribution['gender_by_branch'],
            'gender_by_role' => $genderDistribution['gender_by_role'],
            'gender_percentages' => $genderDistribution['gender_percentages'],

            // Staff Strength Charts
            'staff_by_branch' => $staffStrength['staff_by_branch'],
            'staff_by_role' => $staffStrength['staff_by_role'],
            'staff_by_designation' => $staffStrength['staff_by_designation'],
            'staff_by_grade' => $staffStrength['staff_by_grade'],
            'active_inactive' => [
                'active' => $staffStrength['active_staff'],
                'inactive' => $staffStrength['inactive_staff']
            ],

            // Workload Distribution Charts
            'workload_summary' => $workloadDistribution['workload_summary'],
            'top_performers_workload' => array_slice($workloadDistribution['top_performers'], 0, 5, true),

            // Financial Accountability Charts
            'budget_by_status' => $financialAccountability['budget_by_status'],
            'budget_utilization' => $financialAccountability['budget_utilization'],
            'top_budget_managers' => array_slice($financialAccountability['top_budget_managers'], 0, 5, true),

            // Performance Metrics Charts
            'performance_distribution' => $performanceMetrics['performance_distribution'],
            'top_performers_rating' => array_slice($performanceMetrics['top_performers'], 0, 5, true),

            // Summary Statistics
            'summary_stats' => [
                'total_staff' => count($users),
                'total_branches' => count($branches),
                'total_workplans' => count($workplans),
                'total_activities' => count($activities),
                'total_proposals' => count($proposals),
                'total_budget' => $financialAccountability['total_budget_allocated'],
                'average_age' => $ageDistribution['average_age'],
                'average_rating' => $performanceMetrics['average_rating']
            ]
        ];
    }
}
