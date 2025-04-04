<?php
require '../auth.php';
require '../baseController.php';  // Incluir el archivo con la base URL

$apiUrl = BASE_URL . "/Customer";  // Aquí ya estamos usando la URL base
$token = $_SESSION['token'];
$customerId = $_GET['id'] ?? '';  // Obtener el ID del cliente desde la URL
$error = "";
$success = "";

if (!$customerId) {
    die("No se especificó un ID de cliente.");
}

// Obtener los datos del cliente
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$apiUrl/$customerId");
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
    die("Error al obtener los datos del cliente.");
}

$customer = $data['data'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $rfc = $_POST['rfc'] ?? '';

    if (!empty($name) && !empty($rfc)) {
        $putData = json_encode(["name" => $name, "rfc" => $rfc]);

        // Actualizar los datos del cliente
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$apiUrl/$customerId");
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
            $success = "Cliente actualizado exitosamente.";
        } else {
            $error = "Error al actualizar el cliente.";
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
    LYL - Editar Cliente
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
                            <h6>Editar Cliente</h6>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <a href="http://localhost:5500/pages/clientes.php" class="btn btn-secondary">Regresar a Clientes</a>
                            <?php else: ?>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">RFC</label>
                                        <input type="text" name="rfc" class="form-control" value="<?php echo htmlspecialchars($customer['rfc']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
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
