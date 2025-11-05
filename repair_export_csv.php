<?php
include '../includes/connection.php';

// Same filters as repair_tracking
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$filterSQL = "WHERE 1=1 ";
if ($search) $filterSQL .= "AND (customer_name LIKE '%$search%' OR device LIKE '%$search%') ";
if ($status) $filterSQL .= "AND status = '$status' ";
if ($from && $to) $filterSQL .= "AND DATE(created_at) BETWEEN '$from' AND '$to' ";

$query = mysqli_query($db, "SELECT * FROM repair_jobs $filterSQL ORDER BY created_at DESC");

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="repair_jobs.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['Customer Name', 'Phone', 'Device', 'Issue', 'Status', 'Date']);

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [$row['customer_name'], $row['customer_phone'], $row['device'], $row['issue'], $row['status'], $row['created_at']]);
}
fclose($output);
exit;
