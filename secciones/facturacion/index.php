<?php
include("../../controladores/conexionBaseDatos.php");
session_start();
if (!isset($_SESSION['usu_usuario'])) {
    header("Location:../../index.php");
}

// Inicializar las variables de búsqueda de fechas
$fechaInicio = "";
$fechaFin = "";

// Inicializar el mensaje informativo
$mensaje = "";

// Consulta SQL sin filtro de fecha de emisión
$sentencia = $conexion->prepare("SELECT * FROM `fac_factura` ");

// Comprobar si se ha enviado un formulario de búsqueda
if (isset($_POST['buscar'])) {
    // Obtener las fechas de inicio y fin ingresadas por el usuario
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];

    if (!empty($fechaInicio) && !empty($fechaFin)) {
        // Consulta SQL con filtro de rango de fechas de emisión
        $sentencia = $conexion->prepare("SELECT * FROM `fac_factura` WHERE fac_fecha_emi BETWEEN :fecha_inicio AND :fecha_fin");
        $sentencia->bindParam(':fecha_inicio', $fechaInicio);
        $sentencia->bindParam(':fecha_fin', $fechaFin);
    } else {
        $mensaje = "Debes ingresar ambas fechas para realizar la búsqueda.";
    }
}

$sentencia->execute();
$lista_facturas = $sentencia->fetchAll(PDO::FETCH_ASSOC);

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
        <h2>Lista de Facturas</h2>
        <a title="Salir" href="../inicio/index.php" class="btn btn-sm" style="position: absolute; top: 0; right: 0; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color: white; border-radius: 50px;"><i class="fas fa-times"></i></a>

        <!-- Formulario de búsqueda por rango de fechas de emisión -->
        <form method="POST" class="mb-3 row">
            <div class="form-group col-md-6">
                <label for="fecha_inicio">Fecha de Emisión de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo $fechaInicio; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="fecha_fin">Fecha de Emisión de Fin:</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo $fechaFin; ?>">
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" name="buscar" class="btn btn-sm" style="background-color: indigo; color: white;">Buscar</button>
                <a href="index.php" class="btn btn-secondary btn-sm">Cancelar</a>
            </div>
        </form>



        <!-- Mensaje informativo -->
        <?php if (!empty($mensaje)) : ?>
            <div class="alert alert-danger mt-3">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Factura</th>
                        <th>Cliente</th>
                        <th>Fecha de Emisión</th>
                        <th>Forma de Pago</th>
                        <th>Total</th>
                        <th>Acciones</th> <!-- Nueva columna para botones -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_facturas as $factura) : ?>
                        <tr>
                            <td><?php echo $factura['fac_id']; ?></td>
                            <td><?php echo $factura['cli_id']; ?></td>
                            <td><?php echo $factura['fac_fecha_emi']; ?></td>
                            <td><?php echo $factura['fac_forma_pago']; ?></td>
                            <td><?php echo $factura['fac_valor_tot']; ?></td>
                            <td>
                                <!-- <a href="ver_factura.php?id=<?php echo $factura['fac_id']; ?>" class="btn btn-primary">Ver Factura</a> -->
                                <a href="imprimir.php?id=<?php echo $factura['fac_id']; ?>" style="background-color: indigo; color: white;" class="btn btn-sm"><i class="fas fa-print"></i></a>
                            
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../plantillas/pie.php"); ?>
