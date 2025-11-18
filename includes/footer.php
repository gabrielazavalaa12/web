<?php
// Conexión con la base de datos para leer parámetros del sistema
include_once(__DIR__ . "/../conexion.php");

// Consultar los parámetros (nombre, dirección, horario)
$sql_footer  = "SELECT TOP 1 * FROM PARAMETROS";
$stmt_footer = sqlsrv_query($conn, $sql_footer);

$config_footer = null;
if ($stmt_footer !== false) {
    $config_footer = sqlsrv_fetch_array($stmt_footer, SQLSRV_FETCH_ASSOC);
}
?>

<footer class="text-center text-white mt-5" style="background-color: #146c43; padding: 25px 0;">
    <?php if ($config_footer): ?>
        <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($config_footer['NombreClinica']); ?></h5>
        <p class="mb-1"><?php echo htmlspecialchars($config_footer['Direccion']); ?></p>
        <p class="mb-1">
            📞 <?php echo htmlspecialchars($config_footer['Telefono']); ?> |
            📧 <?php echo htmlspecialchars($config_footer['Correo']); ?>
        </p>
        <p class="mb-2">🕓 <?php echo htmlspecialchars($config_footer['Horario']); ?></p>
    <?php else: ?>
        <h5 class="fw-bold mb-2">Veterinaria Mi Mascota</h5>
        <p class="mb-1">Av. Central #123, Monterrey, NL</p>
        <p class="mb-1">📞 8123456789 | 📧 contacto@mimascota.com</p>
        <p class="mb-2">🕓 Lunes a Sábado de 9:00 a 18:00</p>
    <?php endif; ?>

    <hr class="mx-auto" style="width: 80%; border-color: rgba(255,255,255,0.3);">
    <p class="mb-0">
        © <?php echo date("Y"); ?>
        <?php echo $config_footer ? htmlspecialchars($config_footer['NombreClinica']) : 'Veterinaria Mi Mascota'; ?> |
        Todos los derechos reservados 🐾
    </p>
</footer>
