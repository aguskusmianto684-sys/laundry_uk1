<?php

session_start();
if (isset($_SESSION['logged_in'])) {
    echo "<script>
        alert('Anda sudah login');
        window.location.href='../../pages/dashboard/index.php';
    </script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      background: linear-gradient(135deg, #0077b6, #90e0ef);
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-card {
      width: 400px;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
      background: #fff;
      animation: fadeIn 1s ease;
    }

    .login-card-header {
      background: linear-gradient(135deg, #023e8a, #0077b6, #90e0ef);
      color: #fff;
      text-align: center;
      padding: 20px;
      font-size: 1.5rem;
      font-weight: bold;
    }

    .login-card-body {
      padding: 30px;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
      border-color: #0077b6;
    }

    #togglePassword {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
    }

    @keyframes fadeIn {
      from {opacity:0; transform: translateY(-20px);}
      to {opacity:1; transform: translateY(0);}
    }

    .text-muted a {
      text-decoration: none;
      color: #0077b6;
    }

    .text-muted a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="login-card shadow">
    <div class="login-card-header">
      Login
    </div>
    <div class="login-card-body">
      <form action="../../actions/auth/login_proses.php" method="POST">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password" required>
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
              <i class="bi bi-eye-slash" id="iconEye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
      <!-- <p class="text-center mt-3 text-muted">Belum punya akun? <a href="register.php">Registrasi</a></p> -->
    </div>
  </div>

  <script>
    const toggle = document.getElementById("togglePassword");
    const password = document.getElementById("password");
    const icon = document.getElementById("iconEye");

    toggle.addEventListener("click", () => {
      const type = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);
      icon.classList.toggle("bi-eye");
      icon.classList.toggle("bi-eye-slash");
    });
  </script>
</body>
</html>
