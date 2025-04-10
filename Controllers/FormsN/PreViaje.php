<?php
require '../auth.php';
require_once '../baseController.php';  // Incluir el archivo con la base URL
// Definir la URL de la API usando BASE_URL
$apiUrl = BASE_URL . "/DeliveriesHDR";  // URL de la API para Pre Viajes
$token = $_SESSION['token'];
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'] ?? '';
    $truck_id = $_POST['truck_id'] ?? '';
    $route_id = $_POST['route_id'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';

    if (!empty($customer_id) && !empty($truck_id) && !empty($route_id) && !empty($appointment_date)) {
        $postData = json_encode([
            "customer_id" => (int)$customer_id,
            "truck_id" => (int)$truck_id,
            "route_id" => (int)$route_id,
            "appointment_date" => $appointment_date
            // status_id se asigna automáticamente en el backend
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
            $success = "Pre Viaje creado exitosamente.";
        } else {
            $error = "Error al crear el Pre Viaje. Código: $httpCode";
            if ($httpCode == 400) {
                $error .= " - Datos inválidos";
            } elseif ($httpCode == 401) {
                $error .= " - No autorizado";
            } elseif ($httpCode == 500) {
                $error .= " - Error del servidor";
            }
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

// Obtener datos para los dropdowns
$customers = [];
$trucks = [];
$routes = [];

try {
    // Obtener clientes
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, BASE_URL . "/Customer");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "ngrok-skip-browser-warning: true"
    ]);
    $response = curl_exec($ch);
    $customersData = json_decode($response, true);
    if ($customersData['success'] && isset($customersData['data'])) {
        $customers = $customersData['data'];
    }
    curl_close($ch);

    // Obtener camiones
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, BASE_URL . "/Truck");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "ngrok-skip-browser-warning: true"
    ]);
    $response = curl_exec($ch);
    $trucksData = json_decode($response, true);
    if (isset($trucksData['data'])) {
        $trucks = $trucksData['data'];
    }
    curl_close($ch);

    // Obtener rutas
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, BASE_URL . "/Routes");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "ngrok-skip-browser-warning: true"
    ]);
    $response = curl_exec($ch);
    $routesData = json_decode($response, true);
    if (isset($routesData['data'])) {
        $routes = $routesData['data'];
    }
    curl_close($ch);
} catch (Exception $e) {
    $error = "Error al obtener datos para los dropdowns: " . $e->getMessage();
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
   LYL - Nuevo Pre Viaje
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
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h6>Crear Nuevo Pre Viaje</h6>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <a href="https://qa.lylautotransportes.com.mx/pages/pre-viajes.php" class="btn btn-secondary">Regresar a Pre Viajes</a>
                            <?php else: ?>
                                <form method="POST" onsubmit="disableSubmitButton()">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Cliente</label>
                                            <select name="customer_id" class="form-select" required>
                                                <option value="">Seleccionar Cliente</option>
                                                <?php foreach ($customers as $customer): ?>
                                                    <option value="<?php echo htmlspecialchars($customer['id']); ?>">
                                                        <?php echo htmlspecialchars($customer['name'] . ' (' . $customer['rfc'] . ')'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Camión</label>
                                            <select name="truck_id" class="form-select" required>
                                                <option value="">Seleccionar Camión</option>
                                                <?php foreach ($trucks as $truck): ?>
                                                    <option value="<?php echo htmlspecialchars($truck['id']); ?>">
                                                        <?php echo htmlspecialchars($truck['name'] . ' - ' . $truck['plate']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ruta</label>
                                            <select name="route_id" class="form-select" required>
                                                <option value="">Seleccionar Ruta</option>
                                                <?php foreach ($routes as $route): ?>
                                                    <option value="<?php echo htmlspecialchars($route['id']); ?>">
                                                        <?php echo htmlspecialchars($route['origin'] . ' a ' . $route['destination']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Fecha y Hora de Cita</label>
                                            <input type="datetime-local" name="appointment_date" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" id="submitBtn" class="btn btn-primary">Crear Pre Viaje</button>
                                    <a href="https://qa.lylautotransportes.com.mx/pages/pre-viajes.php" class="btn btn-secondary">Cancelar</a>
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
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
        }
        
        // Formatear la fecha actual para el campo datetime-local
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            // Ajustar para el desfase de zona horaria
            const timezoneOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now - timezoneOffset)).toISOString().slice(0, 16);
            document.querySelector('input[type="datetime-local"]').value = localISOTime;
        });
    </script>
</body>
</html>