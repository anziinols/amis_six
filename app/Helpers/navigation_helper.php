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

        // Define menu permissions for each role
        $menuPermissions = [
            'admin' => [
                'dashboard',
                'admin_panel',
                'smes',
                'commodity_boards',
                'workplans',
                'proposals',
                'activities',
                'reports',
                'profile',
                'commodities' // Admin has access to commodities
            ],
            'supervisor' => [
                'dashboard',
                'workplans',
                'proposals',
                'activities',
                'reports',
                'profile'
            ],
            'user' => [
                'dashboard',
                'activities',
                'reports',
                'profile'
            ],
            'commodity' => [
                'dashboard',
                'commodity_boards',
                'reports',
                'profile'
            ]
        ];

        // Get allowed menus for the user's role
        $allowedMenus = $menuPermissions[$userRole] ?? [];

        return in_array($menuItem, $allowedMenus);
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
        if ($userRole === null) {
            $userRole = session()->get('role');
        }

        return $userRole === 'admin';
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
        if ($userRole === null) {
            $userRole = session()->get('role');
        }

        return in_array($userRole, ['admin', 'commodity']);
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

        $allMenus = [
            'dashboard' => [
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'url' => 'dashboard',
                'roles' => ['admin', 'supervisor', 'user', 'commodity']
            ],
            'admin_panel' => [
                'title' => 'Admin Panel',
                'icon' => 'fas fa-cog',
                'url' => '#adminSubmenu',
                'roles' => ['admin'],
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
                'roles' => ['admin']
            ],
            'commodity_boards' => [
                'title' => 'Commodity Boards',
                'icon' => 'fas fa-boxes',
                'url' => 'commodity-boards',
                'roles' => ['admin', 'commodity']
            ],
            'workplans' => [
                'title' => 'Workplans',
                'icon' => 'fas fa-tasks',
                'url' => 'workplans',
                'roles' => ['admin', 'supervisor']
            ],
            'proposals' => [
                'title' => 'Proposal',
                'icon' => 'fas fa-lightbulb',
                'url' => 'proposals',
                'roles' => ['admin', 'supervisor']
            ],
            'activities' => [
                'title' => 'Activities',
                'icon' => 'fas fa-clipboard-list',
                'url' => '#activitiesSubmenu',
                'roles' => ['admin', 'supervisor', 'user'],
                'submenu' => true,
                'submenus' => [
                    'my_activities' => ['title' => 'My Activities', 'icon' => 'fas fa-tasks', 'url' => 'activities'],
                    'documents' => ['title' => 'Documents', 'icon' => 'fas fa-file-alt', 'url' => 'documents'],
                    'meetings' => ['title' => 'Meetings', 'icon' => 'fas fa-calendar-alt', 'url' => 'meetings'],
                    'agreements' => ['title' => 'Agreements', 'icon' => 'fas fa-handshake', 'url' => 'agreements']
                ]
            ],
            'reports' => [
                'title' => 'Reports',
                'icon' => 'fas fa-chart-bar',
                'url' => '#reportsSubmenu',
                'roles' => ['admin', 'supervisor', 'user', 'commodity'],
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
                'url' => 'dashboard/profile',
                'roles' => ['admin', 'supervisor', 'user', 'commodity']
            ]
        ];

        // Filter menus based on user role
        $allowedMenus = [];
        foreach ($allMenus as $key => $menu) {
            if (in_array($userRole, $menu['roles'])) {
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
        if ($userRole === null) {
            $userRole = session()->get('role');
        }

        return in_array($userRole, ['admin', 'supervisor', 'user']);
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
        if ($userRole === null) {
            $userRole = session()->get('role');
        }

        return in_array($userRole, ['admin', 'supervisor', 'user', 'commodity']);
    }
}
