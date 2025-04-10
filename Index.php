<?php 
require_once './Controllers/baseController.php'; // Incluir el archivo con la base URL
$apiUrl = BASE_URL . "/User/login"; // Aquí ya estamos usando la URL base
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>LYL ONTRACK - LOGIN</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: #f5f5f5;
    }
    .login-container {
      width: 100%;
      max-width: 400px;
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    .logo {
      max-width: 120px;
      margin-bottom: 1rem;
    }
    .btn-primary {
      background: linear-gradient(90deg, #1d8cf8, #3358f4);
      border: none;
    }
    .btn-primary:hover {
      background: linear-gradient(90deg, #3358f4, #1d8cf8);
    }
    .form-control {
      border: none;
      border-bottom: 2px solid #ccc;
      border-radius: 0;
      padding: 10px;
      background: transparent;
      box-shadow: none;
    }
    .form-control:focus {
      border-bottom: 2px solid #3358f4;
      outline: none;
      box-shadow: none;
    }
    .error-message {
      color: red;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <?php session_start(); ?>
  <div class="login-container">
    <img src="../assets/img/logo_lyl.png" alt="LYL OnTrack Logo" class="logo">
    <h3 class="font-weight-bold">Ambiente de Pruebas</h3>
    <p class="text-muted">Sign in to continue</p>
    <form id="loginForm">
      <div class="mb-3 text-start">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3 text-start">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
      <div id="errorMessage" class="error-message"></div>
    </form>
    <div class="mt-3">
      <small><a href="#" class="text-primary">Forgot password?</a></small>
    </div>
    <div class="mt-2">
      <small>Don't have an account? <a href="#" class="text-primary">Sign up</a></small>
    </div>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
      event.preventDefault();
      
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const errorMessage = document.getElementById('errorMessage');
      errorMessage.textContent = '';
      
      try {
        const response = await fetch('https://manually-massive-flamingo.ngrok-free.app/api/User/login', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ email, passwordHash: password })
        });
        
        const data = await response.json();
        
        if (data.success) {
          fetch('./Controllers/session.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token: data.data.token })
          }).then(() => {
            window.location.href = 'pages/home.php';
          });
        } else {
          errorMessage.textContent = 'Invalid credentials. Please try again.';
        }
      } catch (error) {
        errorMessage.textContent = 'An error occurred. Please try again later.';
      }
    });
  </script>
</body>
</html>