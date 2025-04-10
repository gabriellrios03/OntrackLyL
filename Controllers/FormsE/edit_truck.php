<?php
require '../auth.php';
require_once '../baseController.php';  // Incluir el archivo con la base URL
// Definir la URL de la API usando BASE_URL
$apiUrl = BASE_URL . "/Truck";  // Aquí ya estamos usando la URL base
$token = $_SESSION['token'];
$truckId = $_GET['id'] ?? '';  // Obtener el ID del camión desde la URL
$error = "";
$success = "";

if (!$truckId) {
    die("No se especificó un ID de camión.");
}

// Obtener los datos del camión
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$apiUrl/$truckId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "ngrok-skip-browser-warning: true"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Verificar si la solicitud fue exitosa
if (!$data || !$data['success']) {
    die("Error al obtener los datos del camión.");
}

$truck = $data['data'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $plate = $_POST['plate'] ?? '';

    if (!empty($name) && !empty($plate)) {
        $putData = json_encode(["name" => $name, "plate" => $plate]);

        // Actualizar los datos del camión
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$apiUrl/$truckId");
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
            $success = "Camión actualizado exitosamente.";
        } else {
            $error = "Error al actualizar el camión.";
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
   LYL - Editar Camión
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
                            <h6>Editar Camión</h6>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <a href="https://qa.lylautotransportes.com.mx/pages/trucks.php" class="btn btn-secondary">Regresar a Camiones</a>
                            <?php else: ?>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($truck['name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Placas</label>
                                        <input type="text" name="plate" class="form-control" value="<?php echo htmlspecialchars($truck['plate']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Actualizar Camión</button>
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
