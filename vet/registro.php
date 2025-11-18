<?php
include("../conexion.php"); // Conectamos a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $especialidad = $_POST['especialidad'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    // Validar que los campos no estén vacíos
    if (!empty($nombre) && !empty($especialidad) && !empty($telefono) && !empty($email)) {
        // Consulta para insertar en la tabla VET
        $sql = "INSERT INTO VET (Nombre, Especialidad, Telefono, Email)
                VALUES (?, ?, ?, ?)";

        // Preparar la consulta
        $params = array($nombre, $especialidad, $telefono, $email);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt) {
            echo "<div style='color:green; text-align:center; margin-top:20px;'>
                    ✔️ Veterinario registrado correctamente.
                  </div>";
        } else {
            echo "<div style='color:red; text-align:center; margin-top:20px;'>
                    ❌ Error al registrar veterinario.
                  </div>";
            print_r(sqlsrv_errors());
        }
    } else {
        echo "<div style='color:red; text-align:center; margin-top:20px;'>
                ⚠️ Por favor completa todos los campos.
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Veterinario 🩺</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <?php include("../includes/header.php"); ?>

    <div class="form-container">
        <h2>Registrar Veterinario 🩺</h2>
        <form action="registro.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" placeholder="Ej. Dr. José Martínez" required>

            <label for="especialidad">Especialidad:</label>
            <input type="text" name="especialidad" placeholder="Ej. Cirugía, Medicina general" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" placeholder="Ej. 8123456789" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" placeholder="Ej. drjose@clinicavet.com" required>

            <button type="submit">Registrar Veterinario</button>
        </form>
    </div>

    <?php include("../includes/footer.php"); ?>
</body>
</html>
