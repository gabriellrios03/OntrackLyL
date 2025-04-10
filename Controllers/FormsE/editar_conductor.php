<?php
require '../auth.php';
require '../baseController.php';

$apiUrl = BASE_URL . "/Drivers";
$token = $_SESSION['token'];
$driverId = $_GET['id'] ?? '';
$error = "";
$success = "";

if (!$driverId) {
    die("No se especificó un ID de conductor.");
}

// Obtener los datos del conductor
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$apiUrl/$driverId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "ngrok-skip-browser-warning: true"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!$data || !$data['success']) {
    die("Error al obtener los datos del conductor.");
}

$driver = $data['data'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $rfc = $_POST['rfc'] ?? '';
    $status_Id = $_POST['status_Id'] == '6' ? 6 : 7;

    if (!empty($name) && !empty($lastname) && !empty($rfc)) {
        $putData = json_encode([
            "name" => $name,
            "lastname" => $lastname,
            "rfc" => $rfc,
            "status_Id" => $status_Id
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$apiUrl/$driverId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/json",
            "ngrok-skip-browser-warning: true"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $putData);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $success = "Conductor actualizado exitosamente.";
        } else {
            $error = "Error al actualizar el conductor.";
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  </title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>
<body class="bg-gray-100">
    <?php include_once ('../../Components/SideBar.php') ?>

    <main class="main-content">
        <div class="container py-4">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h6>Editar Conductor</h6>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <a href="https://qa.lylautotransportes.com.mx/pages/drivers.php" class="btn btn-secondary">Regresar a Conductores</a>
                            <?php else: ?>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($driver['name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Apellido</label>
                                        <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($driver['lastname']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">RFC</label>
                                        <input type="text" name="rfc" class="form-control" value="<?php echo htmlspecialchars($driver['rfc']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Estado</label>
                                        <select name="status_Id" class="form-select" required>
                                            <option value="6" <?php echo $driver['status_Id'] == 6 ? 'selected' : ''; ?>>Activo</option>
                                            <option value="7" <?php echo $driver['status_Id'] == 7 ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Actualizar Conductor</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
