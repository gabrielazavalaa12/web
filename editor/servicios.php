<?php
include("../includes/header.php");
include("../conexion.php");
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 p-5" style="border-radius: 20px;">
        <h2 class="text-success text-center mb-4">💼 Servicios Disponibles</h2>
        <p class="text-center text-muted mb-5">
            Consulta los servicios y tratamientos que ofrece nuestra veterinaria 🐾
        </p>

        <div class="row g-4 justify-content-center">
            <?php
            // Consultamos todos los servicios actuales desde la BD
            $sql = "SELECT * FROM SERVICIO ORDER BY ID_Servicio ASC";
            $stmt = sqlsrv_query($conn, $sql);

            if ($stmt && sqlsrv_has_rows($stmt)) {
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $nombre = htmlspecialchars($row['Nombre']);
                    $descripcion = htmlspecialchars($row['Descripcion']);
                    $precio = number_format($row['Precio'], 2);
                    
                    echo "
                    <div class='col-md-4'>
                        <div class='card h-100 shadow-sm border-0 text-center p-4 hover-card'>
                            <div class='card-body'>
                                <h5 class='fw-bold text-success mb-2'>$nombre</h5>
                                <p class='text-muted mb-3'>$descripcion</p>
                                <p class='fw-bold text-dark'>$$precio MXN</p>
                            </div>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "
                <div class='text-center text-muted py-4'>
                    ⚠️ No hay servicios registrados en este momento.  
                    <br>El administrador puede agregar nuevos servicios desde su panel.
                </div>
                ";
            }
            ?>
        </div>

        <div class="text-center mt-5">
            <a href="panel_editor.php" class="btn btn-outline-success px-4">
                ⬅️ Volver al Panel
            </a>
        </div>
    </div>
</div>

<!-- Estilos -->
<style>
    .hover-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 15px;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<?php include("../includes/footer.php"); ?>