<?php
// get_reservation_stats.php
// Returns JSON { reserved: N, total: 50, available: M }
// Accepts GET params: date, time

header('Content-Type: application/json; charset=utf-8');
include 'db_connect.php';

$date = $_GET['date'] ?? date('Y-m-d');
$time = $_GET['time'] ?? date('H:i');

// sanitize minimal
$date = preg_replace('/[^0-9\-]/','', $date);
$time = preg_replace('/[^0-9:]/','', $time);

$total = 50;

$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM reservations WHERE reservation_date = ? AND reservation_time = ? AND status != 'cancelled'");
$stmt->bind_param('ss', $date, $time);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$reserved = intval($row['cnt'] ?? 0);
$available = max(0, $total - $reserved);

echo json_encode(['reserved'=>$reserved, 'total'=>$total, 'available'=>$available]);
