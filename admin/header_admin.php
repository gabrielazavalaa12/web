<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : "Panel del Administrador"; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1b4332;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4" href="panel_admin.php">🐾 Mi Mascota</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Panel -->
                <li class="nav-item">
                    <a class="nav-link" href="panel_admin.php">
                        <i class="bi bi-house-door"></i> Panel
                    </a>
                </li>

                <!-- Usuarios -->
                <li class="nav-item">
                    <a class="nav-link" href="usuarios.php">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>

                <!-- Veterinarios -->
                <li class="nav-item">
                    <a class="nav-link" href="veterinarios.php">
                        <i class="bi bi-heart-pulse"></i> Veterinarios
                    </a>
                </li>

                <!-- Servicios -->
                <li class="nav-item">
                    <a class="nav-link" href="servicios.php">
                        <i class="bi bi-briefcase"></i> Servicios
                    </a>
                </li>

                <!-- Asignar Citas -->
                <li class="nav-item">
                    <a class="nav-link" href="asignar_citas.php">
                        <i class="bi bi-calendar-check"></i> Asignar Citas
                    </a>
                </li>

                <!-- Parámetros -->
                <li class="nav-item">
                    <a class="nav-link" href="parametros.php">
                        <i class="bi bi-gear"></i> Parámetros
                    </a>
                </li>
            </ul>

            <!-- Usuario y botón de salir -->
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['usuario_nombre'])): ?>
                    <span class="text-white me-3">
                        👋 Hola, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong>
                    </span>
                <?php endif; ?>

                <a href="../logout.php" class="btn btn-danger btn-sm">Salir</a>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
