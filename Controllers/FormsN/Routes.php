<?php
require '../auth.php';
require '../baseController.php';  // Include the file with the base URL

// Define API URLs using BASE_URL
$routesApiUrl = BASE_URL . "/routes";
$customersApiUrl = BASE_URL . "/Customer";
$token = $_SESSION['token'];
$error = "";
$success = "";

// Fetch customers for the dropdown
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $customersApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "ngrok-skip-browser-warning: true"
]);
$customersResponse = curl_exec($ch);
curl_close($ch);

$customersData = json_decode($customersResponse, true);
$customers = $customersData['success'] ? $customersData['data'] : [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_Id = $_POST['customer_Id'] ?? '';
    $origin = $_POST['origin'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $distance_Km = $_POST['distance_Km'] ?? 0;
    $avg_fuel_consumption = $_POST['avg_fuel_consumption'] ?? 0;
    $customer_price = $_POST['customer_price'] ?? 0;
    $driver_profit = $_POST['driver_profit'] ?? 0;

    if (!empty($customer_Id) && !empty($origin) && !empty($destination)) {
        $postData = json_encode([
            "customer_Id" => (int)$customer_Id,
            "origin" => $origin,
            "destination" => $destination,
            "distance_Km" => (float)$distance_Km,
            "avg_fuel_consumption" => (float)$avg_fuel_consumption,
            "customer_price" => (float)$customer_price,
            "driver_profit" => (float)$driver_profit
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $routesApiUrl);
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
            $success = "Ruta creada exitosamente.";
        } else {
            $error = "Error al crear la ruta. Código HTTP: $httpCode";
            if ($httpCode == 400) {
                $error .= " - Datos inválidos";
            }
        }
    } else {
        $error = "Los campos de cliente, origen y destino son obligatorios.";
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
   LYL - Nueva Ruta
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
                            <h6>Crear Nueva Ruta</h6>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <a href="https://qa.lylautotransportes.com.mx/pages/rutas.php" class="btn btn-secondary">Regresar a Rutas</a>
                            <?php else: ?>
                                <form method="POST" onsubmit="disableSubmitButton()">
                                    <div class="mb-3">
                                        <label class="form-label">Cliente</label>
                                        <select name="customer_Id" class="form-control" required>
                                            <option value="">Seleccionar cliente</option>
                                            <?php foreach ($customers as $customer): ?>
                                                <option value="<?php echo $customer['id']; ?>">
                                                    <?php echo htmlspecialchars($customer['name'] . ' (' . $customer['rfc'] . ')'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Origen</label>
                                        <input type="text" name="origin" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Destino</label>
                                        <input type="text" name="destination" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Distancia (km)</label>
                                        <input type="number" step="0.01" name="distance_Km" class="form-control" value="0">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Consumo promedio de combustible (L/100km)</label>
                                        <input type="number" step="0.01" name="avg_fuel_consumption" class="form-control" value="0">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Precio al cliente</label>
                                        <input type="number" step="0.01" name="customer_price" class="form-control" value="0">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ganancia del conductor</label>
                                        <input type="number" step="0.01" name="driver_profit" class="form-control" value="0">
                                    </div>
                                    <button type="submit" id="submitBtn" class="btn btn-primary">Crear Ruta</button>
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