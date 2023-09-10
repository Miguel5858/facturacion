<?php 
include("../../controladores/conexionBaseDatos.php");

session_start();
if(!isset($_SESSION['usu_usuario'])){
  header("Location:../../index.php");  
}

// Borrar dicho registro con el id correspondiente
if(isset($_GET['txtID'])){

  
$txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";
// Borrar cuando tiene imagen
$sentencia=$conexion->prepare("SELECT emp_logo FROM fac_empresa WHERE emp_id=:emp_id");  
$sentencia->bindParam(":emp_id", $txtID);  
$sentencia->execute();
$registro_logo=$sentencia->fetch(PDO::FETCH_LAZY);

if(isset($registro_logo["emp_logo"])){
    if(file_exists("../../imagenes/empresa/".$registro_logo["emp_logo"])){
        unlink("../../imagenes/empresa/".$registro_logo["emp_logo"]);

    }
}
// Borrar cuando tiene imagen   
    
  $sentencia=$conexion->prepare("DELETE FROM fac_empresa WHERE emp_id=:emp_id");  
  $sentencia->bindParam(":emp_id", $txtID);  
  $sentencia->execute();

}


// Seleccionar registros de la base de datos y mostralos en un foreach
$sentencia=$conexion->prepare("SELECT * FROM `fac_empresa`");
$sentencia->execute();
$lista_empresa=$sentencia->fetchAll(PDO::FETCH_ASSOC);



?>

<?php include("../../plantillas/cabeceraNav.php"); ?>








 <div class="card mt-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <!-- <a class="btn btn-primary btn-sm" href="crear.php">Nuevo Registro</a> -->
    EMPRESA
    <a title="Salir" href="../mantenimientos/index.php" class="btn btn-sm" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" > <i class="fas fa-times"></i>
 </a>
  </div>
  <div class="card-body">
 
  <div class="table-responsive-sm">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Logo</th>
          <th scope="col">Nombres</th>
          <th scope="col">Telefono</th>
          <th scope="col">Direccion</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($lista_empresa as $registros) { ?>
        <tr>
          <td><?php echo $registros['emp_id']; ?></td>
          <td>
          <img class="img-thumbnail" width="50" src="../../imagenes/empresa/<?php echo $registros['emp_logo']; ?>" alt="">
          </td>
          <td><?php echo $registros['emp_nombre']; ?></td>
          <td><?php echo $registros['emp_telefono']; ?></td>
          <td><?php echo $registros['emp_direccion']; ?></td>
          <td>
          <a name="" title="Editar" id="" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" class="btn btn-sm" href="editar.php?txtID=<?php echo $registros['emp_id']; ?>" role="button"><i class="fa fa-pen"></i></a>
         </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  
  </div>
 </div>
 



<?php include("../../plantillas/pie.php"); ?>