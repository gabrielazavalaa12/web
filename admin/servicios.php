<?php
include("../includes/header.php");
include("../conexion.php");

// --- AGREGAR SERVICIO ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] == "agregar") {
    $nombre      = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio      = $_POST["precio"];

    if (!empty($nombre) && !empty($descripcion) && !empty($precio)) {
        $sql    = "INSERT INTO SERVICIO (Nombre, Descripcion, Precio) VALUES (?, ?, ?)";
        $params = array($nombre, $descripcion, $precio);
        $stmt   = sqlsrv_query($conn, $sql, $params);

        if ($stmt) {
            echo "<div class='alert alert-success text-center'>✅ Servicio agregado correctamente.</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>❌ Error al agregar servicio.</div>";
            print_r(sqlsrv_errors());
        }
    } else {
        echo "<div class='alert alert-warning text-center'>⚠️ Completa todos los campos.</div>";
    }
}

// --- ELIMINAR SERVICIO ---
if (isset($_GET["eliminar"])) {
    $id  = $_GET["eliminar"];
    $sql = "DELETE FROM SERVICIO WHERE ID_Servicio = ?";
    $stmt = sqlsrv_query($conn, $sql, array($id));

    if ($stmt) {
        echo "<div class='alert alert-success text-center'>🗑️ Servicio eliminado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>❌ No se pudo eliminar el servicio.</div>";
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 p-5" style="border-radius: 20px;">
        <h2 class="text-success text-center mb-4">💼 Gestión de Servicios</h2>
        <p class="text-center text-muted mb-4">Administra los servicios ofrecidos por la veterinaria.</p>

        <!-- Formulario para agregar servicios -->
        <form method="POST" class="row g-3 mb-4 justify-content-center">
            <input type="hidden" name="accion" value="agregar">

            <div class="col-md-3">
                <input type="text" class="form-control" name="nombre" placeholder="Nombre del servicio" required>
            </div>

            <div class="col-md-5">
                <input type="text" class="form-control" name="descripcion" placeholder="Descripción" required>
            </div>

            <div class="col-md-2">
                <input type="number" class="form-control" name="precio" placeholder="Precio" step="0.01" required>
            </div>

            <div class="col-md-2 text-center">
                <button type="submit" class="btn btn-success w-100">➕ Agregar</button>
            </div>
        </form>

        <!-- Tabla de servicios -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql  = "SELECT * FROM SERVICIO ORDER BY ID_Servicio ASC";
                    $stmt = sqlsrv_query($conn, $sql);

                    if ($stmt && sqlsrv_has_rows($stmt)) {
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            echo "
                            <tr class='text-center'>
                                <td>{$row['ID_Servicio']}</td>
                                <td>{$row['Nombre']}</td>
                                <td>{$row['Descripcion']}</td>
                                <td>$" . number_format($row['Precio'], 2) . "</td>
                                <td>
                                    <a href='?eliminar={$row['ID_Servicio']}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Eliminar este servicio?')\">🗑️ Eliminar</a>
                                </td>
                            </tr>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-muted'>⚠️ No hay servicios registrados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="panel_admin.php" class="btn btn-outline-success px-4">⬅️ Volver al Panel</a>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
