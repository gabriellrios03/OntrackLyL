<?php
require '../Controllers/auth.php';
require '../Controllers/baseController.php';
// URLs de la API
$baseAPI = BASE_URL;
$apiUrlActive = BASE_URL . "/Delivery?status_id=10";
$apiUrlCancelled = BASE_URL . "/Delivery?status_id=2";

$token = $_SESSION['token'];

// Función para obtener datos de la API
function getDeliveryData($url, $token) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "ngrok-skip-browser-warning: true"
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (!$data || !$data['success']) {
        return [];
    }
    
    return $data['data'];
}

// Obtener datos
$activeDeliveries = getDeliveryData($apiUrlActive, $token);
$cancelledDeliveries = getDeliveryData($apiUrlCancelled, $token);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   LYL - Pre Viajes
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
    .tab-content {
      display: none;
    }
    .tab-content.active {
      display: block;
    }
    .nav-link.active {
      background-color: #fff;
      color: #495057;
      border-color: #dee2e6 #dee2e6 #fff;
    }
  </style>
  <script>
    function openTab(evt, tabName) {
      var i, tabcontent, tablinks;
      
      tabcontent = document.getElementsByClassName("tab-content");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
      }
      
      tablinks = document.getElementsByClassName("nav-link");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
      }
      
      document.getElementById(tabName).classList.add("active");
      evt.currentTarget.classList.add("active");
    }
    
    function searchTable(inputId, tableId) {
      let input = document.getElementById(inputId).value.toLowerCase();
      let rows = document.querySelectorAll(`#${tableId} tbody tr`);
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
                            <h6>Pre Viajes</h6>
                            
                            <!-- Pestañas -->
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" onclick="openTab(event, 'active')">Activos</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" onclick="openTab(event, 'cancelled')">Cancelados</button>
                                </li>
                            </ul>
                            
                            <!-- Contenido de pestañas -->
                            <div class="tab-content active" id="active">
                                <input type="text" id="searchActive" class="form-control mb-3 mt-3" placeholder="Buscar viajes activos..." onkeyup="searchTable('searchActive', 'activeTable')">
                                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                    <table class="table" id="activeTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Camion</th>
                                                <th>Placas</th>
                                                <th>Ruta</th>
                                                <th>Fecha Cita</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($activeDeliveries as $delivery): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($delivery['delivery_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['customer_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['truck_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['truck_plate']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['route_origin'] . ' a ' . $delivery['route_destination']); ?></td>
                                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($delivery['appointment_date']))); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['status_description']); ?></td>
                                                    <td>
                                                        <button class="btn btn-info btn-sm">Procesar Viaje</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-content" id="cancelled">
                                <input type="text" id="searchCancelled" class="form-control mb-3 mt-3" placeholder="Buscar viajes cancelados..." onkeyup="searchTable('searchCancelled', 'cancelledTable')">
                                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                    <table class="table" id="cancelledTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Camion</th>
                                                <th>Placas</th>
                                                <th>Ruta</th>
                                                <th>Fecha Cita</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cancelledDeliveries as $delivery): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($delivery['delivery_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['customer_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['truck_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['truck_plate']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['route_origin'] . ' a ' . $delivery['route_destination']); ?></td>
                                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($delivery['appointment_date']))); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['status_description']); ?></td>
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
        </div>
    </main>
</body>
</html>