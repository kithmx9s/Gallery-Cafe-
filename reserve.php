<?php
include "db_connect.php";

if (isset($_GET['count'])) {
    $result = $conn->query("SELECT COUNT(*) as total FROM reservations");
    $row = $result->fetch_assoc();
    echo json_encode(["reserved" => $row['total']]);
    exit;
}

$count = $conn->query("SELECT COUNT(*) as total FROM reservations")->fetch_assoc()['total'];
if ($count >= 25) {
    die("No tables available");
}

$stmt = $conn->prepare(
"INSERT INTO reservations (full_name,email,phone,meal,menu,reserve_date,reserve_time)
 VALUES (?,?,?,?,?,?,?)"
);

$stmt->bind_param(
"sssssss",
$_POST['full_name'],
$_POST['email'],
$_POST['phone'],
$_POST['meal'],
$_POST['menu'],
$_POST['reserve_date'],
$_POST['reserve_time']
);

$stmt->execute();
header("Location: reservation.html");
?>
