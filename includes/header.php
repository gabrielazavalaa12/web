<?php
// ==========================
// HEADER UNIVERSAL POR ROL
// ==========================

// Iniciar sesión solo si no hay una activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['rol'])) {
    header("Location: ../index.php");
    exit;
}

$rol = $_SESSION['rol'];
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';

// Colores personalizados según el rol
$colorBarra = match ($rol) {
    'Administrador' => '#146c43', // Verde oscuro
    'Editor' => '#1b8a5a',        // Verde medio
    'Consultor' => '#127e1bff',     // Azul Bootstrap
    default => '#198754',         // Verde genérico
};
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Mascota 🐾</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: <?= $colorBarra ?>;">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold text-white" href="#">
                🐶 Mi Mascota
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">

                    <?php if ($rol === 'Administrador'): ?>
                        <li class="nav-item"><a class="nav-link" href="../admin/panel_admin.php">🏠 Panel</a></li>
                        <li class="nav-item"><a class="nav-link" href="../admin/usuarios.php">👥 Usuarios</a></li>
                        <li class="nav-item"><a class="nav-link" href="../admin/veterinarios.php">🐾 Veterinarios</a></li>
                        <li class="nav-item"><a class="nav-link" href="../admin/servicios.php">💼 Servicios</a></li>
                        <li class="nav-item"><a class="nav-link" href="../admin/parametros.php">⚙️ Parámetros</a></li>
                        <li class="nav-item"><a class="nav-link" href="../admin/asignar_citas.php"> 📅 Asignar citas</a></li>

                    <?php elseif ($rol === 'Editor'): ?>
                        <li class="nav-item"><a class="nav-link" href="../editor/panel_editor.php">🏠 Panel</a></li>
                        <li class="nav-item"><a class="nav-link" href="../editor/usuarios.php">👥 Usuarios</a></li>
                        <li class="nav-item"><a class="nav-link" href="../editor/mascotas.php">🐶 Mascotas</a></li>
                        <li class="nav-item"><a class="nav-link" href="../editor/cita.php">📅 Citas</a></li>
                        <li class="nav-item"><a class="nav-link" href="../editor/servicios.php">💼 Servicios</a></li>

                   <?php elseif ($rol === 'Consultor'): ?>
    <li class="nav-item"><a class="nav-link" href="../consultor/panel_consultor.php">🏠 Panel</a></li>
    <li class="nav-item"><a class="nav-link" href="../consultor/citas.php">📅 Consultar Citas</a></li>
    <li class="nav-item"><a class="nav-link" href="../consultor/historial.php">📋 Historial Médico</a></li>
    <li class="nav-item"><a class="nav-link" href="../consultor/reportes.php">📊 Reportes Globales</a></li>
<?php endif; ?>


                    <!-- Usuario actual -->
                    <li class="nav-item ms-3">
                        <span class="badge bg-light text-dark fw-semibold px-3 py-2">
                            👋 Hola, <?= htmlspecialchars($nombre); ?>
                        </span>
                    </li>

                    <!-- Botón de salir -->
                    <li class="nav-item">
                        <a class="nav-link text-danger fw-bold ms-3" href="../logout.php">🚪 Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
