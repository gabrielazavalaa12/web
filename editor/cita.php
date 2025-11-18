<?php
// editor/cita.php
// Registrar CITA en SQL Server y redirigir a factura.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../conexion.php");

$mensaje = "";

// ===================================================
//  1) PROCESAR FORMULARIO (POST)
// ===================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_usuario   = $_POST['id_usuario']   ?? null;
    $id_mascota   = $_POST['id_mascota']   ?? null;
    $id_servicio  = $_POST['id_servicio']  ?? null;
    $fecha_cita   = $_POST['fecha_cita']   ?? null; // formato: YYYY-MM-DD
    $hora_cita    = $_POST['hora_cita']    ?? null; // formato: HH:MM

    if ($id_usuario && $id_mascota && $id_servicio && $fecha_cita && $hora_cita) {

        // 1.1 Obtener datos del servicio (Nombre y Precio)
        $sqlServ = "SELECT Nombre, Precio FROM SERVICIO WHERE ID_Servicio = ?";
        $stmtServ = sqlsrv_query($conn, $sqlServ, array($id_servicio));

        if ($stmtServ && ($rowServ = sqlsrv_fetch_array($stmtServ, SQLSRV_FETCH_ASSOC))) {

            $motivo = $rowServ['Nombre'];     // Lo guardamos como Motivo de la cita
            $costo  = $rowServ['Precio'];     // Lo guardamos como Costo de la cita

            // 1.2 Insertar la CITA y obtener el ID_Cita
            $sqlCita = "
                INSERT INTO CITA 
                    (ID_Mascota, ID_Usuario, ID_Vet, Fecha, Hora, Motivo, Estado, FechaCreacion, Costo)
                OUTPUT INSERTED.ID_Cita
                VALUES 
                    (?, ?, NULL, ?, ?, ?, 'Pendiente', GETDATE(), ?)
            ";

            $paramsCita = array(
                $id_mascota,
                $id_usuario,
                $fecha_cita,
                $hora_cita,
                $motivo,
                $costo
            );

            $stmtCita = sqlsrv_query($conn, $sqlCita, $paramsCita);

            if ($stmtCita !== false && ($rowCita = sqlsrv_fetch_array($stmtCita, SQLSRV_FETCH_ASSOC))) {

                $id_cita = $rowCita['ID_Cita'];

                // 1.3 Crear registro en FACTURACION (opcional, pero recomendado)
                $sqlFact = "
                    INSERT INTO FACTURACION (ID_Cita, Total, MetodoPago, Fecha)
                    VALUES (?, ?, 'Pendiente', GETDATE())
                ";
                $paramsFact = array($id_cita, $costo);
                sqlsrv_query($conn, $sqlFact, $paramsFact);

                // 1.4 REDIRECCIONAR A LA FACTURA
                // Si factura.php está en esta misma carpeta "editor", la ruta es solo "factura.php"
                header("Location: factura.php?id_cita=" . urlencode($id_cita));
                exit;

            } else {
                $mensaje = "❌ Error al registrar la cita.";
            }

        } else {
            $mensaje = "❌ No se pudo obtener la información del servicio.";
        }

    } else {
        $mensaje = "⚠️ Por favor completa todos los campos.";
    }
}

// ===================================================
//  2) CONSULTAS PARA LLENAR LOS SELECTS DEL FORM
// ===================================================

// Usuarios (dueños)
$usuarios = [];
$sqlUsuarios  = "SELECT ID_Usuario, Nombre FROM USUARIO ORDER BY Nombre";
$stmtUsuarios = sqlsrv_query($conn, $sqlUsuarios);
if ($stmtUsuarios) {
    while ($row = sqlsrv_fetch_array($stmtUsuarios, SQLSRV_FETCH_ASSOC)) {
        $usuarios[] = $row;
    }
}

// Mascotas
$mascotas = [];
$sqlMascotas  = "
    SELECT M.ID_Mascota, M.Nombre AS NombreMascota, U.Nombre AS NombreDueno
    FROM MASCOTA M
    INNER JOIN USUARIO U ON M.ID_Usuario = U.ID_Usuario
    ORDER BY U.Nombre, M.Nombre
";
$stmtMascotas = sqlsrv_query($conn, $sqlMascotas);
if ($stmtMascotas) {
    while ($row = sqlsrv_fetch_array($stmtMascotas, SQLSRV_FETCH_ASSOC)) {
        $mascotas[] = $row;
    }
}

// Servicios
$servicios = [];
$sqlServicios  = "SELECT ID_Servicio, Nombre, Precio FROM SERVICIO ORDER BY Nombre";
$stmtServicios = sqlsrv_query($conn, $sqlServicios);
if ($stmtServicios) {
    while ($row = sqlsrv_fetch_array($stmtServicios, SQLSRV_FETCH_ASSOC)) {
        $servicios[] = $row;
    }
}

// ===================================================
//  3) DESDE AQUÍ YA PODEMOS MOSTRAR HTML
// ===================================================
$pageTitle = "Registrar Cita 🐾";
include("../includes/header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Cita</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        body {
            background: #f8faf9;
            font-family: "Poppins", sans-serif;
        }
        .form-wrapper {
            max-width: 900px;
            margin: 40px auto 60px;
            background: white;
            border-radius: 18px;
            box-shadow: 0px 8px 18px rgba(0,0,0,0.08);
            padding: 35px 45px 45px;
        }
        h2 {
            text-align: center;
            color: #2d6a4f;
            font-size: 28px;
            margin-bottom: 25px;
        }
        label {
            font-weight: 600;
            color: #1b4332;
            margin-top: 12px;
        }
        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1.5px solid #b7e4c7;
            font-size: 15px;
            transition: .3s;
        }
        select:focus, input:focus {
            outline: none;
            border-color: #40916c;
            box-shadow: 0 0 5px rgba(64,145,108,0.4);
        }
        button {
            background-color: #40916c;
            color: white;
            font-weight: bold;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            margin-top: 25px;
            cursor: pointer;
            width: 100%;
            transition: .3s;
        }
        button:hover {
            background-color: #2d6a4f;
        }
        .alert {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
        .alert-success { background:#d8f3dc; color:#1b4332; }
        .alert-warning { background:#fff3bf; color:#664d03; }
        .alert-danger  { background:#ffe5e5; color:#d00000; }
    </style>
</head>
<body>

<div class="form-wrapper">
    <h2>Registrar Cita 🐾</h2>

    <?php if (!empty($mensaje)): ?>
        <?php
            $class = 'alert-warning';
            if (str_starts_with($mensaje, '❌')) $class = 'alert-danger';
            if (str_starts_with($mensaje, '✅')) $class = 'alert-success';
        ?>
        <div class="alert <?php echo $class; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" action="cita.php">
        <!-- Dueño -->
        <label for="id_usuario">Dueño (Usuario)</label>
        <select name="id_usuario" id="id_usuario" required>
            <option value="">Seleccione un usuario...</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?php echo $u['ID_Usuario']; ?>">
                    <?php echo htmlspecialchars($u['Nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Mascota -->
        <label for="id_mascota">Mascota</label>
        <select name="id_mascota" id="id_mascota" required>
            <option value="">Seleccione una mascota...</option>
            <?php foreach ($mascotas as $m): ?>
                <option value="<?php echo $m['ID_Mascota']; ?>">
                    <?php echo htmlspecialchars($m['NombreMascota'] . " (Dueño: " . $m['NombreDueno'] . ")"); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Servicio -->
        <label for="id_servicio">Tipo de Servicio / Motivo</label>
        <select name="id_servicio" id="id_servicio" required>
            <option value="">Seleccione un servicio...</option>
            <?php foreach ($servicios as $s): ?>
                <option value="<?php echo $s['ID_Servicio']; ?>">
                    <?php echo htmlspecialchars($s['Nombre']) . " - $"
                         . number_format($s['Precio'], 2); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Fecha -->
        <label for="fecha_cita">Fecha de la Cita</label>
        <input type="date" name="fecha_cita" id="fecha_cita" required>

        <!-- Hora -->
        <label for="hora_cita">Hora de la Cita</label>
        <input type="time" name="hora_cita" id="hora_cita" required>

        <button type="submit">Registrar Cita</button>
    </form>

    <div class="text-center" style="margin-top:20px; text-align:center;">
        <a href="panel_editor.php" style="
            text-decoration:none;
            color:#2d6a4f;
            font-weight:600;">
            ⬅ Volver al Panel
        </a>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
</body>
</html>
