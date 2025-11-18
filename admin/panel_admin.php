<?php
session_start();
include("../includes/header.php");

// Simulación de nombre de usuario administrador
$nombreAdmin = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Administrador";
?>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-5 text-center" style="border-radius: 20px;">
        <h2 class="text-success fw-bold mb-4">
            🏥 Bienvenido, <?php echo htmlspecialchars($nombreAdmin); ?> 💼
        </h2>
        <p class="text-muted mb-5">
            Panel del <strong>Administrador</strong> — Administra usuarios, veterinarios, servicios y configuraciones del sistema.
        </p>

        <!-- Contenedor de botones principales -->
        <div class="row g-4 justify-content-center">

            <!-- Usuarios y Roles -->
            <div class="col-md-5">
                <a href="usuarios.php" class="text-decoration-none">
                    <div class="p-4 rounded-4 shadow-sm h-100 bg-success text-white hover-zoom">
                        <h4 class="fw-bold mb-2">👥 Gestionar Usuarios y Roles</h4>
                        <p>Administra los usuarios registrados y sus permisos.</p>
                    </div>
                </a>
            </div>

            <!-- Veterinarios -->
            <div class="col-md-5">
                <a href="veterinarios.php" class="text-decoration-none">
                    <div class="p-4 rounded-4 shadow-sm h-100 bg-success-subtle text-success hover-zoom">
                        <h4 class="fw-bold mb-2">🐾 Gestionar Veterinarios</h4>
                        <p>Controla la información del personal veterinario.</p>
                    </div>
                </a>
            </div>

            <!-- Catálogo de Servicios -->
            <div class="col-md-5">
                <a href="servicios.php" class="text-decoration-none">
                    <div class="p-4 rounded-4 shadow-sm h-100 bg-success text-white hover-zoom">
                        <h4 class="fw-bold mb-2">💼 Catálogo de Servicios</h4>
                        <p>Administra los servicios ofrecidos por la veterinaria.</p>
                    </div>
                </a>
            </div>

            <!-- Parámetros del Sistema -->
            <div class="col-md-5">
                <a href="parametros.php" class="text-decoration-none">
                    <div class="p-4 rounded-4 shadow-sm h-100 bg-success-subtle text-success hover-zoom">
                        <h4 class="fw-bold mb-2">⚙️ Parámetros del Sistema</h4>
                        <p>Configura aspectos generales y técnicos del sistema.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-5 mt-4">
    <a href="asignar_citas.php" class="btn btn-outline-success w-100 py-4 fs-5 fw-bold shadow-sm">
        🩺 Asignar Citas a Veterinarios
        <p class="fs-6 fw-normal mt-2">Gestiona las citas creadas y asigna veterinarios disponibles.</p>
    </a>
</div>

        </div>

        <!-- Botón de cerrar sesión -->
        <div class="text-center mt-5">
            <a href="../logout.php" class="btn btn-outline-danger px-4 py-2 fw-semibold">
                🚪 Cerrar Sesión
            </a>
        </div>
    </div>
</div>

<!-- Estilos adicionales -->
<style>
    .hover-zoom {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .hover-zoom:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.2);
    }
    .bg-success-subtle {
        background-color: #d8f3dc !important;
    }
</style>

<?php include("../includes/footer.php"); ?>