<?php
include("../../controladores/conexionBaseDatos.php");

session_start();
if(!isset($_SESSION['usu_usuario'])){
  header("Location:../../index.php");  
}

$mensaje = ""; // Inicializamos el mensaje en vacío

// Verificamos si se ha enviado el formulario
if($_POST){ 
  $prod_nombre = (isset($_POST['prod_nombre'])) ? $_POST['prod_nombre'] : "";
  $prod_codigo = (isset($_POST['prod_codigo'])) ? $_POST['prod_codigo'] : "";
  $prod_precio = (isset($_POST['prod_precio'])) ? $_POST['prod_precio'] : "";
  $prod_iva = (isset($_POST['prod_iva'])) ? $_POST['prod_iva'] : "";

  // Verificamos si ya existe un producto con el mismo código o nombre
  $sentencia_verificar = $conexion->prepare("SELECT * FROM `fac_productos` WHERE prod_codigo = :prod_codigo OR prod_nombre = :prod_nombre");
  $sentencia_verificar->bindParam(":prod_codigo", $prod_codigo);
  $sentencia_verificar->bindParam(":prod_nombre", $prod_nombre);
  $sentencia_verificar->execute();

  if ($sentencia_verificar->rowCount() > 0) {
    $mensaje = "No se puede crear el producto porque ya existe un producto con el mismo código o nombre.";
  } else {
    // Si no existe, procedemos con la inserción
    $sentencia_insertar = $conexion->prepare("INSERT INTO `fac_productos`(`prod_id`, `prod_nombre`, `prod_codigo`, `prod_precio`, `prod_iva`)
      VALUES (NULL, :prod_nombre, :prod_codigo, :prod_precio, :prod_iva);");

    $sentencia_insertar->bindParam(":prod_nombre", $prod_nombre); 
    $sentencia_insertar->bindParam(":prod_codigo", $prod_codigo); 
    $sentencia_insertar->bindParam(":prod_precio", $prod_precio); 
    $sentencia_insertar->bindParam(":prod_iva", $prod_iva); 
    $sentencia_insertar->execute();

    $mensaje = "Registro agregado con éxito.";
    header('Location: index.php');
  }
}

include("../../plantillas/cabeceraNav.php");
?>
<!-- El resto del código HTML del formulario permanece igual -->


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-3">Registrar Producto</h4>
        <a href="index.php" class="btn btn-sm" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" > <i class="fas fa-times"></i>
        </a>
    </div>
    <div class="card-body">

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
        <?php endif; ?>
     
    <form action="" method="post">

    <div class="mb-3">
      <label for="prod_nombre" class="form-label">Nombre:</label>
    <input type="text"
        class="form-control" name="prod_nombre" id="prod_nombre" placeholder="Nombre">
    </div>

    <div class="mb-3">
      <label for="prod_codigo" class="form-label">Codigo:</label>
    <input type="text"
        class="form-control" name="prod_codigo" id="prod_codigo" placeholder="Codigo">
    </div>

    <div class="mb-3">
  <label for="prod_precio" class="form-label">Precio:</label>
  <input type="number"
         step="any"
         class="form-control" 
         name="prod_precio" 
         id="prod_precio" 
         placeholder="Precio" 
         inputmode="decimal" 
         pattern="[0-9]+([,.][0-9]+)?">
   </div>

      <div class="mb-3">
      <label for="prod_iva" class="form-label">IVA:</label>
      <select class="form-select" name="prod_iva" id="prod_iva">
        <option value="12">12%</option>
        <option value="0">0%</option>
      </select>
    </div>


    <button type="submit" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:10px;" class="btn btn-sm">Registrar</button>


    </form>

    </div>

</div>


<script>
// Este código convierte el nombre ingresado a mayúsculas mientras se escribe
document.addEventListener("DOMContentLoaded", function () {
    const nombreInput = document.getElementById("prod_nombre");

    nombreInput.addEventListener("input", function () {
        nombreInput.value = nombreInput.value.toUpperCase();
    });
});
</script>



<?php include("../../plantillas/pie.php"); ?>

