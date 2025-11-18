<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'Editor') {
    header("Location: ../index.php");
    exit;
}
include("../includes/header.php");
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 p-5" 
         style="border-radius: 25px; background: linear-gradient(135deg, #f4fff8, #ffffff);">

        <h1 class="text-center text-success mb-4" style="font-weight: 800; font-size: 42px;">
            🐾 Bienvenido <?php echo htmlspecialchars($_SESSION['nombre']); ?> 🐾
        </h1>

        <p class="text-center text-muted fs-5 mb-5">
            Panel del <strong>Editor</strong> — Gestiona usuarios, mascotas, citas y servicios veterinarios.
        </p>

        <!-- Sección de Servicios primero -->
        <div class="text-center mb-5">
            <a href="servicios.php" 
               class="btn btn-lg px-5 py-3 fw-bold shadow-sm zoom-btn"
               style="background-color:#2d6a4f; color:white; border-radius:20px; font-size:24px;">
               🌿 Ver Servicios Disponibles
            </a>
        </div>

        <!-- Cuadro de opciones -->
        <div class="row g-4 justify-content-center text-center">

            <!-- Usuarios -->
            <div class="col-md-5">
                <a href="usuarios.php" class="card h-100 text-decoration-none border-0 zoom-card"
                   style="border-radius:20px; background-color:#40916c; color:white;">
                    <div class="card-body py-5">
                        <h3 class="fw-bold mb-2 fs-4">👥 Registrar / Editar Usuarios</h3>
                        <p class="text-light">Gestiona la información de los dueños o clientes.</p>
                    </div>
                </a>
            </div>

            <!-- Mascotas -->
            <div class="col-md-5">
                <a href="mascotas.php" class="card h-100 text-decoration-none border-0 zoom-card"
                   style="border-radius:20px; background-color:#52b788; color:white;">
                    <div class="card-body py-5">
                        <h3 class="fw-bold mb-2 fs-4">🐾 Registrar / Editar Mascotas</h3>
                        <p class="text-light">Administra los datos de las mascotas registradas.</p>
                    </div>
                </a>
            </div>

            <!-- Citas -->
            <div class="col-md-10">
                <a href="cita.php" class="card h-100 text-decoration-none border-0 zoom-card mt-3"
                   style="border-radius:20px; background-color:#74c69d; color:white;">
                    <div class="card-body py-5">
                        <h3 class="fw-bold mb-2 fs-4">📅 Agendar / Actualizar Citas</h3>
                        <p class="text-light">Programa y gestiona citas médicas de las mascotas.</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Botón de cierre -->
        <div class="text-center mt-5">
            <a href="../logout.php" 
               class="btn btn-outline-danger fw-bold px-5 py-2" 
               style="border-radius:15px; font-size:18px;">
               🚪 Cerrar Sesión
            </a>
        </div>
    </div>
</div>

<!-- Estilos y animaciones -->
<style>
    .zoom-card {
        transition: all 0.3s ease-in-out;
        transform: scale(1);
    }

    .zoom-card:hover {
        transform: scale(1.05);
        box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.2);
        background-color: #1b4332 !important;
    }

    .zoom-card:hover h3, 
    .zoom-card:hover p {
        color: #d8f3dc !important;
    }

    .zoom-btn {
        transition: all 0.3s ease;
    }

    .zoom-btn:hover {
        transform: scale(1.07);
        background-color: #1b4332 !important;
        box-shadow: 0px 8px 20px rgba(27, 67, 50, 0.4);
    }

    .card-body {
        transition: 0.3s;
    }

    body {
        background: #f2fdf7;
        font-family: 'Poppins', sans-serif;
    }
</style>

<?php include("../includes/footer.php"); ?>
