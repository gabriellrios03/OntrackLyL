<?php
require '../Controllers/auth.php';
require '../Controllers/baseController.php';  // Include the file with the base URL

// API URLs using BASE_URL
$apiUrl = BASE_URL . "/dryvans";  // Using the base URL for dryvans

$token = $_SESSION['token'];

// Configure cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "ngrok-skip-browser-warning: true"
]);

$response = curl_exec($ch);
curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

// Verify if the request was successful
if (!$data || !$data['success']) {
    die("Error al obtener los dryvans.");
}

$dryvans = $data['data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   LYL - Dryvans
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <script>
    function searchTable() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let rows = document.querySelectorAll("#dryvanTable tbody tr");
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
  </script>
</head>
<body class="bg-gray-100">
    <?php include_once ('../Components/SideBar.php') ?>

    <main class="main-content">
        <hr>
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <a href="../Controllers/FormsN/Dryvan.php" class="btn btn-success mb-3">Nuevo Dryvan</a>
                            </div>
                            <h6>Catálogo de Dryvans</h6>
                            <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar dryvans..." onkeyup="searchTable()" style="margin-top: 10px;">
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table" id="dryvanTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Placa</th>
                                            <th>Última Ubicación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dryvans as $dryvan): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($dryvan['id']); ?></td>
                                                <td><?php echo htmlspecialchars($dryvan['name']); ?></td>
                                                <td><?php echo htmlspecialchars($dryvan['plate']); ?></td>
                                                <td><?php echo htmlspecialchars($dryvan['last_location']); ?></td>
                                                <td>
                                                    <a href="http://localhost:5500/Controllers/FormsE/edit_dryvan.php?id=<?php echo $dryvan['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>