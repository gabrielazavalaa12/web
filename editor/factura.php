<?php
require_once("../conexion.php");

// 1. Validar parámetro
if (!isset($_GET['id_cita']) || !is_numeric($_GET['id_cita'])) {
    die("ID de cita no válido.");
}
$id_cita = (int)$_GET['id_cita'];

// 2. Obtener datos de la cita + usuario + mascota + servicio
//   OJO: unimos SERVICIO por el nombre del servicio (Motivo)
$sql = "
    SELECT 
        c.ID_Cita,
        c.Fecha,
        c.Hora,
        c.Motivo,
        u.Nombre            AS NombreDueno,
        u.CorreoElectronico AS CorreoDueno,
        m.Nombre            AS NombreMascota,
        s.Nombre            AS NombreServicio,
        s.Descripcion       AS DescripcionServicio,
        s.Precio            AS PrecioServicio
    FROM CITA c
    INNER JOIN USUARIO  u ON c.ID_Usuario = u.ID_Usuario
    INNER JOIN MASCOTA  m ON c.ID_Mascota = m.ID_Mascota
    INNER JOIN SERVICIO s ON c.Motivo = s.Nombre
    WHERE c.ID_Cita = ?
";

$params = array($id_cita);
$stmt   = sqlsrv_query($conn, $sql, $params);

if (!$stmt) {
    die("Error al obtener la cita. " . print_r(sqlsrv_errors(), true));
}

$cita = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$cita) {
    die("No se encontró la cita solicitada.");
}

// 3. Calcular totales
$precio   = (float)$cita['PrecioServicio'];
$subtotal = $precio;
$iva      = round($subtotal * 0.16, 2);
$total    = $subtotal + $iva;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Cita #<?php echo htmlspecialchars($cita['ID_Cita']); ?> 🧾</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: "Poppins", sans-serif;
        }
        .factura-container {
            max-width: 900px;
            margin: 40px auto 60px auto;
        }
        .factura-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            padding: 30px 35px 35px 35px;
        }
        .factura-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .factura-header h2 {
            font-size: 26px;
            color: #1b4332;
            margin-bottom: 5px;
        }
        .factura-header p {
            color: #6c757d;
            font-size: 14px;
        }
        .section-title {
            font-weight: 700;
            color: #2d6a4f;
            margin-top: 10px;
            margin-bottom: 8px;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-block {
            background: #f8fafc;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .info-block span.label {
            font-weight: 600;
            color: #495057;
        }
        .resaltado {
            font-weight: 600;
            color: #1b4332;
        }
        .totales-card {
            background: #e9f7ef;
            border-radius: 16px;
            padding: 18px 20px;
            margin-top: 15px;
            font-size: 14px;
        }
        .totales-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .totales-row.total {
            font-weight: 700;
            font-size: 16px;
            border-top: 1px dashed #94d2bd;
            padding-top: 8px;
            margin-top: 10px;
        }
        .metodo-pago-card {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #edf2f4;
        }
        label {
            font-weight: 600;
            font-size: 14px;
            color: #1b4332;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 10px;
            border: 1.5px solid #b7e4c7;
            font-size: 14px;
            transition: 0.3s;
        }
        select:focus {
            outline: none;
            border-color: #2d6a4f;
            box-shadow: 0 0 5px rgba(64,145,108,0.3);
        }
        .btn-siguiente {
            margin-top: 22px;
            background-color: #2d6a4f;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 10px 26px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-siguiente:hover:not(:disabled) {
            background-color: #24533c;
            transform: translateY(-1px);
        }
        .btn-siguiente:disabled {
            opacity: 0.6;
            cursor: default;
        }
        #mensaje-final {
            margin-top: 18px;
            display: none;
            background: #d8f3dc;
            color: #1b4332;
            padding: 12px 15px;
            border-radius: 12px;
            font-size: 14px;
            text-align: center;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .factura-card {
                padding: 22px 18px;
            }
        }
    </style>
</head>
<body>
<?php include("../includes/header.php"); ?>

<div class="factura-container">
    <div class="factura-card">
        <div class="factura-header">
            <h2>Factura de Cita #<?php echo htmlspecialchars($cita['ID_Cita']); ?></h2>
            <p>Revisa los detalles de tu cita y el monto a pagar.</p>
        </div>

        <!-- Datos de la cita -->
        <div class="section-title">Datos de la cita</div>
        <div class="info-block">
            <div><span class="label">Fecha:</span> <?php echo $cita['Fecha'] instanceof DateTime ? $cita['Fecha']->format('d/m/Y') : htmlspecialchars($cita['Fecha']); ?></div>
            <div><span class="label">Hora:</span> <?php echo $cita['Hora'] instanceof DateTime ? $cita['Hora']->format('H:i') : htmlspecialchars($cita['Hora']); ?></div>
        </div>

        <!-- Paciente / dueño -->
        <div class="section-title">Paciente y dueño</div>
        <div class="info-block">
            <div><span class="label">Mascota:</span> <span class="resaltado"><?php echo htmlspecialchars($cita['NombreMascota']); ?></span></div>
            <div><span class="label">Dueño:</span> <?php echo htmlspecialchars($cita['NombreDueno']); ?></div>
            <div><span class="label">Correo de contacto:</span> <?php echo htmlspecialchars($cita['CorreoDueno']); ?></div>
        </div>

        <!-- Servicio -->
        <div class="section-title">Servicio solicitado</div>
        <div class="info-block">
            <div><span class="label">Servicio:</span> <span class="resaltado"><?php echo htmlspecialchars($cita['NombreServicio']); ?></span></div>
            <div><span class="label">Descripción:</span> <?php echo htmlspecialchars($cita['DescripcionServicio']); ?></div>
            <div><span class="label">Precio catálogo:</span> $<?php echo number_format($precio, 2); ?> MXN</div>
        </div>

        <!-- Totales -->
        <div class="totales-card">
            <div class="totales-row">
                <span>Subtotal servicio</span>
                <span>$<?php echo number_format($subtotal, 2); ?> MXN</span>
            </div>
            <div class="totales-row">
                <span>IVA (16%)</span>
                <span>$<?php echo number_format($iva, 2); ?> MXN</span>
            </div>
            <div class="totales-row total">
                <span>Total a pagar</span>
                <span>$<?php echo number_format($total, 2); ?> MXN</span>
            </div>
        </div>

        <!-- Método de pago -->
        <div class="metodo-pago-card">
            <label for="metodo_pago">Método de pago</label>
            <select id="metodo_pago" name="metodo_pago">
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>

            <button type="button" id="btnSiguiente" class="btn-siguiente">Siguiente</button>

            <div id="mensaje-final"></div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnSiguiente');
    const select = document.getElementById('metodo_pago');
    const mensajeDiv = document.getElementById('mensaje-final');

    if (btn && select && mensajeDiv) {
        btn.addEventListener('click', function () {
            // Evitar múltiples clics
            if (btn.disabled) return;

            const metodoValor = select.value; // efectivo / tarjeta / transferencia
            let metodoTexto = '';

            switch (metodoValor) {
                case 'efectivo':
                    metodoTexto = 'efectivo';
                    break;
                case 'tarjeta':
                    metodoTexto = 'tarjeta';
                    break;
                case 'transferencia':
                    metodoTexto = 'transferencia';
                    break;
                default:
                    metodoTexto = metodoValor;
            }

            mensajeDiv.textContent =
                'Te esperamos en tu próxima cita agendada, recuerda que tu pago en ' +
                metodoTexto +
                ' se realiza en la sucursal.';

            mensajeDiv.style.display = 'block';

            // Deshabilitar el botón y cambiarle el texto
            btn.disabled = true;
            btn.textContent = 'Mensaje enviado';
            btn.style.opacity = '0.6';
            btn.style.cursor = 'default';
        });
    }
});
</script>

</body>
</html>