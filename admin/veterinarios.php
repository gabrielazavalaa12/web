<?php
include("../includes/header.php");
include("../conexion.php");

// ---------------------------------------
// REGISTRAR VETERINARIO
// ---------------------------------------
if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $especialidad = $_POST['especialidad'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $sql = "INSERT INTO VET (Nombre, Especialidad, Telefono, Email)
            VALUES (?, ?, ?, ?)";
    $params = array($nombre, $especialidad, $telefono, $correo);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "<div class='alert alert-success text-center mt-3'>✅ Veterinario registrado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Error al registrar veterinario.</div>";
        echo "<pre style='color:red; text-align:center;'>";
        print_r(sqlsrv_errors(SQLSRV_ERR_ERRORS));
        echo "</pre>";
    }
}

// ---------------------------------------
// ELIMINAR VETERINARIO
// ---------------------------------------
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM VET WHERE ID_Vet = ?";
    $stmt = sqlsrv_query($conn, $sql, array($id));

    if ($stmt) {
        echo "<div class='alert alert-success text-center mt-3'>🗑️ Veterinario eliminado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Error al eliminar veterinario.</div>";
    }
}

// ---------------------------------------
// ACTUALIZAR VETERINARIO
// ---------------------------------------
if (isset($_POST['actualizar'])) {
    $id = $_POST['id_vet'];
    $nombre = $_POST['nombre'];
    $especialidad = $_POST['especialidad'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $sql = "UPDATE VET 
            SET Nombre = ?, Especialidad = ?, Telefono = ?, Email = ? 
            WHERE ID_Vet = ?";
    $params = array($nombre, $especialidad, $telefono, $correo, $id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "<div class='alert alert-success text-center mt-3'>✏️ Veterinario actualizado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Error al actualizar veterinario.</div>";
        echo "<pre style='color:red; text-align:center;'>";
        print_r(sqlsrv_errors(SQLSRV_ERR_ERRORS));
        echo "</pre>";
    }
}
?>

<!-- ===================================== -->
<!-- 🩺 DISEÑO DE LA PÁGINA -->
<!-- ===================================== -->
<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 p-5" style="border-radius:20px;">
        <h2 class="text-success text-center mb-4">🐾 Gestión de Veterinarios</h2>

        <!-- FORMULARIO DE REGISTRO -->
        <form method="POST" class="row g-3 mb-5 justify-content-center">
            <div class="col-md-3">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="especialidad" class="form-control" placeholder="Especialidad" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="telefono" class="form-control" placeholder="Teléfono" required>
            </div>
            <div class="col-md-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required>
            </div>
            <div class="col-md-1 text-center">
                <button type="submit" name="registrar" class="btn btn-success w-100">+</button>
            </div>
        </form>

        <!-- TABLA DE VETERINARIOS -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM VET ORDER BY ID_Vet DESC";
                $stmt = sqlsrv_query($conn, $sql);

                if ($stmt) {
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr class='text-center'>
                            <td>{$row['ID_Vet']}</td>
                            <td>{$row['Nombre']}</td>
                            <td>{$row['Especialidad']}</td>
                            <td>{$row['Telefono']}</td>
                            <td>{$row['Email']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editarModal{$row['ID_Vet']}'>Editar</button>
                                <a href='veterinarios.php?eliminar={$row['ID_Vet']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar este veterinario?\");'>Eliminar</a>
                            </td>
                        </tr>";

                        // MODAL DE EDICIÓN
                        echo "
                        <div class='modal fade' id='editarModal{$row['ID_Vet']}' tabindex='-1'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-header bg-success text-white'>
                                        <h5 class='modal-title'>Editar Veterinario</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                    </div>
                                    <form method='POST'>
                                        <div class='modal-body'>
                                            <input type='hidden' name='id_vet' value='{$row['ID_Vet']}'>
                                            <label class='form-label'>Nombre</label>
                                            <input type='text' name='nombre' class='form-control mb-2' value='{$row['Nombre']}' required>
                                            <label class='form-label'>Especialidad</label>
                                            <input type='text' name='especialidad' class='form-control mb-2' value='{$row['Especialidad']}' required>
                                            <label class='form-label'>Teléfono</label>
                                            <input type='text' name='telefono' class='form-control mb-2' value='{$row['Telefono']}' required>
                                            <label class='form-label'>Correo</label>
                                            <input type='email' name='correo' class='form-control mb-2' value='{$row['Email']}' required>
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='submit' name='actualizar' class='btn btn-success'>Guardar Cambios</button>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center text-muted'>⚠️ No hay veterinarios registrados.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

        <!-- BOTÓN VOLVER -->
        <div class="text-center mt-4">
            <a href="panel_admin.php" class="btn btn-outline-success">⬅️ Volver al Panel</a>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>