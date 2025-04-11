<?php
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: https://qa.lylautotransportes.com.mx/Index.php");
    exit();
}

// Validar token con la API
$token = $_SESSION['token'];
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://manually-massive-flamingo.ngrok-free.app/api/User/validate-token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'accept: */*',
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Si la respuesta es 401, destruir sesión y redirigir
if ($httpCode === 401) {
    session_destroy();
    header("Location: https://qa.lylautotransportes.com.mx/Index.php");
    exit();
}
?>
