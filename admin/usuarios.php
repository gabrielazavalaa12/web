<?php
include("../includes/header.php");
include("../conexion.php");

// CREAR usuario
if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO USUARIOS_LOGIN (Nombre, Correo, Password, Rol, FechaRegistro)
            VALUES (?, ?, ?, ?, GETDATE())";
    $params = array($nombre, $correo, $password, $rol);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "<div class='alert alert-success text-center mt-3'>✅ Usuario registrado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Error al registrar usuario.</div>";
    }
}

// ELIMINAR usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM USUARIOS_LOGIN WHERE ID_Usuario = ?";
    $stmt = sqlsrv_query($conn, $sql, array($id));
    if ($stmt) {
        echo "<div class='alert alert-success text-center mt-3'>🗑️ Usuario eliminado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Error al eliminar usuario.</div>";
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 p-5" style="border-radius:20px;">
        <h2 class="text-success text-center mb-4">👥 Gestión de Usuarios y Roles</h2>

        <!-- FORMULARIO DE REGISTRO -->
        <form method="POST" class="row g-3 mb-5">
            <div class="col-md-3">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <div class="col-md-2">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <div class="col-md-2">
                <select name="rol" class="form-select" required>
                    <option value="">Rol...</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Editor">Editor</option>
                    <option value="Consultor">Consultor</option>
                </select>
            </div>
            <div class="col-md-2 text-center">
                <button type="submit" name="registrar" class="btn btn-success w-100">Registrar</button>
            </div>
        </form>

        <!-- LISTA DE USUARIOS -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM USUARIOS_LOGIN ORDER BY FechaRegistro DESC";
                $stmt = sqlsrv_query($conn, $sql);
                if ($stmt) {
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr class='text-center'>
                            <td>{$row['ID_Usuario']}</td>
                            <td>{$row['Nombre']}</td>
                            <td>{$row['Correo']}</td>
                            <td>{$row['Rol']}</td>
                            <td>" . $row['FechaRegistro']->format('Y-m-d') . "</td>
                            <td>
                                <a href='usuarios.php?eliminar={$row['ID_Usuario']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar este usuario?\");'>Eliminar</a>
                            </td>
                        </tr>";
                    }
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="panel_admin.php" class="btn btn-outline-success">⬅️ Volver al Panel</a>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>