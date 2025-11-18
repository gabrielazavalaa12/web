<?php
$pageTitle = "Consultar Citas 🗓️";
include("../includes/header.php");
include("../conexion.php");
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg p-4" style="border-radius: 15px;">
        <h2 class="text-center text-success mb-4">🗓️ Citas Agendadas</h2>
        <p class="text-center text-muted mb-4">
            Visualiza todas las citas registradas en el sistema.  
            (Solo lectura, no editable por el consultor)
        </p>

        <!-- Filtros -->
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filtrar por Dueño</label>
                    <select name="usuario" class="form-select">
                        <option value="">Todos</option>
                        <?php
                        $sql_usuarios = "SELECT ID_Usuario, Nombre FROM USUARIO ORDER BY Nombre";
                        $stmt_usuarios = sqlsrv_query($conn, $sql_usuarios);
                        while ($row = sqlsrv_fetch_array($stmt_usuarios, SQLSRV_FETCH_ASSOC)) {
                            $selected = (isset($_GET['usuario']) && $_GET['usuario'] == $row['ID_Usuario']) ? 'selected' : '';
                            echo "<option value='{$row['ID_Usuario']}' $selected>{$row['Nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filtrar por Servicio</label>
                    <select name="motivo" class="form-select">
                        <option value="">Todos</option>
                        <option value="Vacunación">Vacunación</option>
                        <option value="Desparasitación">Desparasitación</option>
                        <option value="Cirugía">Cirugía</option>
                        <option value="Consulta general">Consulta general</option>
                        <option value="Esterilización">Esterilización</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100 fw-bold">🔍 Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Tabla de citas -->
        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>ID</th>
                        <th>Dueño</th>
                        <th>Mascota</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Servicio</th>
                        <th>Veterinario</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "
                        SELECT 
                            C.ID_Cita,
                            U.Nombre AS Dueño,
                            M.Nombre AS Mascota,
                            C.Fecha,
                            C.Hora,
                            C.Motivo AS Servicio,
                            V.Nombre AS Veterinario,
                            C.Estado
                        FROM CITA C
                        INNER JOIN USUARIO U ON C.ID_Usuario = U.ID_Usuario
                        INNER JOIN MASCOTA M ON C.ID_Mascota = M.ID_Mascota
                        LEFT JOIN VET V ON C.ID_Vet = V.ID_Vet
                    ";

                    $params = [];
                    $where = [];

                    if (!empty($_GET['usuario'])) {
                        $where[] = "U.ID_Usuario = ?";
                        $params[] = $_GET['usuario'];
                    }

                    if (!empty($_GET['motivo'])) {
                        $where[] = "C.Motivo = ?";
                        $params[] = $_GET['motivo'];
                    }

                    if ($where) {
                        $query .= " WHERE " . implode(" AND ", $where);
                    }

                    $query .= " ORDER BY C.Fecha DESC";

                    $stmt = sqlsrv_query($conn, $query, $params);

                    if ($stmt === false) {
                        echo "<tr><td colspan='8' class='text-center text-danger'>❌ Error en la consulta.</td></tr>";
                    } else {
                        $hayDatos = false;
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            $hayDatos = true;
                            $fecha = $row['Fecha'] ? $row['Fecha']->format('Y-m-d') : '';
                            $hora = $row['Hora'] ? $row['Hora']->format('H:i') : '';
                            $estadoColor = ($row['Estado'] == 'Completada') ? 'text-success' : (($row['Estado'] == 'Pendiente') ? 'text-warning' : 'text-secondary');

                            echo "
                                <tr class='text-center'>
                                    <td>{$row['ID_Cita']}</td>
                                    <td>{$row['Dueño']}</td>
                                    <td>{$row['Mascota']}</td>
                                    <td>{$fecha}</td>
                                    <td>{$hora}</td>
                                    <td>{$row['Servicio']}</td>
                                    <td>{$row['Veterinario']}</td>
                                    <td class='{$estadoColor}'><strong>{$row['Estado']}</strong></td>
                                </tr>
                            ";
                        }

                        if (!$hayDatos) {
                            echo "<tr><td colspan='8' class='text-center text-muted'>⚕️ No se encontraron citas registradas.</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>