<?php
session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['token'])) {
    $_SESSION['token'] = $data['token'];
    echo json_encode(["success" => true, "message" => "Session started"]);
} else {
    echo json_encode(["success" => false, "message" => "Token missing"]);
}
?>
