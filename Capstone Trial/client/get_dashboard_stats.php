<?php
session_start();
require_once('../includes/db_connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'CLIENT') {
    exit(json_encode(['error' => 'Unauthorized']));
}

$user_id = $_SESSION['user_id'];
$stats = [];

// Get project statistics
$query = "SELECT 
    COUNT(*) as total_projects,
    SUM(CASE WHEN project_status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN project_status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
    SUM(CASE WHEN project_status = 'Completed' THEN 1 ELSE 0 END) as completed
    FROM projects 
    WHERE client_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();
$stmt->close();

// Get recent activities
$query = "SELECT p.*, u.full_name as assigned_to
          FROM projects p
          LEFT JOIN users u ON p.assigned_user_id = u.id
          WHERE p.client_id = ?
          ORDER BY p.updated_at DESC
          LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stats['recent_activities'] = [];
while ($row = $result->fetch_assoc()) {
    $stats['recent_activities'][] = $row;
}
$stmt->close();

echo json_encode($stats);
?>