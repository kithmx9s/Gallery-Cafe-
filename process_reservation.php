<?php
// process_reservation.php
// Accepts POST, assigns next available table for the requested date/time and inserts reservation.
// Returns JSON { success: bool, ref_display: string, error: string }

header('Content-Type: application/json; charset=utf-8');
include 'db_connect.php';

function jsonError($msg){ echo json_encode(['success'=>false,'error'=>$msg]); exit; }

if($_SERVER['REQUEST_METHOD'] !== 'POST') jsonError('Invalid request');

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$date = trim($_POST['date'] ?? '');
$time = trim($_POST['time'] ?? '');
$guests = intval($_POST['guests'] ?? 1);
$meal_type = $_POST['meal_type'] ?? null;
$country = $_POST['country'] ?? null;

if(!$name || !$phone || !$date || !$time || !$guests) jsonError('Missing required fields');

// sanitize basic
$name = htmlspecialchars($name, ENT_QUOTES);
$phone = htmlspecialchars($phone, ENT_QUOTES);
$email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;

// check that date/time is valid (not in the past)
$now = new DateTime();
$r_dt = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
if(!$r_dt) jsonError('Invalid date/time');
if($r_dt < $now) jsonError('Reservation must be for a future date/time');

// total tables
$totalTables = 50;

// find reserved table numbers for that date & time (exact time slot)
// Here we treat reservations with same date & time as conflict. You can refine logic for time windows.
$stmt = $conn->prepare("SELECT table_number FROM reservations WHERE reservation_date = ? AND reservation_time = ? AND status != 'cancelled'");
$stmt->bind_param('ss', $date, $time);
$stmt->execute();
$res = $stmt->get_result();

$taken = [];
while($row = $res->fetch_assoc()) $taken[] = intval($row['table_number']);
$stmt->close();

// find first available table 1..50
$available_table = null;
for($i=1;$i<=$totalTables;$i++){
    if(!in_array($i, $taken)){
        $available_table = $i;
        break;
    }
}

if($available_table === null){
    jsonError('No tables available at chosen date/time');
}

// build ref number: YYYYMMDDTBxx (xx = zero-padded table number)
$ref_prefix = date('Ymd', strtotime($date));
$ref_no = $ref_prefix . 'TB' . str_pad($available_table, 2, '0', STR_PAD_LEFT);
$ref_display = $ref_prefix . str_pad($available_table, 2, '0', STR_PAD_LEFT); // also provide without TB as you mentioned

// insert reservation
$ins = $conn->prepare("INSERT INTO reservations
  (ref_no, customer_name, phone, email, reservation_date, reservation_time, guests, meal_type, country, table_number, status)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')");
$ins->bind_param('sssssisisi', $ref_no, $name, $phone, $email, $date, $time, $guests, $meal_type, $country, $available_table);

if($ins->execute()){
    echo json_encode(['success'=>true, 'ref'=> $ref_no, 'ref_display'=>$ref_display, 'table_number'=>$available_table]);
    exit;
} else {
    jsonError('Database error: could not save reservation');
}
