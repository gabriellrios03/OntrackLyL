<?php
require '../Controllers/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    LYL ONTRACK - DELIVERIES
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
</head>

<body class="g-sidenav-show  bg-gray-100">
  <!--Se carga La sideBar Dinamica -->
  <?php include_once ('../Components/SideBar.php') ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-3">
          <hr>
          <h3 class="mb-0 h4 font-weight-bolder">Accesos Directos</h3>
          <p class="mb-4">LYL ONTRACK. QA - ENV  Version 1.0.0</p>
        </div>
        
        <!-- Quick Access Cards -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <a href="../pages/pre-viajes.php" class="card text-decoration-none">
            <div class="card-header p-2 ps-3 d-flex align-items-center">
              <i class="material-symbols-rounded opacity-5 me-2">schedule</i>
              <span class="nav-link-text ms-1">PreViajes</span>
            </div>
          </a>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <a href="../pages/viajes.php" class="card text-decoration-none">
            <div class="card-header p-2 ps-3 d-flex align-items-center">
              <i class="material-symbols-rounded opacity-5 me-2">directions_car</i>
              <span class="nav-link-text ms-1">Viajes</span>
            </div>
          </a>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <a href="../pages/clientes.php" class="card text-decoration-none">
            <div class="card-header p-2 ps-3 d-flex align-items-center">
              <i class="material-symbols-rounded opacity-5 me-2">group</i>
              <span class="nav-link-text ms-1">Clientes</span>
            </div>
          </a>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <a href="../pages/drivers.php" class="card text-decoration-none">
            <div class="card-header p-2 ps-3 d-flex align-items-center">
              <i class="material-symbols-rounded opacity-5 me-2">person</i>
              <span class="nav-link-text ms-1">Drivers</span>
            </div>
          </a>
        </div>
      </div>
      
      <!-- GPS Iframe -->
      <div class="row mt-4">
        <div class="col-xl-12 col-sm-12 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <h5 class="mb-0">GPS Tracking</h5>
            </div>
            <div class="card-body text-center">
              <iframe src="https://gps.logitrack.mx/v2/" width="100%" height="600px" style="border: none;"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  
  
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
    
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>