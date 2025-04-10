<?php
require '../auth.php';
require '../baseController.php';

// Get dryvan ID from URL
$dryvanId = $_GET['id'] ?? null;
if (!$dryvanId) {
    die("ID de dryvan no especificado.");
}

// API URL
$dryvanApiUrl = BASE_URL . "/Dryvans/" . $dryvanId;
$token = $_SESSION['token'];
$error = "";
$success = "";

// Fetch current dryvan data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $dryvanApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "ngrok-skip-browser-warning: true",
    "accept: */*"
]);
$dryvanResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode != 200) {
    die("Error al obtener los datos del dryvan.");
}

$dryvanData = json_decode($dryvanResponse, true);
$dryvan = $dryvanData['data'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $plate = $_POST['plate'] ?? '';
    $last_location = $_POST['last_location'] ?? '';

    if (!empty($name) && !empty($plate)) {
        $putData = json_encode([
            "id" => (int)$dryvanId,
            "name" => $name,
            "plate" => $plate,
            "last_location" => $last_location
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $dryvanApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/json",
            "ngrok-skip-browser-warning: true",
            "accept: */*"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $putData);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $success = "Dryvan actualizado exitosamente.";
            // Refresh dryvan data
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $dryvanApiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $token",
                "ngrok-skip-browser-warning: true",
                "accept: */*"
            ]);
            $dryvanResponse = curl_exec($ch);
            curl_close($ch);
            $dryvanData = json_decode($dryvanResponse, true);
            $dryvan = $dryvanData['data'];
        } else {
            $error = "Error al actualizar el dryvan. Código HTTP: $httpCode";
            if ($response) {
                $errorData = json_decode($response, true);
                $error .= " - " . ($errorData['message'] ?? 'Error desconocido');
            }
        }
    } else {
        $error = "Nombre y placa son campos obligatorios.";
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
  <title>LYL - Editar Dryvan</title>
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
                            <h6>Editar Dryvan #<?php echo htmlspecialchars($dryvanId); ?></h6>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <a href="https://qa.lylautotransportes.com.mx/pages/equipo.php" class="btn btn-secondary">Regresar a Dryvans</a>
                            <?php else: ?>
                                <form method="POST" onsubmit="disableSubmitButton()">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre del Dryvan</label>
                                        <input type="text" name="name" class="form-control" 
                                               value="<?php echo htmlspecialchars($dryvan['name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Placa</label>
                                        <input type="text" name="plate" class="form-control" 
                                               value="<?php echo htmlspecialchars($dryvan['plate']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Última Ubicación</label>
                                        <input type="text" name="last_location" class="form-control" 
                                               value="<?php echo htmlspecialchars($dryvan['last_location']); ?>">
                                    </div>
                                    <button type="submit" id="submitBtn" class="btn btn-primary">Actualizar Dryvan</button>
                                    <a href="https://qa.lylautotransportes.com.mx/pages/equipo.php" class="btn btn-secondary">Cancelar</a>
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