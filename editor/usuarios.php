<?php
// editor/usuarios.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Protege el panel del editor
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'Editor' && $_SESSION['rol'] !== 'Administrador')) {
    header("Location: ../index.php");
    exit;
}

// incluye SIEMPRE con ruta absoluta desde el archivo actual
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../includes/header.php';

// --- aquí tu lógica para registrar DUEÑOS (tabla USUARIO) ---
// Ejemplo mínimo:
$alert = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $correo    = trim($_POST['correo'] ?? '');

    if ($nombre && $direccion && $telefono && $correo) {
        // ¿existe ya el correo?
        $sqlCheck = "SELECT 1 FROM USUARIO WHERE CorreoElectronico = ?";
        $stmtC = sqlsrv_query($conn, $sqlCheck, [$correo]);
        if ($stmtC && sqlsrv_fetch_array($stmtC, SQLSRV_FETCH_ASSOC)) {
            $alert = "<div class='alert alert-warning'>⚠️ Ese correo ya está registrado en dueños.</div>";
        } else {
            $sql = "INSERT INTO USUARIO (Nombre, Direccion, Telefono, CorreoElectronico, FechaRegistro)
                    VALUES (?, ?, ?, ?, GETDATE())";
            $ok  = sqlsrv_query($conn, $sql, [$nombre, $direccion, $telefono, $correo]);
            $alert = $ok
                ? "<div class='alert alert-success'>✅ Dueño registrado correctamente.</div>"
                : "<div class='alert alert-danger'>❌ Error al registrar dueño.</div>";
        }
    } else {
        $alert = "<div class='alert alert-warning'>⚠️ Completa todos los campos.</div>";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Registrar Dueño</title>
  <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
<div class="container my-4">
  <h2 class="text-success mb-3">👤 Registrar Dueño</h2>
  <?= $alert ?>
  <form method="post" class="row g-3">
    <div class="col-md-6"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Correo</label><input type="email" name="correo" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Teléfono</label><input name="telefono" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Dirección</label><input name="direccion" class="form-control" required></div>
    <div class="col-12"><button class="btn btn-success">Registrar Dueño</button></div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
