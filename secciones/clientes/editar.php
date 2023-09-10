<?php

include("../../controladores/conexionBaseDatos.php");

session_start();
if (!isset($_SESSION['usu_usuario'])) {
    header("Location: ../../index.php");
}

$mensaje = ""; // Inicializamos el mensaje en vacío

// Editar dicho registro con el ID correspondiente
if(isset($_GET['txtID'])){
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";
    $sentencia=$conexion->prepare("SELECT * FROM fac_clientes WHERE cli_id=:cli_id");  
    $sentencia->bindParam(":cli_id", $txtID);  
    $sentencia->execute();
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);

    $cli_nombre=$registro['cli_nombre'];
    $cli_numero_identificacion=$registro['cli_numero_identificacion'];
    $cli_celular=$registro['cli_celular'];
    $cli_email=$registro['cli_email'];
    $cli_direccion=$registro['cli_direccion'];
    $cli_tipo_identificacion = $registro['cli_tipo_identificacion'];
}

if($_POST){ 
  $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
  $cli_nombre=(isset($_POST['cli_nombre']))?$_POST['cli_nombre']:"";
  $cli_numero_identificacion=(isset($_POST['cli_numero_identificacion']))?$_POST['cli_numero_identificacion']:"";
  $cli_celular=(isset($_POST['cli_celular']))?$_POST['cli_celular']:"";
  $cli_email=(isset($_POST['cli_email']))?$_POST['cli_email']:"";
  $cli_direccion=(isset($_POST['cli_direccion']))?$_POST['cli_direccion']:"";
  $cli_tipo_identificacion=(isset($_POST['cli_tipo_identificacion']))?$_POST['cli_tipo_identificacion']:"";

  // Validar longitud del número de identificación
  if (strlen($cli_numero_identificacion) !== 10 && strlen($cli_numero_identificacion) !== 13) {
    $mensaje = "El número de identificación debe tener 10 o 13 dígitos.";
  } elseif (strlen($cli_numero_identificacion) === 13 && substr($cli_numero_identificacion, -3) !== '001') {
    $mensaje = "Los tres últimos dígitos del número de identificación deben ser '001' si son 13 dígitos.";
  } else {
    // Verificamos si ya existe un producto con el mismo código o nombre
    $sentencia_verificar = $conexion->prepare("SELECT * FROM `fac_clientes` WHERE (cli_nombre = :cli_nombre OR cli_numero_identificacion = :cli_numero_identificacion) AND cli_id != :cli_id");
    $sentencia_verificar->bindParam(":cli_nombre", $cli_nombre);
    $sentencia_verificar->bindParam(":cli_numero_identificacion", $cli_numero_identificacion);
    $sentencia_verificar->bindParam(":cli_id", $txtID);
    $sentencia_verificar->execute();

    if ($sentencia_verificar->rowCount() > 0) {
      $mensaje = "No se puede actualizar el cliente porque ya existe un cliente con el mismo código o nombre.";
    } else {
      // Si no existe conflicto, procedemos con la actualización
      $sentencia=$conexion->prepare("UPDATE fac_clientes 
      SET
      cli_nombre=:cli_nombre,
      cli_numero_identificacion=:cli_numero_identificacion,
      cli_celular=:cli_celular, 
      cli_email=:cli_email,
      cli_direccion=:cli_direccion,
      cli_tipo_identificacion=:cli_tipo_identificacion
      WHERE cli_id=:cli_id");

      $sentencia->bindParam(":cli_nombre", $cli_nombre); 
      $sentencia->bindParam(":cli_numero_identificacion", $cli_numero_identificacion); 
      $sentencia->bindParam(":cli_celular", $cli_celular); 
      $sentencia->bindParam(":cli_email", $cli_email); 
      $sentencia->bindParam(":cli_direccion", $cli_direccion); 
      $sentencia->bindParam(":cli_tipo_identificacion", $cli_tipo_identificacion); 
      $sentencia->bindParam(":cli_id", $txtID);
      $sentencia->execute();

      $mensaje = "Registro modificado con éxito.";
      header('Location: index.php?mensaje='.$mensaje);
    }
  }
}

include("../../plantillas/cabeceraNav.php");
?>



<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-3">Editar Cliente</h4>
        <a href="index.php" class="btn btn-sm" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;"> <i class="fas fa-times"></i></a>
    </div>
    <div class="card-body">
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-danger"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3 d-none">
                <label for="txtID" class="form-label">ID:</label>
                <input value="<?php echo $txtID; ?>" type="text" class="form-control" name="txtID" id="txtID" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="cli_nombre" class="form-label">Nombres Completos:</label>
                <input type="text" class="form-control" name="cli_nombre" id="cli_nombre" required placeholder="Nombre" value="<?php echo $cli_nombre; ?>">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cli_numero_identificacion" class="form-label">Identificación:</label>
                        <input type="number" class="form-control" name="cli_numero_identificacion" id="cli_numero_identificacion" required placeholder="Número Identificación" maxlength="13" value="<?php echo $cli_numero_identificacion; ?>">
                        <small class="text-danger" id="identificacion-error" style="display: none;">Máximo 13 dígitos permitidos.</small>
                    </div>
                </div>

                <div class="col-md-6">
                <div class="mb-3">
                    <label for="cli_tipo_identificacion" class="form-label">Tipo de Identificación:</label>
                    <select class="form-select" name="cli_tipo_identificacion" id="cli_tipo_identificacion" <?php echo ($cli_numero_identificacion !== $registro['cli_numero_identificacion']) ? '' : 'disabled'; ?>>
                        <option value="cedula" <?php if ($cli_tipo_identificacion === 'cedula') echo 'selected'; ?>>Cédula</option>
                        <option value="ruc" <?php if ($cli_tipo_identificacion === 'ruc') echo 'selected'; ?>>RUC</option>
                    </select>
                </div>
            </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cli_celular" class="form-label">Celular:</label>
                        <input type="text" class="form-control" name="cli_celular" id="cli_celular" required placeholder="Celular" value="<?php echo $cli_celular; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cli_email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="cli_email" id="cli_email" required placeholder="Email" value="<?php echo $cli_email; ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="cli_direccion" class="form-label">Direccion:</label>
                <input type="text" class="form-control" name="cli_direccion" id="cli_direccion" placeholder="Direccion" value="<?php echo $cli_direccion; ?>">
            </div>

            <button type="submit" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:10px;" class="btn btn-sm">Actualizar</button>
        </form>
    </div>
</div>


<script>

// Este código limita la longitud del campo de identificación a 13 dígitos
document.addEventListener("DOMContentLoaded", function () {
    const identificacionInput = document.getElementById("cli_numero_identificacion");
    const identificacionError = document.getElementById("identificacion-error");

    identificacionInput.addEventListener("input", function () {
        const maxDigits = 13;
        const enteredValue = identificacionInput.value;
        
        if (enteredValue.length > maxDigits) {
            identificacionInput.value = enteredValue.slice(0, maxDigits);
            identificacionError.style.display = "block";
        } else {
            identificacionError.style.display = "none";
        }
    });
});

// Este código ajusta automáticamente el tipo de identificación según la longitud del número ingresado
document.addEventListener("DOMContentLoaded", function () {
    const identificacionInput = document.getElementById("cli_numero_identificacion");
    const tipoIdentificacionSelect = document.getElementById("cli_tipo_identificacion");

    identificacionInput.addEventListener("input", function () {
        const enteredValue = identificacionInput.value;
        
        if (enteredValue.length === 10) {
            tipoIdentificacionSelect.value = "cedula";
            tipoIdentificacionSelect.disabled = false;
            tipoIdentificacionSelect.options[1].disabled = true;
        } else if (enteredValue.length === 13) {
            tipoIdentificacionSelect.value = "ruc";
            tipoIdentificacionSelect.disabled = false;
            tipoIdentificacionSelect.options[0].disabled = true;
        } else {
            tipoIdentificacionSelect.value = "";
            tipoIdentificacionSelect.disabled = true;
            tipoIdentificacionSelect.options[0].disabled = false;
            tipoIdentificacionSelect.options[1].disabled = false;
        }
    });
});

// Este código convierte el nombre ingresado a mayúsculas
document.addEventListener("DOMContentLoaded", function () {
    const nombreInput = document.getElementById("cli_nombre");

    nombreInput.addEventListener("input", function () {
        nombreInput.value = nombreInput.value.toUpperCase();
    });
});
</script>


<?php include("../../plantillas/pie.php"); ?>


