<?php
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre   = $_POST["nombre"];
    $correo   = $_POST["correo"];
    $password = $_POST["password"];
    $rol      = $_POST["rol"];

    // Verificar si ya existe el correo
    $sqlCheck     = "SELECT * FROM USUARIOS_LOGIN WHERE Correo = ?";
    $paramsCheck  = array($correo);
    $stmtCheck    = sqlsrv_query($conn, $sqlCheck, $paramsCheck);

    if ($stmtCheck && sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC)) {
        $mensaje = "<div class='alert alert-warning'>⚠️ El correo ya está registrado.</div>";
    } else {
        // Generar hash seguro
        $hash   = password_hash($password, PASSWORD_BCRYPT);
        $sql    = "INSERT INTO USUARIOS_LOGIN (Nombre, Correo, Password, Rol) VALUES (?, ?, ?, ?)";
        $params = array($nombre, $correo, $hash, $rol);
        $stmt   = sqlsrv_query($conn, $sql, $params);

        if ($stmt) {
            $mensaje = "<div class='alert alert-success'>✅ Usuario registrado correctamente. Ya puedes iniciar sesión.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>❌ Error al registrar usuario.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario - Veterinaria</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: linear-gradient(120deg, #95d5b2, #74c69d);
            font-family: "Poppins", sans-serif;
        }
        .container {
            max-width: 450px;
            margin: 80px auto;
            background: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.1);
        }
        .btn-register {
            background-color: #2d6a4f;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px;
            width: 100%;
            font-weight: bold;
        }
        .btn-register:hover {
            background-color: #1b4332;
        }
        a {
            color: #2d6a4f;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center mb-3">🐾 Veterinaria Mi Mascota</h3>
    <h5 class="text-center mb-4">Crear Cuenta</h5>

    <?= $mensaje ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="correo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select" required>
                <option value="">Seleccione un rol...</option>
                <option value="Administrador">Administrador</option>
                <option value="Editor">Editor</option>
                <option value="Consultor">Consultor</option>
            </select>
        </div>

        <button class="btn-register" type="submit">Crear Cuenta</button>
    </form>

    <p class="text-center mt-3">
        ¿Ya tienes cuenta?
        <a href="index.php">Inicia sesión aquí</a>
    </p>
</div>

</body>
</html>
