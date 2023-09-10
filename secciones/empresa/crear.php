<?php 

include("../../controladores/conexionBaseDatos.php");

session_start();
if(!isset($_SESSION['usu_usuario'])){
  header("Location:../../index.php");  
}




// Recepcionamos los valores del formulario
if($_POST){ 
  $emp_nombre=(isset($_POST['emp_nombre']))?$_POST['emp_nombre']:"";
  $emp_ruc=(isset($_POST['emp_ruc']))?$_POST['emp_ruc']:"";
  $emp_telefono=(isset($_POST['emp_telefono']))?$_POST['emp_telefono']:"";
  $emp_direccion=(isset($_POST['emp_direccion']))?$_POST['emp_direccion']:"";
  $emp_email=(isset($_POST['emp_email']))?$_POST['emp_email']:"";
  $emp_logo=(isset($_FILES['emp_logo']['name']))?$_FILES['emp_logo']['name']:"";
       
    // SUBIR LA IMAGEN INICIO
    $fecha_logo=new DateTime();
    $nombre_archivo_logo=($emp_logo!="")? $fecha_logo->getTimestamp()."_".$emp_logo:"";

    $tmp_logo=$_FILES["emp_logo"]["tmp_name"];
    if($tmp_logo!=""){
    move_uploaded_file($tmp_logo,"../../imagenes/empresa/".$nombre_archivo_logo);
    }
     // SUBIR LA IMAGEN FIN  
  $sentencia=$conexion->prepare("INSERT INTO `fac_empresa`(`emp_id`, `emp_nombre`, `emp_ruc`, `emp_telefono`, `emp_direccion`,`emp_email`,`emp_logo`)
  VALUES (NULL, :emp_nombre, :emp_ruc, :emp_telefono, :emp_direccion, :emp_email, :emp_logo);");

  $sentencia->bindParam(":emp_nombre", $emp_nombre); 
  $sentencia->bindParam(":emp_ruc", $emp_ruc); 
  $sentencia->bindParam(":emp_telefono", $emp_telefono); 
  $sentencia->bindParam(":emp_direccion", $emp_direccion); 
  $sentencia->bindParam(":emp_email", $emp_email); 
  $sentencia->bindParam(":emp_logo", $nombre_archivo_logo); 
  $sentencia->execute();


  header('Location: index.php?');

  }


?>

<?php include("../../plantillas/cabeceraNav.php"); ?>




<div class="card">
    <div class="card-header">
        Crear Empresa
    </div>
    <div class="card-body">
     
    <form enctype="multipart/form-data" action="" method="post">

    <div class="mb-3">
      <label for="emp_nombre" class="form-label">Nombre empresa:</label>
    <input type="text"
        class="form-control" name="emp_nombre" id="emp_nombre" placeholder="Nombre empresa" required>
    </div>

    <div class="mb-3">
      <label for="emp_ruc" class="form-label">RUC Empresa:</label>
    <input type="text"
        class="form-control" name="emp_ruc" id="emp_ruc" placeholder="RUC empresa" required>
    </div>

    <div class="mb-3">
      <label for="emp_telefono" class="form-label">Empresa telefono:</label>
    <input type="text"
        class="form-control" name="emp_telefono" id="emp_telefono" placeholder="Empresa telefono" required>
    </div>

    <div class="mb-3">
      <label for="emp_direccion" class="form-label">Empresa direccion:</label>
    <input type="text"
        class="form-control" name="emp_direccion" id="emp_direccion" placeholder="Empresa direccion" required>
    </div>

    <div class="mb-3">
      <label for="emp_email" class="form-label">Empresa email:</label>
    <input type="email"
        class="form-control" name="emp_email" id="emp_email" placeholder="Empresa email" required>
    </div>

 

    <div class="mb-3">
      <label for="emp_logo" class="form-label">Logo:</label>
    <input type="file"
        class="form-control" name="emp_logo" id="emp_logo" required>
    </div>


    <button type="submit" class="btn btn-success btn-sm">Agregar</button>

    <a class="btn btn-primary btn-sm" href="index.php" role="button">Salir</a>


    </form>

    </div>

</div>



<?php include("../../plantillas/pie.php");?>
