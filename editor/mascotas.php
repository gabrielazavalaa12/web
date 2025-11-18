<?php
include("../conexion.php");

// Obtener los usuarios registrados (dueños)
$usuarios = [];
$query = "SELECT ID_Usuario, Nombre FROM USUARIO";
$result = sqlsrv_query($conn, $query);

if ($result) {
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $usuarios[] = $row;
    }
}

// Registrar mascota
$mensaje = ""; // variable para mensajes en pantalla

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];

    if (!empty($id_usuario) && !empty($nombre) && !empty($especie) && !empty($raza) && !empty($fecha_nacimiento) && !empty($sexo)) {
        $sql = "INSERT INTO MASCOTA (ID_Usuario, Nombre, Especie, Raza, FechaNacimiento, Sexo, FechaCreacion)
                VALUES (?, ?, ?, ?, ?, ?, GETDATE())";

        $params = array($id_usuario, $nombre, $especie, $raza, $fecha_nacimiento, $sexo);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt) {
            $mensaje = "<div class='alert success'>🎉 Mascota registrada correctamente.</div>";
        } else {
            $mensaje = "<div class='alert error'>❌ Error al registrar la mascota.</div>";
        }
    } else {
        $mensaje = "<div class='alert warning'>⚠️ Por favor completa todos los campos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mascota 🐾</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        body {
            background: #f8faf9;
            font-family: "Poppins", sans-serif;
        }
        .form-wrapper {
            max-width: 650px;
            margin: 60px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
            padding: 40px 50px;
        }
        h2 {
            text-align: center;
            color: #2d6a4f;
            font-size: 28px;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: 600;
            color: #1b4332;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1.5px solid #b7e4c7;
            border-radius: 8px;
            transition: 0.3s;
            font-size: 15px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #40916c;
            box-shadow: 0 0 5px rgba(64,145,108,0.4);
        }
        button {
            background-color: #40916c;
            color: white;
            font-weight: bold;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            margin-top: 25px;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }
        button:hover {
            background-color: #2d6a4f;
        }
        .alert {
            text-align: center;
            margin: 20px auto;
            padding: 12px;
            width: 80%;
            border-radius: 10px;
            font-weight: 600;
        }
        .alert.success { background: #d8f3dc; color: #1b4332; }
        .alert.error { background: #ffe5e5; color: #d00000; }
        .alert.warning { background: #fff3bf; color: #664d03; }
    </style>
</head>

<body>
    <?php include("../includes/header.php"); ?>

    <div class="form-wrapper">
        <h2>Registrar Mascota 🐾</h2>

        <?= $mensaje; // muestra los mensajes de éxito o error ?>

        <form action="mascotas.php" method="POST">
            <label for="id_usuario">Dueño (Usuario)</label>
            <select name="id_usuario" required>
                <option value="">Seleccione un usuario...</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['ID_Usuario']; ?>"><?= htmlspecialchars($usuario['Nombre']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="nombre">Nombre de la Mascota</label>
            <input type="text" name="nombre" placeholder="Ej. Rocky" required>

            <label for="especie">Especie</label>
            <input type="text" name="especie" placeholder="Ej. Perro, Gato" required>

            <label for="raza">Raza</label>
            <input type="text" name="raza" placeholder="Ej. Labrador, Siamés" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" required>

            <label for="sexo">Sexo</label>
            <select name="sexo" required>
                <option value="">Seleccione...</option>
                <option value="Macho">Macho</option>
                <option value="Hembra">Hembra</option>
            </select>

            <button type="submit">Registrar Mascota</button>
        </form>
    </div>

    <?php include("../includes/footer.php"); ?>
</body>
</html>