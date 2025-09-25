<?php

/**
 * Navigation Helper
 * 
 * Helper functions for role-based navigation and menu visibility
 */

if (!function_exists('canAccessMenu')) {
    /**
     * Check if user can access a specific menu based on their role
     * 
     * @param string $menuItem The menu item to check
     * @param string|null $userRole The user's role (if null, gets from session)
     * @return bool
     */
    function canAccessMenu($menuItem, $userRole = null)
    {
        if ($userRole === null) {
            $userRole = session()->get('role');
        }

        // Special check for evaluation menu - accessible to admin capability OR is_evaluator
        if ($menuItem === 'evaluation') {
            return session()->get('is_admin') == 1 || session()->get('is_evaluator') == 1;
        }

        // Pure capability-based access control
        $isAdmin = session()->get('is_admin') == 1;
        $isSupervisor = session()->get('is_supervisor') == 1;
        $isEvaluator = session()->get('is_evaluator') == 1;

        // Define menu access by capabilities
        $menuCapabilities = [
            'dashboard' => [], // Available to all
            'profile' => [], // Available to all
            'admin_panel' => ['admin'],
            'smes' => ['admin'],
            'commodities' => ['admin'],
            'commodity_boards' => ['admin'], // Only admin can manage commodity boards
            'workplans' => ['admin', 'supervisor'],

            'activities' => ['admin', 'supervisor'], // Regular users access through direct assignment
            'workplan_period' => ['admin', 'supervisor', 'user'], // Allow ordinary users access
            'my_activities' => ['admin', 'supervisor', 'user'], // Allow ordinary users access to their activities
            'reports' => ['admin', 'supervisor', 'user'], // Allow ordinary users access to reports
            'duty_instructions' => ['admin', 'supervisor'],
            'evaluation' => ['admin', 'evaluator']
        ];

        // Check if menu requires specific capabilities
        $requiredCapabilities = $menuCapabilities[$menuItem] ?? [];

        // If no capabilities required, allow access
        if (empty($requiredCapabilities)) {
            return true;
        }

        // Check if user has any of the required capabilities
        foreach ($requiredCapabilities as $capability) {
            if ($capability === 'admin' && $isAdmin) return true;
            if ($capability === 'supervisor' && $isSupervisor) return true;
            if ($capability === 'evaluator' && $isEvaluator) return true;
        }

        return false;
    }
}

if (!function_exists('canAccessAdminPanel')) {
    /**
     * Check if user can access admin panel
     * 
     * @param string|null $userRole The user's role (if null, gets from session)
     * @return bool
     */
    function canAccessAdminPanel($userRole = null)
    {
        // Check admin capability instead of role
        return session()->get('is_admin') == 1;
    }
}

if (!function_exists('canAccessCommodities')) {
    /**
     * Check if user can access commodities section
     * 
     * @param string|null $userRole The user's role (if null, gets from session)
     * @return bool
     */
    function canAccessCommodities($userRole = null)
    {
        // Only admin capability can access commodities
        return session()->get('is_admin') == 1;
    }
}

if (!function_exists('getNavigationMenus')) {
    /**
     * Get navigation menus based on user role
     * 
     * @param string|null $userRole The user's role (if null, gets from session)
     * @return array
     */
    function getNavigationMenus($userRole = null)
    {
        if ($userRole === null) {
            $userRole = session()->get('role');
        }

        // Get user capabilities
        $isAdmin = session()->get('is_admin') == 1;
        $isSupervisor = session()->get('is_supervisor') == 1;
        $isEvaluator = session()->get('is_evaluator') == 1;

        $allMenus = [
            'dashboard' => [
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'url' => 'dashboard',
                'roles' => ['user', 'guest', 'commodity'], // Basic roles
                'capabilities' => [] // Available to all
            ],
            'admin_panel' => [
                'title' => 'Admin Panel',
                'icon' => 'fas fa-cog',
                'url' => '#adminSubmenu',
                'capabilities' => ['admin'],
                'submenu' => true,
                'submenus' => [
                    'users' => ['title' => 'Users', 'icon' => 'fas fa-users', 'url' => 'admin/users'],
                    'regions' => ['title' => 'Regions', 'icon' => 'fas fa-map-marker-alt', 'url' => 'admin/regions'],
                    'gov_structure' => ['title' => 'Gov. Structure', 'icon' => 'fas fa-sitemap', 'url' => 'admin/gov-structure'],
                    'branches' => ['title' => 'Branches', 'icon' => 'fas fa-building', 'url' => 'admin/branches'],
                    'mtdp_plans' => ['title' => 'MTDP Plans', 'icon' => 'fas fa-project-diagram', 'url' => 'admin/mtdp-plans'],
                    'nasp_plans' => ['title' => 'NASP Plans', 'icon' => 'fas fa-chart-line', 'url' => 'admin/nasp-plans'],
                    'corporate_plans' => ['title' => 'Corporate Plans', 'icon' => 'fas fa-briefcase', 'url' => 'admin/corporate-plans'],
                    'org_settings' => ['title' => 'Org.Settings', 'icon' => 'fas fa-cogs', 'url' => 'admin/org-settings'],
                    'commodities' => ['title' => 'Commodities', 'icon' => 'fas fa-seedling', 'url' => 'admin/commodities']
                ]
            ],
            'smes' => [
                'title' => 'SMEs',
                'icon' => 'fas fa-store',
                'url' => 'smes',
                'capabilities' => ['admin']
            ],
            'commodity_boards' => [
                'title' => 'Commodity Boards',
                'icon' => 'fas fa-boxes',
                'url' => 'commodity-boards',
                'capabilities' => ['admin']
            ],
            'workplans' => [
                'title' => 'Workplans',
                'icon' => 'fas fa-tasks',
                'url' => 'workplans',
                'capabilities' => ['admin', 'supervisor']
            ],
            'workplan_period' => [
                'title' => 'Workplan Period',
                'icon' => 'fas fa-calendar-alt',
                'url' => 'workplan-period',
                'capabilities' => ['admin', 'supervisor', 'user']
            ],

            'evaluation' => [
                'title' => 'Evaluation',
                'icon' => 'fas fa-clipboard-check',
                'url' => 'evaluation',
                'capabilities' => ['admin', 'evaluator']
            ],
            'duty_instructions' => [
                'title' => 'Duty Instructions',
                'icon' => 'fas fa-tasks',
                'url' => 'duty-instructions',
                'capabilities' => ['admin', 'supervisor']
            ],
            'my_activities' => [
                'title' => 'My Activities',
                'icon' => 'fas fa-clipboard-list',
                'url' => 'activities',
                'capabilities' => ['admin', 'supervisor', 'user']
            ],
            'activities' => [
                'title' => 'Activities',
                'icon' => 'fas fa-folder-open',
                'url' => '#activitiesSubmenu',
                'capabilities' => ['admin', 'supervisor'],
                'submenu' => true,
                'submenus' => [
                    'documents' => ['title' => 'Documents', 'icon' => 'fas fa-file-alt', 'url' => 'documents'],
                    'meetings' => ['title' => 'Meetings', 'icon' => 'fas fa-calendar-alt', 'url' => 'meetings'],
                    'agreements' => ['title' => 'Agreements', 'icon' => 'fas fa-handshake', 'url' => 'agreements']
                ]
            ],
            'reports' => [
                'title' => 'Reports',
                'icon' => 'fas fa-chart-bar',
                'url' => '#reportsSubmenu',
                'capabilities' => ['admin', 'supervisor', 'user'],
                'submenu' => true,
                'submenus' => [
                    'mtdp_report' => ['title' => 'MTDP Report', 'icon' => 'fas fa-file-alt', 'url' => 'reports/mtdp'],
                    'nasp_report' => ['title' => 'NASP Report', 'icon' => 'fas fa-file-alt', 'url' => 'reports/nasp'],
                    'workplan_report' => ['title' => 'Workplan Report', 'icon' => 'fas fa-file-alt', 'url' => 'reports/workplan'],
                    'activities_map' => ['title' => 'Activities Map', 'icon' => 'fas fa-map-marked-alt', 'url' => 'reports/activities-map'],
                    'commodity_reports' => ['title' => 'Commodity Reports', 'icon' => 'fas fa-seedling', 'url' => 'reports/commodity'],
                    'hr_reports' => ['title' => 'HR Reports', 'icon' => 'fas fa-users', 'url' => 'reports/hr']
                ]
            ],
            'profile' => [
                'title' => 'Profile',
                'icon' => 'fas fa-user',
                'url' => 'dashboard/profile'
                // No capabilities required - available to all
            ]
        ];

        // Filter menus based purely on capabilities
        $allowedMenus = [];
        foreach ($allMenus as $key => $menu) {
            $hasAccess = false;

            // Check if menu requires capabilities
            if (isset($menu['capabilities'])) {
                foreach ($menu['capabilities'] as $capability) {
                    if ($capability === 'admin' && $isAdmin) {
                        $hasAccess = true;
                        break;
                    } elseif ($capability === 'supervisor' && $isSupervisor) {
                        $hasAccess = true;
                        break;
                    } elseif ($capability === 'evaluator' && $isEvaluator) {
                        $hasAccess = true;
                        break;
                    }
                }
            } else {
                // Menu available to all (no capabilities required)
                $hasAccess = true;
            }

            if ($hasAccess) {
                $allowedMenus[$key] = $menu;
            }
        }

        return $allowedMenus;
    }
}

if (!function_exists('shouldShowActivitiesSubmenu')) {
    /**
     * Check if activities submenu should be shown based on user role
     * 
     * @param string|null $userRole The user's role (if null, gets from session)
     * @return bool
     */
    function shouldShowActivitiesSubmenu($userRole = null)
    {
        // Pure capability-based check
        $isAdmin = session()->get('is_admin') == 1;
        $isSupervisor = session()->get('is_supervisor') == 1;

        return $isAdmin || $isSupervisor;
    }
}

if (!function_exists('shouldShowReportsSubmenu')) {
    /**
     * Check if reports submenu should be shown based on user role
     * 
     * @param string|null $userRole The user's role (if null, gets from session)
     * @return bool
     */
    function shouldShowReportsSubmenu($userRole = null)
    {
        // Pure capability-based check
        $isAdmin = session()->get('is_admin') == 1;
        $isSupervisor = session()->get('is_supervisor') == 1;

        return $isAdmin || $isSupervisor;
    }
}
