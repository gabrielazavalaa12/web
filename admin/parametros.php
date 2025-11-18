<?php
include("../includes/header.php");
include("../conexion.php");

// Leer los parámetros actuales
$sql = "SELECT TOP 1 * FROM PARAMETROS";
$stmt = sqlsrv_query($conn, $sql);
$config = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Actualizar si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $horario = $_POST["horario"];

    $sql_update = "UPDATE PARAMETROS 
                   SET NombreClinica=?, Direccion=?, Telefono=?, Correo=?, Horario=?";
    $params = [$nombre, $direccion, $telefono, $correo, $horario];
    $update = sqlsrv_query($conn, $sql_update, $params);

    if ($update) {
        echo '<div class="alert alert-success text-center mt-3">✅ Parámetros actualizados correctamente.</div>';
        $config = ['NombreClinica'=>$nombre, 'Direccion'=>$direccion, 'Telefono'=>$telefono, 'Correo'=>$correo, 'Horario'=>$horario];
    } else {
        echo '<div class="alert alert-danger text-center mt-3">❌ Error al actualizar los parámetros.</div>';
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 p-5" style="border-radius: 20px; max-width:800px; margin:auto;">
        <h2 class="text-center text-success mb-4">⚙️ Configuración del Sistema</h2>
        <p class="text-center text-muted">Edita los datos generales de la veterinaria.</p>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre de la Clínica</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($config['NombreClinica']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($config['Direccion']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($config['Telefono']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($config['Correo']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Horario de Atención</label>
                <input type="text" name="horario" class="form-control" value="<?php echo htmlspecialchars($config['Horario']); ?>" required>
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-success px-5">💾 Guardar Cambios</button>
                <a href="panel_admin.php" class="btn btn-outline-secondary ms-2">⬅️ Volver al Panel</a>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>