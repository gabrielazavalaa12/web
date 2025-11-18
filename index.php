<?php
session_start();
include("conexion.php");

// Si ya hay sesión activa, redirige según rol
if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 'Administrador':
            header("Location: admin/panel_admin.php");
            exit;
        case 'Editor':
            header("Location: editor/panel_editor.php");
            exit;
        case 'Consultor':
            header("Location: consultor/panel_consultor.php");
            exit;
    }
}

$error = "";

// Procesar login (versión MySQLi)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo   = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    // Usamos consulta preparada para evitar inyección SQL
    $sql  = "SELECT * FROM USUARIOS_LOGIN WHERE Correo = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $hash = $row['Password'];

            if (password_verify($password, $hash)) {
                $_SESSION['id_usuario'] = $row['ID_Usuario'];
                $_SESSION['nombre']     = $row['Nombre'];
                $_SESSION['rol']        = $row['Rol'];

                switch ($row['Rol']) {
                    case 'Administrador':
                        header("Location: admin/panel_admin.php");
                        exit;
                    case 'Editor':
                        header("Location: editor/panel_editor.php");
                        exit;
                    case 'Consultor':
                        header("Location: consultor/panel_consultor.php");
                        exit;
                }
            } else {
                $error = "❌ Contraseña incorrecta.";
            }
        } else {
            $error = "⚠️ No se encontró una cuenta con ese correo.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = "⚠️ Error en la preparación de la consulta: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar Sesión - Veterinaria</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: linear-gradient(120deg, #95d5b2, #74c69d);
  font-family: "Poppins", sans-serif;
}
.login-container {
  max-width: 400px;
  margin: 80px auto;
  background: #fff;
  padding: 35px;
  border-radius: 15px;
  box-shadow: 0px 10px 25px rgba(0,0,0,0.1);
}
.btn-login {
  background-color: #2d6a4f;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px;
  width: 100%;
  font-weight: bold;
}
.btn-login:hover {
  background-color: #1b4332;
}
a {
  color: #2d6a4f;
  text-decoration: none;
  font-weight: 500;
}
h3 {
  color: #2d6a4f;
  font-weight: bold;
}
</style>
</head>
<body>
<div class="login-container">
  <h3 class="text-center mb-3">🐾 Veterinaria Mi Mascota</h3>
  <h5 class="text-center mb-4">Iniciar Sesión</h5>

  <?php if (!empty($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Correo electrónico</label>
      <input type="email" name="correo" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Contraseña</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn-login" type="submit">Iniciar Sesión</button>
  </form>

  <p class="text-center mt-3">¿No tienes cuenta?
    <a href="registro_usuario.php">Crea una aquí</a>
  </p>
</div>
</body>
</html>
