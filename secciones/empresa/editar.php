<?php

include("../../controladores/conexionBaseDatos.php");

session_start();
if(!isset($_SESSION['usu_usuario'])){
  header("Location:../../index.php");  
}


// Editar dicho registro con el id correspondiente
if(isset($_GET['txtID'])){
    
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";
    $sentencia=$conexion->prepare("SELECT * FROM fac_empresa WHERE emp_id=:emp_id");  
    $sentencia->bindParam(":emp_id", $txtID);  
    $sentencia->execute();
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);

  
    $emp_nombre=$registro['emp_nombre'];
    $emp_ruc=$registro['emp_ruc'];
    $emp_telefono=$registro['emp_telefono'];
    $emp_direccion=$registro['emp_direccion'];
    $emp_email=$registro['emp_email'];
    $emp_logo=$registro['emp_logo'];

}

if($_POST){ 
  $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
  $emp_nombre=(isset($_POST['emp_nombre']))?$_POST['emp_nombre']:"";
  $emp_ruc=(isset($_POST['emp_ruc']))?$_POST['emp_ruc']:"";
  $emp_telefono=(isset($_POST['emp_telefono']))?$_POST['emp_telefono']:"";
  $emp_direccion=(isset($_POST['emp_direccion']))?$_POST['emp_direccion']:"";
  $emp_email=(isset($_POST['emp_email']))?$_POST['emp_email']:"";
  
  $sentencia=$conexion->prepare("UPDATE fac_empresa 
  SET
  emp_nombre=:emp_nombre,
  emp_ruc=:emp_ruc,
  emp_telefono=:emp_telefono,
  emp_direccion=:emp_direccion, 
  emp_email=:emp_email 
  WHERE emp_id=:emp_id");

  $sentencia->bindParam(":emp_nombre", $emp_nombre); 
  $sentencia->bindParam(":emp_ruc", $emp_ruc); 
  $sentencia->bindParam(":emp_telefono", $emp_telefono); 
  $sentencia->bindParam(":emp_direccion", $emp_direccion); 
  $sentencia->bindParam(":emp_email", $emp_email); 
  $sentencia->bindParam(":emp_id", $txtID);
  $sentencia->execute();


  // ACTUALIZAR  LA IMAGEN INICIO
  if($_FILES['emp_logo']['tmp_name']!=""){

    $emp_logo=(isset($_FILES['emp_logo']['name']))?$_FILES['emp_logo']['name']:"";
    $fecha_logo=new DateTime();
    $nombre_archivo_logo=($emp_logo!="")? $fecha_logo->getTimestamp()."_".$emp_logo:"";

    $tmp_logo=$_FILES["emp_logo"]["tmp_name"];

    move_uploaded_file($tmp_logo,"../../imagenes/empresa/".$nombre_archivo_logo);


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

    $sentencia=$conexion->prepare("UPDATE fac_empresa SET emp_logo=:emp_logo WHERE emp_id=:emp_id"); 
    $sentencia->bindParam(":emp_logo", $nombre_archivo_logo); 
    $sentencia->bindParam(":emp_id", $txtID);  
    $sentencia->execute();  

}
// ACTUALIZAR LA IMAGEN FIN

 
  header('Location: index.php?');

  }


  

 ?>

<?php include("../../plantillas/cabeceraNav.php"); ?>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        Editar Empresa
        <a href="index.php" class="btn btn-sm" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" > <i class="fas fa-times"></i>
      </a>
    </div>
    <div class="card-body">

        <form enctype="multipart/form-data" action="" method="post">

        <div class="mb-3 d-none">
      <label for="txtID" class="form-label">ID:</label>
    <input value="<?php echo $txtID; ?>" type="text"
        class="form-control" name="txtID" id="txtID" placeholder="">
    </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="emp_nombre" class="form-label">Nombre:</label>
                    <input value="<?php echo $emp_nombre; ?>" type="text"
                        class="form-control" name="emp_nombre" id="emp_nombre" placeholder="Nombre" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="emp_ruc" class="form-label">RUC:</label>
                    <input value="<?php echo $emp_ruc; ?>" type="text"
                        class="form-control" name="emp_ruc" id="emp_ruc" placeholder="RUC" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="emp_telefono" class="form-label">Teléfono:</label>
                    <input value="<?php echo $emp_telefono; ?>" type="text"
                        class="form-control" name="emp_telefono" id="emp_telefono" placeholder="Teléfono" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="emp_direccion" class="form-label">Direccion:</label>
                    <input value="<?php echo $emp_direccion; ?>" type="text"
                        class="form-control" name="emp_direccion" id="emp_direccion" placeholder="Direccion" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="emp_email" class="form-label">Email:</label>
                    <input value="<?php echo $emp_email; ?>" type="text"
                        class="form-control" name="emp_email" id="emp_email" placeholder="Email" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="emp_logo" class="form-label">Imagen:</label>
                    <img width="50" src="../../imagenes/empresa/<?php echo $emp_logo; ?>" alt="">
                    <input type="file" class="form-control" name="emp_logo" id="emp_logo">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <button type="submit" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:10px;" class="btn btn-sm">Actualizar</button>
                </div>


        </form>

    </div>
</div>



<?php include("../../plantillas/pie.php");?>

