<?php
require_once("../conexion.php");

// -----------------------------------------
// Procesar asignación de veterinario (POST)
// -----------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_cita"], $_POST["id_vet"])) {
    $id_cita = (int) $_POST["id_cita"];
    $id_vet  = (int) $_POST["id_vet"];

    if ($id_cita > 0 && $id_vet > 0) {
        $sqlUpdate = "UPDATE CITA 
                      SET ID_Vet = ?, Estado = 'Programada'
                      WHERE ID_Cita = ?";
        $paramsUpdate = array($id_vet, $id_cita);
        $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

        if ($stmtUpdate) {
            $mensaje_ok = "✅ Veterinario asignado correctamente a la cita #{$id_cita}.";
        } else {
            $mensaje_error = "❌ Error al asignar el veterinario.";
        }
    } else {
        $mensaje_error = "❌ Datos inválidos para asignar veterinario.";
    }
}

// -----------------------------------------
// Obtener citas registradas por los editores
// (aquí puedes filtrar solo pendientes si quieres)
// -----------------------------------------
$sqlCitas = "
    SELECT 
        c.ID_Cita,
        c.Fecha,
        c.Hora,
        c.Motivo,
        c.Estado,
        u.Nombre  AS NombreDueno,
        m.Nombre  AS NombreMascota,
        v.ID_Vet,
        v.Nombre  AS NombreVet
    FROM CITA c
    INNER JOIN USUARIO u ON c.ID_Usuario = u.ID_Usuario
    INNER JOIN MASCOTA m ON c.ID_Mascota = m.ID_Mascota
    LEFT JOIN VET v      ON c.ID_Vet = v.ID_Vet
    ORDER BY c.Fecha, c.Hora, c.ID_Cita
";

$stmtCitas = sqlsrv_query($conn, $sqlCitas);

if (!$stmtCitas) {
    die("Error al obtener las citas: " . print_r(sqlsrv_errors(), true));
}

// -----------------------------------------
// Obtener veterinarios disponibles
// -----------------------------------------
$sqlVets = "SELECT ID_Vet, Nombre FROM VET ORDER BY Nombre";
$stmtVets = sqlsrv_query($conn, $sqlVets);

$veterinarios = [];
if ($stmtVets) {
    while ($row = sqlsrv_fetch_array($stmtVets, SQLSRV_FETCH_ASSOC)) {
        $veterinarios[] = $row;
    }
}

// -----------------------------------------
// HTML
// -----------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Citas a Veterinarios</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        .card {
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        .tabla-citas th {
            background-color: #b7e4c7;
            font-weight: 600;
        }
        .tabla-citas td, .tabla-citas th {
            font-size: 14px;
            vertical-align: middle;
        }
        .badge-estado {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-pendiente { background:#ffeeba; color:#856404; }
        .badge-programada { background:#d4edda; color:#155724; }
        .badge-otra { background:#e2e3e5; color:#383d41; }
    </style>
</head>
<body>

<?php include("../includes/header.php"); ?>

<div class="container mt-4 mb-5">
    <div class="card p-4 tabla-citas">
        <h2 class="text-center mb-3" style="color:#1b4332;">
            🐾 Asignación de Citas a Veterinarios
        </h2>
        <p class="text-center text-muted mb-4">
            Aquí puedes ver todas las citas registradas por los editores y asignarlas a veterinarios disponibles.
        </p>

        <?php if (!empty($mensaje_ok)): ?>
            <div class="alert alert-success text-center"><?php echo $mensaje_ok; ?></div>
        <?php endif; ?>

        <?php if (!empty($mensaje_error)): ?>
            <div class="alert alert-danger text-center"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Dueño</th>
                    <th>Mascota</th>
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Veterinario Asignado</th>
                    <th>Asignar Veterinario</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($cita = sqlsrv_fetch_array($stmtCitas, SQLSRV_FETCH_ASSOC)): ?>
                    <?php
                    // Formatear fecha y hora (aquí estaba el error)
                    $fecha = $cita['Fecha'] instanceof DateTime
                        ? $cita['Fecha']->format('Y-m-d')
                        : $cita['Fecha'];

                    $hora = $cita['Hora'] instanceof DateTime
                        ? $cita['Hora']->format('H:i')
                        : $cita['Hora'];

                    // Clase para el badge de estado
                    $estadoClase = 'badge-otra';
                    if ($cita['Estado'] === 'Pendiente')   $estadoClase = 'badge-pendiente';
                    if ($cita['Estado'] === 'Programada') $estadoClase = 'badge-programada';
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cita['ID_Cita']); ?></td>
                        <td><?php echo htmlspecialchars($cita['NombreDueno']); ?></td>
                        <td><?php echo htmlspecialchars($cita['NombreMascota']); ?></td>
                        <td><?php echo htmlspecialchars($cita['Motivo']); ?></td>
                        <td><?php echo htmlspecialchars($fecha); ?></td>
                        <td><?php echo htmlspecialchars($hora); ?></td>
                        <td>
                            <span class="badge-estado <?php echo $estadoClase; ?>">
                                <?php echo htmlspecialchars($cita['Estado']); ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            if (!empty($cita['NombreVet'])) {
                                echo htmlspecialchars($cita['NombreVet']);
                            } else {
                                echo "<span class='text-muted'>Sin asignar</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="id_cita"
                                       value="<?php echo (int)$cita['ID_Cita']; ?>">
                                <select name="id_vet" class="form-select form-select-sm me-2" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($veterinarios as $vet): ?>
                                        <option value="<?php echo (int)$vet['ID_Vet']; ?>"
                                            <?php echo ($cita['ID_Vet'] == $vet['ID_Vet']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($vet['Nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-success btn-sm">
                                    Asignar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-3">
            <a href="panel_admin.php" class="btn btn-outline-secondary">
                Volver al Panel
            </a>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>