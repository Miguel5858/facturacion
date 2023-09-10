
<?php 
include("../../controladores/conexionBaseDatos.php");

session_start();
if(!isset($_SESSION['usu_usuario'])){
  header("Location:../../index.php");  
}


// Borrar dicho registro con el ID correspondiente
if(isset($_GET['txtID'])){
    
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";
    $sentencia=$conexion->prepare("DELETE FROM fac_productos WHERE prod_id=:prod_id ");  
    $sentencia->bindParam(":prod_id", $txtID);  
    $sentencia->execute();

}

// Seleccionar registros
$sentencia=$conexion->prepare("SELECT * FROM `fac_productos`");
$sentencia->execute();
$lista_productos=$sentencia->fetchAll(PDO::FETCH_ASSOC);


include("../../plantillas/cabeceraNav.php");

?>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <a title="Agregar un Producto" href="crear.php" class="btn btn-sm" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" > <i class="fas fa-plus"></i> </a>
    PRODUCTOS
    <a title="Salir" href="../mantenimientos/index.php" class="btn btn-sm" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" > <i class="fas fa-times"></i>
    </a>
    </div>
    <div class="card-body">

    <div class="table-responsive-sm">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Codigo</th>
                    <th scope="col">Precio</th>
                    <th scope="col">IVA</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($lista_productos as $registros) { ?>
                <tr class="">
                    <td><?php echo $registros['prod_id']; ?></td>
                    <td><?php echo $registros['prod_nombre']; ?></td>
                    <td><?php echo $registros['prod_codigo']; ?></td>
                    <td>$<?php echo $registros['prod_precio']; ?></td>
                    <td>%<?php echo $registros['prod_iva']; ?></td>
                    <td>

                    <a name="" title="Editar" id="" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" class="btn btn-sm" href="editar.php?txtID=<?php echo $registros['prod_id']; ?>" role="button"><i class="fa fa-pen"></i></a>

                    <a name="" title="Eliminar" id="" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" class="btn btn-sm" href="index.php?txtID=<?php echo $registros['prod_id']; ?>" role="button" onclick="return confirm('Estás seguro que deseas eliminar el registro?');"><i class="fa fa-trash"></i></a>
                        
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
        
    </div>
</div>

<?php include("../../plantillas/pie.php"); ?>