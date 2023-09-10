<?php
ob_start();
?>

<?php
include("../../controladores/conexionBaseDatos.php");

// Seleccionar registros empresa
$sentencia = $conexion->prepare("SELECT * FROM `fac_empresa`");
$sentencia->execute();
$lista_empresa = $sentencia->fetchAll(PDO::FETCH_ASSOC);
$empresa = $lista_empresa[0];

// Obtener el ID de la factura de la URL
if (isset($_GET['id'])) {
    $factura_id = $_GET['id'];

    // Consultar la base de datos para obtener los detalles de la factura
    $sentencia = $conexion->prepare("SELECT * FROM `fac_factura` WHERE `fac_id` = :factura_id");
    $sentencia->bindParam(':factura_id', $factura_id);
    $sentencia->execute();
    $factura = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Consultar los productos relacionados con esta factura
    $sentenciaProductos = $conexion->prepare("SELECT * FROM `fac_detalle_fac` WHERE `fac_id` = :factura_id");
    $sentenciaProductos->bindParam(':factura_id', $factura_id);
    $sentenciaProductos->execute();
    $productosFactura = $sentenciaProductos->fetchAll(PDO::FETCH_ASSOC);

    // Consultar el nombre del cliente relacionado con la factura
    $sentenciaCliente = $conexion->prepare("SELECT cli_nombre, cli_celular, cli_numero_identificacion, cli_direccion FROM `fac_clientes` WHERE `cli_id` = :cliente_id");
    $sentenciaCliente->bindParam(':cliente_id', $factura['cli_id']);
    $sentenciaCliente->execute();
    $cliente = $sentenciaCliente->fetch(PDO::FETCH_ASSOC);
}
?>





<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
           
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 5px solid indigo;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Encabezado de la factura */
        .header {
            text-align: center;
            background-color: indigo;
            color: #fff;
            padding: 20px 0;
            border-radius: 10px 10px 0 0;
        }

        .header h1 {
            margin: 0;
        }

        /* Detalles de la factura */
        .invoice-details {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 2px solid indigo;
        }

        /* Información de la empresa */
        .company-info {
            flex: 1;
            padding: 20px;
        }

        /* Información del cliente */
        .client-info {
            flex: 1;
            padding: 20px;
            background-color: indigo;
            color: #fff;
            border-radius: 0 0 0 10px;
        }

        /* Detalles de los productos */
        .product-details {
            margin-top: 20px;
        }

        .product-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-details th, .product-details td {
            border: 1px solid indigo;
            padding: 10px;
            text-align: left;
        }

        /* Resumen de la factura */
        .invoice-summary {
            margin-top: 20px;
            background-color: indigo;
            color: #fff;
            padding: 10px 20px;
            border-radius: 0 0 10px 10px;
        }

        .invoice-summary table {
            width: 100%;
        }

        .invoice-summary td {
            padding: 5px;
            text-align: right;
        }

        .invoice-summary .total {
            font-weight: bold;
        }

        /* Pie de página de la factura */
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px 0;
            background-color: indigo;
            color: #fff;
            border-radius: 0 0 10px 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Factura No. <?php echo $factura['fac_id']; ?></h1>
        </div>

        <h5>Fecha de Emisión: <?php echo $factura['fac_fecha_emi']; ?></h5>
               
        <div class="invoice-details">
            <div class="company-info">
                <h2>Detalles de la Empresa</h2>
                <img class="" width="250" style="background-color: indigo; padding: 10px; border-radius:10px;"
                    src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/facturacion/imagenes/empresa/<?php echo $empresa['emp_logo']; ?>"
                    alt="Logo empresa"><br>
                    <?php echo $empresa['emp_nombre']; ?><br>
                RUC: <?php echo $empresa['emp_ruc']; ?><br>
                Teléfono: <?php echo $empresa['emp_telefono']; ?><br>
                Dirección: <?php echo $empresa['emp_direccion']; ?>
            </div>
            <div class="client-info">
                <h2>Datos del Cliente</h2>
                <p><?php echo $cliente['cli_nombre']; ?></p>
                <p>RUC/CC: <?php echo $cliente['cli_numero_identificacion']; ?></p>
                <p>Celular: <?php echo $cliente['cli_celular']; ?></p>
                <p>Dirección: <?php echo $cliente['cli_direccion']; ?></p>
            </div>
        </div>
        <div class="product-details">
            <h2>Detalles de los Productos</h2>
            <table>
                <thead>
                    <tr>
                              <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                                <th>IVA</th>
                                <th>Total con IVA</th>
                    </tr>
                </thead>
                <tbody>
                            <?php foreach ($productosFactura as $producto) : ?>
                                <tr>
                                    <td><?php echo $producto['prod_id']; ?></td>
                                    <td><?php echo $producto['det_cantidad']; ?></td>
                                    <td><?php echo $producto['det_precio']; ?></td>
                                    <td><?php echo $producto['det_subtotal']; ?></td>
                                    <td><?php echo $producto['det_iva']; ?></td>
                                    <td><?php echo $producto['det_total']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
            </table>
        </div>
        <div class="invoice-summary">
            <h2>Resumen de la Factura</h2>
            <p>Forma de pago: <?php echo $factura['fac_forma_pago']; ?></p>
            <table>
                <tr>
                    <td>Sub total IVA Vigente:</td>
                    <td><?php echo $factura['fac_subtotal_iva_vig']; ?></td>
                </tr>
                <tr>
                    <td>Sub total 0%:</td>
                    <td><?php echo $factura['fac_subtotal_cer']; ?></td>
                </tr>
                <tr>
                    <td>SUBTOTAL:</td>
                    <td><?php echo $factura['fac_subtotal']; ?></td>
                </tr>
                <tr>
                    <td>Valor IVA 12%:</td>
                    <td><?php echo $factura['fac_iva_doc']; ?></td>
                </tr>
                <tr>
                    <td>VALOR TOTAL:</td>
                    <td><?php echo $factura['fac_valor_tot']; ?></td>
                </tr>
            </table>
        </div>
        <div class="footer">
            Gracias por su compra.
        </div>
    </div>
</body>
</html>





<?php
$html = ob_get_clean();
require_once '../../librerias/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();
$dompdf->stream("factura_".$factura['fac_id'], array("Attachment" => true));
?>
