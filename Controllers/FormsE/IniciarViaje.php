<?php
require '../../Controllers/auth.php';
require '../../Controllers/baseController.php';

if (!isset($_GET['delivery_id']) || empty($_GET['delivery_id'])) {
    header("Location: ../PreViajes.php");
    exit();
}

$deliveryId = $_GET['delivery_id'];
$token = $_SESSION['token'];
$error = "";
$success = "";

// Obtener la lista de dryvans disponibles
$dryvans = [];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, BASE_URL . '/Dryvans');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "ngrok-skip-browser-warning: true"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    if ($data && $data['success']) {
        $dryvans = $data['data'];
    }
}
curl_close($ch);

// Obtener la lista de conductores disponibles
$drivers = [];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, BASE_URL . '/Drivers');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "ngrok-skip-browser-warning: true"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    if ($data && $data['success']) {
        $drivers = $data['data'];
    }
}
curl_close($ch);

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'header_id' => $deliveryId,
        'driver_Id' => $_POST['driver_Id'],
        'dryvan_id' => $_POST['dryvan_id'],
        'remision_number' => $_POST['remision_number'],
        'notes' => $_POST['notes']
    ];

    // Validar campos requeridos
    if (empty($data['driver_Id']) || empty($data['dryvan_id']) || empty($data['remision_number'])) {
        $error = "Todos los campos marcados con * son obligatorios.";
    } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, BASE_URL . '/Delivery');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/json",
            "ngrok-skip-browser-warning: true"
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200 || $httpCode == 201) {
            $success = "Viaje iniciado con éxito.";
        } else {
            $error = "Error al iniciar el viaje. Código: $httpCode";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>LYL - Iniciar Viaje</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="../../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>
<body class="bg-gray-100">
    <?php include_once ('../../Components/SideBar.php') ?>

    <main class="main-content">
        <div class="container py-4">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h6>Iniciar Viaje - ID: <?php echo htmlspecialchars($deliveryId); ?></h6>
                            
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <a href="https://qa.lylautotransportes.com.mx/pages/pre-viajes.php" class="btn btn-secondary">Regresar a Pre-Viajes</a>
                            <?php else: ?>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Conductor *</label>
                                        <select name="driver_Id" class="form-select" required>
                                            <option value="">Seleccione un conductor</option>
                                            <?php foreach ($drivers as $driver): ?>
                                                <option value="<?php echo htmlspecialchars($driver['id']); ?>">
                                                    <?php echo htmlspecialchars($driver['name']); ?> <?php echo htmlspecialchars($driver['lastname']); ?>
                                                    <?php if (!empty($driver['rfc'])): ?>
                                                        (<?php echo htmlspecialchars($driver['rfc']); ?>)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Dryvan *</label>
                                        <select name="dryvan_id" class="form-select" required>
                                            <option value="">Seleccione un dryvan</option>
                                            <?php foreach ($dryvans as $dryvan): ?>
                                                <option value="<?php echo htmlspecialchars($dryvan['id']); ?>">
                                                    <?php echo htmlspecialchars($dryvan['name']); ?> - <?php echo htmlspecialchars($dryvan['plate']); ?>
                                                    <?php if (!empty($dryvan['last_location'])): ?>
                                                        (<?php echo htmlspecialchars($dryvan['last_location']); ?>)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Número de Remisión *</label>
                                        <input type="text" name="remision_number" class="form-control" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Notas</label>
                                        <textarea name="notes" class="form-control" rows="3"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Confirmar Inicio</button>
                                    <a href="https://qa.lylautotransportes.com.mx/pages/pre-viajes.php" class="btn btn-secondary">Cancelar</a>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </main>

    <!--   Core JS Files   -->
    <script src="../../assets/js/core/popper.min.js"></script>
    <script src="../../assets/js/core/bootstrap.min.js"></script>
    <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
</body>
</html>