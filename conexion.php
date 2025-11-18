<?php
// ============================================
// Archivo: conexion.php
// Descripción: Conexión a MySQL en InfinityFree
// Base de datos: if0_40377057_clinica_veterinaria
// ============================================

// Datos de MySQL (los que ves en el panel)
$host     = "sql207.infinityfree.com";        // Nombre de host de MySQL
$user     = "if0_40377057";                  // Nombre de usuario de MySQL
$password = "Gabyzavala12";  // Contraseña de MySQL (la misma del vPanel)
$dbname   = "if0_40377057_clinica_veterinaria"; // Nombre de la base de datos

// Crear conexión
$conn = mysqli_connect($host, $user, $password, $dbname);

// Verificar conexión
if (!$conn) {
    die("❌ Error de conexión a MySQL: " . mysqli_connect_error());
}

// Forzar UTF-8 (acentos, ñ, etc.)
mysqli_set_charset($conn, "utf8mb4");

// Si quieres probar que conecta, puedes descomentar esta línea:
// echo "<p style='color: green;'>✔️ Conexión exitosa a MySQL.</p>";
?>
