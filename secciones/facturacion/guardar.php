<?php
include("../../controladores/conexionBaseDatos.php");

date_default_timezone_set('America/Guayaquil');

session_start();
if (!isset($_SESSION['usu_usuario'])) {
    header("Location: ../../index.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Capturar valores del formulario
    $cliente = $_POST['cliente'];
    $hiddenFechaHora = $_POST['hiddenFechaHora'];
    $formaPago = $_POST['formaPago'];
    $totalConIVA12 = $_POST['subtotalConIVA12'];
    $totalConIVA0 = $_POST['subtotalConIVA0'];
    $subtotalGeneral = $_POST['subtotalGeneral'];
    $totalIva12 = $_POST['totalIva12'];
    $totalFinal = $_POST['totalFinal'];

    try {
        // Iniciar una transacción
        $conexion->beginTransaction();

        // Preparar la consulta SQL para insertar datos de la factura en la tabla 'fac_factura'
        $sql = "INSERT INTO fac_factura (cli_id, fac_fecha_aut, fac_fecha_emi, fac_forma_pago, fac_subtotal_iva_vig, fac_subtotal_cer, fac_subtotal, fac_iva_doc, fac_valor_tot)
                VALUES (:cliente, NOW(), :hiddenFechaHora, :formaPago, :totalConIVA12, :totalConIVA0, :subtotalGeneral, :totalIva12, :totalFinal)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->bindParam(':hiddenFechaHora', $hiddenFechaHora);
        $stmt->bindParam(':formaPago', $formaPago);
        $stmt->bindParam(':totalConIVA12', $totalConIVA12);
        $stmt->bindParam(':totalConIVA0', $totalConIVA0);
        $stmt->bindParam(':subtotalGeneral', $subtotalGeneral);
        $stmt->bindParam(':totalIva12', $totalIva12);
        $stmt->bindParam(':totalFinal', $totalFinal);

        $stmt->execute();

        // Obtener el ID de la factura recién insertada
        $facturaId = $conexion->lastInsertId();

        // Procesar los datos de los productos
        $totalProductos = count($_POST['productosSeleccionados']);
        for ($i = 0; $i < $totalProductos; $i++) {
            $producto = $_POST['productosSeleccionados'][$i]['prod_id'];
            $cantidad = $_POST['productosSeleccionados'][$i]['cantidad'];
            $precio = $_POST['productosSeleccionados'][$i]['prod_precio'];
            $subtotal = $_POST['productosSeleccionados'][$i]['subtotal'];
            $iva = $_POST['productosSeleccionados'][$i]['prod_iva'];
            $subtotalConIVA = $_POST['productosSeleccionados'][$i]['totalConIva'];

            // Preparar la consulta SQL para insertar datos del producto en la tabla 'fac_detalle_fac'
            $sqlProducto = "INSERT INTO fac_detalle_fac (fac_id, prod_id, det_cantidad, det_precio, det_subtotal, det_iva, det_total)
                    VALUES (:facturaId, :producto, :cantidad, :precio, :subtotal, :iva, :subtotalConIVA)";

            $stmtProducto = $conexion->prepare($sqlProducto);
            $stmtProducto->bindParam(':facturaId', $facturaId);
            $stmtProducto->bindParam(':producto', $producto);
            $stmtProducto->bindParam(':cantidad', $cantidad);
            $stmtProducto->bindParam(':precio', $precio);
            $stmtProducto->bindParam(':subtotal', $subtotal);
            $stmtProducto->bindParam(':iva', $iva);
            $stmtProducto->bindParam(':subtotalConIVA', $subtotalConIVA);

            $stmtProducto->execute();
        }

        $conexion->commit();
        echo "Factura y productos guardados correctamente.";
        // Redirige al usuario a la página de creación después de guardar la factura
header("Location: crear.php?factura_guardada=true");
exit();
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Error al guardar la factura: " . $e->getMessage();
    }
    $conexion = null; // Cerrar la conexión
    // Redirige al usuario nuevamente a esta página

}
?>
