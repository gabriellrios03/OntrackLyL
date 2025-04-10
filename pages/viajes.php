<?php
require '../Controllers/auth.php';
require '../Controllers/baseController.php';

// URLs de la API
$baseAPI = BASE_URL;
$apiUrlInProgress = BASE_URL . "/Delivery?status_id=4"; // Viajes en progreso
$apiUrlCompleted = BASE_URL . "/Delivery?status_id=5";  // Viajes terminados

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
$inProgressDeliveries = getDeliveryData($apiUrlInProgress, $token);
$completedDeliveries = getDeliveryData($apiUrlCompleted, $token);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   LYL - Viajes
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
    .btn-action {
      margin-left: 5px;
    }
    .badge-status {
      font-size: 0.85em;
      padding: 5px 10px;
      border-radius: 20px;
    }
    .badge-in-progress {
      background-color: #ffc107;
      color: #212529;
    }
    .badge-completed {
      background-color: #28a745;
      color: white;
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

    function confirmComplete(deliveryId) {
      if (confirm('¿Estás seguro que deseas marcar este viaje como terminado?')) {
        // Aquí iría la llamada a la API para terminar el viaje
        alert('Viaje marcado como terminado. Implementar llamada a API.');
        // Recargar la página para ver los cambios
        window.location.reload();
      }
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
                            <h6>Gestión de Viajes</h6>
                            
                            <!-- Pestañas -->
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" onclick="openTab(event, 'inProgress')">En Progreso</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" onclick="openTab(event, 'completed')">Terminados</button>
                                </li>
                            </ul>
                            
                            <!-- Contenido de pestañas -->
                            <div class="tab-content active" id="inProgress">
                                <input type="text" id="searchInProgress" class="form-control mb-3 mt-3" placeholder="Buscar viajes en progreso..." onkeyup="searchTable('searchInProgress', 'inProgressTable')">
                                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                    <table class="table" id="inProgressTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Camion</th>
                                                <th>Remsión</th>
                                                <th>Ruta</th>
                                                <th>Fecha Inicio</th>
                                                <th>Caja</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($inProgressDeliveries as $delivery): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($delivery['delivery_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['customer_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['truck_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['remision_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['route_origin'] . ' a ' . $delivery['route_destination']); ?></td>
                                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($delivery['start_date'] ?? $delivery['appointment_date']))); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['dryvan_name']); ?></td>
                                                    <td>
                                                        <span class="badge-status badge-in-progress">En Progreso</span>
                                                    </td>
                                                    <td>
                                                    <a href="../Controllers/FormsE/FinalizarViaje.php?delivery_id=<?php echo $delivery['delivery_id']; ?>" class="btn btn-success btn-sm btn-action">Finalizar Viaje</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-content" id="completed">
                                <input type="text" id="searchCompleted" class="form-control mb-3 mt-3" placeholder="Buscar viajes terminados..." onkeyup="searchTable('searchCompleted', 'completedTable')">
                                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                    <table class="table" id="completedTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Camion</th>
                                                <th>Remisión</th>
                                                <th>Ruta</th>
                                                <th>Fecha Inicio</th>
                                                <th>Caja</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($completedDeliveries as $delivery): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($delivery['delivery_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['customer_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['truck_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['remision_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['route_origin'] . ' a ' . $delivery['route_destination']); ?></td>
                                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($delivery['start_date'] ?? $delivery['appointment_date']))); ?></td>
                                                    <td><?php echo htmlspecialchars($delivery['dryvan_name']); ?></td>
                                                    <td>
                                                        <span class="badge-status badge-completed">Terminado</span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-info btn-sm btn-action">
                                                            Ver Detalles
                                                        </button>
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
        </div>
    </main>
</body>
</html>