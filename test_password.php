<?php
include("conexion.php");

// Datos del usuario que quieres probar
$correo = 'admin@veterinaria.com';
$passwordIngresada = 'admin123';

// Buscar en la base de datos con consulta preparada
$sql = "SELECT Password FROM USUARIOS_LOGIN WHERE Correo = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $hash = $row['Password'];

    // Limpiar espacios por si acaso
    $hash = trim((string)$hash);

    echo "<h3>🔍 Hash recuperado:</h3>";
    echo "<pre>$hash</pre>";

    if (password_verify($passwordIngresada, $hash)) {
        echo "<p style='color:green;font-weight:bold;'>✅ Coincide la contraseña correctamente.</p>";
    } else {
        echo "<p style='color:red;font-weight:bold;'>❌ No coincide la contraseña.</p>";
    }
} else {
    echo "<p style='color:red;'>⚠ No se encontró el correo en la base de datos.</p>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>