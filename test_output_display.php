<?php
// Simple test script to verify output activity type display
// This simulates what the workplan activities index page does

// Database connection (adjust as needed)
$host = 'localhost';
$dbname = 'amis_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query to get activities for workplan 1 (same as the controller)
    $stmt = $pdo->prepare("
        SELECT 
            wa.id,
            wa.title,
            wa.activity_type,
            CONCAT(COALESCE(u.fname, ''), ' ', COALESCE(u.lname, '')) as supervisor_name
        FROM workplan_activities wa
        LEFT JOIN users u ON u.id = wa.supervisor_id
        WHERE wa.workplan_id = 1 
        AND wa.deleted_at IS NULL
        ORDER BY wa.id
    ");
    
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Workplan Activities Test</h2>\n";
    echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
    echo "<tr><th>#</th><th>Title</th><th>Type</th><th>Supervisor</th></tr>\n";
    
    foreach ($activities as $index => $activity) {
        $displayType = ucfirst($activity['activity_type']);
        echo "<tr>";
        echo "<td>" . ($index + 1) . "</td>";
        echo "<td>" . htmlspecialchars($activity['title']) . "</td>";
        echo "<td><strong>" . htmlspecialchars($displayType) . "</strong></td>";
        echo "<td>" . htmlspecialchars($activity['supervisor_name'] ?: 'N/A') . "</td>";
        echo "</tr>\n";
    }
    
    echo "</table>\n";
    
    // Count by type
    $typeCounts = [];
    foreach ($activities as $activity) {
        $type = $activity['activity_type'];
        $typeCounts[$type] = ($typeCounts[$type] ?? 0) + 1;
    }
    
    echo "<h3>Activity Type Summary:</h3>\n";
    echo "<ul>\n";
    foreach ($typeCounts as $type => $count) {
        echo "<li>" . ucfirst($type) . ": $count activities</li>\n";
    }
    echo "</ul>\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
