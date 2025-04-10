<?php
require '../Controllers/auth.php';
require '../Controllers/baseController.php';

// URL de la API para conductores
$apiUrl = BASE_URL . "/Drivers";
$token = $_SESSION['token'];

// Función para obtener datos de conductores
function getDriversData($url, $token) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "ngrok-skip-browser-warning: true",
        "accept: */*"
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (!$data || !$data['success']) {
        return [];
    }
    
    return $data['data'];
}

// Obtener datos de conductores
$drivers = getDriversData($apiUrl, $token);

// Mapear estados (asumiendo que tienes esta información)
$statusMap = [
    6 => 'Activo',
    7 => 'Inactivo',
    // Agrega más estados según corresponda
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   LYL - Conductores
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
  <style>
    .badge-status {
      font-size: 0.85em;
      padding: 5px 10px;
      border-radius: 20px;
    }
    .badge-active {
      background-color: #28a745;
      color: white;
    }
    .badge-inactive {
      background-color: #dc3545;
      color: white;
    }
  </style>
  <script>
    function searchTable() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let rows = document.querySelectorAll("#driversTable tbody tr");
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
                                <a href="../Controllers/FormsN/NuevoConductor.php" class="btn btn-success mb-3">Nuevo Conductor</a>
                            </div>
                            <h6>Catálogo de Conductores</h6>
                            <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar conductores..." onkeyup="searchTable()" style="margin-top: 10px;">
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table" id="driversTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>RFC</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($drivers as $driver): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($driver['id']); ?></td>
                                                <td><?php echo htmlspecialchars($driver['name']); ?></td>
                                                <td><?php echo htmlspecialchars($driver['lastname']); ?></td>
                                                <td><?php echo htmlspecialchars($driver['rfc']); ?></td>
                                                <td>
                                                    <?php 
                                                    $statusClass = ($driver['status_Id'] == 6) ? 'badge-active' : 'badge-inactive';
                                                    $statusText = $statusMap[$driver['status_Id']] ?? 'Desconocido';
                                                    ?>
                                                    <span class="badge-status <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($statusText); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="../Controllers/FormsE/editar_conductor.php?id=<?php echo $driver['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
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