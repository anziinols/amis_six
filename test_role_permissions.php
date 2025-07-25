<?php
/**
 * Simple test script to verify role-based permissions
 * Run this from the command line or browser to test the navigation helper functions
 */

// Include CodeIgniter bootstrap
require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

// Load the navigation helper
helper('navigation');

echo "<h1>Role-Based Permission Test</h1>\n";

// Test different roles
$roles = ['admin', 'supervisor', 'user', 'commodity'];

foreach ($roles as $role) {
    echo "<h2>Testing Role: " . ucfirst($role) . "</h2>\n";
    
    // Test menu access
    $menus = ['dashboard', 'admin_panel', 'smes', 'commodity_boards', 'workplans', 'proposals', 'activities', 'reports', 'profile'];
    
    echo "<h3>Menu Access:</h3>\n";
    echo "<ul>\n";
    foreach ($menus as $menu) {
        $canAccess = canAccessMenu($menu, $role);
        $status = $canAccess ? '✓ ALLOWED' : '✗ DENIED';
        echo "<li>{$menu}: {$status}</li>\n";
    }
    echo "</ul>\n";
    
    // Test specific functions
    echo "<h3>Specific Functions:</h3>\n";
    echo "<ul>\n";
    echo "<li>Can Access Admin Panel: " . (canAccessAdminPanel($role) ? '✓ YES' : '✗ NO') . "</li>\n";
    echo "<li>Can Access Commodities: " . (canAccessCommodities($role) ? '✓ YES' : '✗ NO') . "</li>\n";
    echo "<li>Should Show Activities Submenu: " . (shouldShowActivitiesSubmenu($role) ? '✓ YES' : '✗ NO') . "</li>\n";
    echo "<li>Should Show Reports Submenu: " . (shouldShowReportsSubmenu($role) ? '✓ YES' : '✗ NO') . "</li>\n";
    echo "</ul>\n";
    
    echo "<hr>\n";
}

echo "<h2>Expected Results Summary:</h2>\n";
echo "<ul>\n";
echo "<li><strong>Admin:</strong> Should have access to ALL menus including admin_panel, smes, and commodities</li>\n";
echo "<li><strong>Supervisor:</strong> Should have access to dashboard, workplans, proposals, activities, reports, profile</li>\n";
echo "<li><strong>User:</strong> Should have access to dashboard, activities, reports, profile</li>\n";
echo "<li><strong>Commodity:</strong> Should have access to dashboard, commodity_boards, reports, profile</li>\n";
echo "</ul>\n";

echo "<p><strong>Test completed!</strong> Review the results above to ensure they match the expected behavior.</p>\n";
?>
