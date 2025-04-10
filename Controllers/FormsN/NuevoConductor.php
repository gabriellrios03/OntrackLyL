<?php
require '../auth.php';
require '../baseController.php';  // Incluir el archivo con la base URL
$apiUrl = BASE_URL . "/Drivers";  // Nueva ruta para crear drivers
$token = $_SESSION['token'];
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $rfc = $_POST['rfc'] ?? '';

    if (!empty($name) && !empty($lastname) && !empty($rfc)) {
        $postData = json_encode([
            "name" => $name,
            "lastname" => $lastname,
            "status_Id" => 6,  // Fijo a 6
            "rfc" => $rfc
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/json",
            "ngrok-skip-browser-warning: true"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 201) {
            $success = "Conductor creado exitosamente.";
        } else {
            $error = "Error al crear el conductor.";
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
  <title>LYL - Nuevo Conductor</title>
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:300,400,500,600,700,900" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />
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
                            <h6>Crear Nuevo Conductor</h6>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <a href="http://localhost:5500/pages/drivers.php" class="btn btn-secondary">Regresar a Conductores</a>
                            <?php else: ?>
                                <form method="POST" onsubmit="disableSubmitButton()">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Apellido</label>
                                        <input type="text" name="lastname" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">RFC</label>
                                        <input type="text" name="rfc" class="form-control" required>
                                    </div>
                                    <button type="submit" id="submitBtn" class="btn btn-primary">Crear Conductor</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function disableSubmitButton() {
            document.getElementById('submitBtn').disabled = true;
        }
    </script>
</body>
</html>
