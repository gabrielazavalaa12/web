<?php
$pageTitle = "Panel del Consultor 🧾";
include("../includes/header.php");
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 p-5 text-center" style="border-radius: 20px;">
        <h2 class="text-success fw-bold mb-4">
            🐾 Bienvenido <?= htmlspecialchars($_SESSION['nombre']); ?> 🐾
        </h2>

        <p class="text-muted mb-5">
            Este es tu panel de <strong>Consultor</strong>. Desde aquí puedes visualizar citas,
            consultar historiales médicos y generar reportes globales.
        </p>

        <div class="row justify-content-center g-4">
            <!-- Consultar citas -->
            <div class="col-md-5">
                <a href="citas.php" class="btn btn-success w-100 py-4 fs-5 fw-bold shadow-sm">
                    📅 Consultar Citas
                    <p class="fs-6 fw-normal mt-2">
                        Visualiza las citas programadas por fecha o veterinario.
                    </p>
                </a>
            </div>

            <!-- Historial médico -->
            <div class="col-md-5">
                <a href="../consultor/historial.php" class="btn btn-success w-100 py-4 fs-5 fw-bold shadow-sm">
                    📋 Ver Historial Médico
                    <p class="fs-6 fw-normal mt-2">
                        Consulta el historial clínico de las mascotas registradas.
                    </p>
                </a>
            </div>

            <!-- Reportes globales -->
            <div class="col-md-10 mt-4">
                <a href="../consultor/reportes.php" class="btn btn-outline-success w-100 py-4 fs-5 fw-bold shadow">
                    📊 Reportes Globales
                    <p class="fs-6 fw-normal mt-2">
                        Genera informes sobre citas, ingresos y servicios más solicitados.
                    </p>
                </a>
            </div>
        </div>

        <div class="mt-5">
            <a href="../logout.php" class="btn btn-outline-danger px-4 py-2 fw-bold">
                🚪 Cerrar Sesión
            </a>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
