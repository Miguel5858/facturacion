<?php
include("../../controladores/conexionBaseDatos.php");
session_start();
if (!isset($_SESSION['usu_usuario'])) {
    header("Location:../../index.php");
}


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

}

include("../../plantillas/cabeceraNav.php");
?>
<style>
    .frame {
        position: relative;
        border: 1px solid #e2e8f0;
        padding: 15px;
        border-radius: 15px;
    }
</style>
<div class="container mt-4">
       
        <div class="frame mb-4">
        <h2>Detalles de la Factura</h2>
        <a title="Salir" href="../facturacion/index.php" class="btn btn-sm" style="position: absolute; top: 0; right: 0; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color: white; border-radius: 50px;"><i class="fas fa-times"></i></a>



            <!-- Información general de la factura -->
    <div class="row">
        <div class="col-md-6">
        <!-- DETALLES DE LA EMPRESA -->
        <img class="" width="250" style="background-color: indigo; padding: 10px;" src="../../imagenes/empresa/<?php echo $empresa['emp_logo']; ?>" alt="Logo empresa"> <br>
        <?php echo $empresa['emp_nombre']; ?><br>
        RUC: <?php echo $empresa['emp_ruc']; ?><br>
        Telefono: <?php echo $empresa['emp_telefono']; ?><br>
        Direccion: <?php echo $empresa['emp_direccion']; ?>      
           <!-- DETALLES DE LA EMPRESA -->

          
        </div>
        <div class="col-md-6">
            <h5>Fecha de Emisión:</h5>
            <p><?php echo $factura['fac_fecha_emi']; ?></p>

            <h5>Factura:</h5>
            <h5 class="text-danger">No.<?php echo $factura['fac_id']; ?></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h5>Cliente:</h5>
            <p><?php echo $factura['cli_id']; ?></p>
        </div>
        <div class="col-md-6">
           
        </div>
    </div>
            <!-- Detalles de los productos -->
            <h3>Productos:</h3>
            <div class="table-responsive">
            <table class="table">
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


            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-9">
                        
                    <div class="card mb-3">
                        <div class="card-body text-left">
                            <h5 class="card-title">Forma de Pago</h5>
                            <div class="col-md-6">
                           <p><?php echo $factura['fac_forma_pago']; ?></p>
                           </div>                  
                          
                        </div>
                    </div>                        
                    
                    </div>
                    

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-left">
                                <table class="table">
                                    <tr>
                                        <td class="font-weight-bold">Sub total IVA Vigente:</td>
                                        <td><span id="totalConIVA"><?php echo $factura['fac_subtotal_iva_vig']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Sub total 0%:</td>
                                        <td><span id="totalSinIVA"><?php echo $factura['fac_subtotal_cer']; ?></span></td>
                                    </tr>
                                    <!-- Nuevo subtotal general -->
                                    <tr>
                                        <td class="font-weight-bold">SUBTOTAL:</td>
                                        <td><span id="subtotalGeneral"><?php echo $factura['fac_subtotal']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Valor IVA 12%:</td>
                                        <td><span id="valorIVA12"><?php echo $factura['fac_iva_doc']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">VALOR TOTAL:</td>
                                        <td><span id="valorTotal"><?php echo $factura['fac_valor_tot']; ?></span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>


<?php include("../../plantillas/pie.php"); ?>
