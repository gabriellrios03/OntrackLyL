<?php
require '../auth.php';
require_once '../baseController.php';  // Incluir el archivo con la base URL
// Definir la URL de la API usando BASE_URL
$apiUrl = BASE_URL . "/Truck";  // Aquí ya estamos usando la URL base
$token = $_SESSION['token'];
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $plate = $_POST['plate'] ?? '';

    if (!empty($name) && !empty($plate)) {
        $postData = json_encode(["name" => $name, "plate" => $plate]);

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
            $success = "Camión creado exitosamente.";
        } else {
            $error = "Error al crear el camión.";
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   LYL - Trucks
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
                            <h6>Crear Nuevo Camión</h6>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <a href="https://qa.lylautotransportes.com.mx/pages/trucks.php" class="btn btn-secondary">Regresar a Camiones</a>
                            <?php else: ?>
                                <form method="POST" onsubmit="disableSubmitButton()">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Placas</label>
                                        <input type="text" name="plate" class="form-control" required>
                                    </div>
                                    <button type="submit" id="submitBtn" class="btn btn-primary">Crear Camión</button>
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
