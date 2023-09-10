<?php
include("../../controladores/conexionBaseDatos.php");

session_start();
if (!isset($_SESSION['usu_usuario'])) {
    header("Location:../../index.php");
}

// Mensaje de error por defecto
$mensaje = "";

// Obtener el ID del usuario a editar
if (isset($_GET['txtID'])) {
    $idUsuarioEditar = $_GET['txtID'];
    
    // Obtener los datos del usuario a editar
    $consultaStmt = $conexion->prepare("SELECT * FROM `fac_usuarios` WHERE `usu_id` = :txtID");
    $consultaStmt->bindParam(':txtID', $idUsuarioEditar);
    $consultaStmt->execute();
    $usuarioEditar = $consultaStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuarioEditar) {
        // Usuario no encontrado, redirigir a la página de listado
        header('Location: index.php');
        exit;
    }
    
    // Datos del usuario
    $usu_nombre = $usuarioEditar['usu_nombre'];
    $usu_usuario = $usuarioEditar['usu_usuario'];
    $usu_password = $usuarioEditar['usu_password'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Actualizar los datos del usuario
    $usu_nombre = isset($_POST['usu_nombre']) ? $_POST['usu_nombre'] : "";
    $usu_usuario = isset($_POST['usu_usuario']) ? $_POST['usu_usuario'] : "";
    $usu_password = isset($_POST['usu_password']) ? $_POST['usu_password'] : "";
    
    // Verificar que los campos no estén vacíos
    if (empty($usu_nombre) || empty($usu_usuario)) {
        $mensaje = "Por favor, completa todos los campos.";
    } else {
        // Actualizar el usuario en la base de datos
        $actualizacionStmt = $conexion->prepare("UPDATE `fac_usuarios` SET `usu_nombre` = :usu_nombre, `usu_usuario` = :usu_usuario, `usu_password` = :usu_password WHERE `usu_id` = :id");
        $actualizacionStmt->bindParam(':usu_nombre', $usu_nombre);
        $actualizacionStmt->bindParam(':usu_usuario', $usu_usuario);
        
        // Hash y encriptar el nuevo password si se proporciona
        if (!empty($usu_password)) {
            $passwordEncriptado = password_hash($usu_password, PASSWORD_DEFAULT);
            $actualizacionStmt->bindParam(':usu_password', $passwordEncriptado);
        } else {
            // Mantener el password existente sin cambios
            $actualizacionStmt->bindValue(':usu_password', $usuarioEditar['usu_password']);
        }

        $actualizacionStmt->bindParam(':id', $idUsuarioEditar);
        
        if ($actualizacionStmt->execute()) {
            $mensaje = "Usuario actualizado con éxito.";
            // Redireccionar después de realizar la actualización
            header('Location: index.php');
            exit; // Detener la ejecución del script después de redireccionar
        } else {
            // Error al actualizar el registro
            $mensaje = "Error al actualizar el registro.";
            $claseAlerta = "danger";
        }
    }
}

include("../../plantillas/cabeceraNav.php");
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-3">Editar Usuario</h4>
        <a href="index.php" class="btn btn-sm" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;">
            <i class="fas fa-times"></i>
        </a>
    </div>
    <div class="card-body">

        <!-- Formulario de Edición -->
        <form method="post">

        <div class="mb-3 d-none">
      <label for="txtID" class="form-label">ID:</label>
    <input value="<?php echo $idUsuarioEditar; ?>" type="text"
        class="form-control" name="txtID" id="txtID" placeholder="">
    </div>

            <!-- Mostrar mensaje de error si existe -->
            <?php if (!empty($mensaje)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?php echo $mensaje; ?></strong>
                </div>
            <?php endif; ?>

            <!-- Campo de Nombre -->
            <div class="form-group mb-3">
                <label for="usu_nombre">Nombre:</label>
                <input type="text" class="form-control" id="usu_nombre" name="usu_nombre" placeholder="Ingresa tu nombre" value="<?php echo $usu_nombre; ?>">
            </div>

            <!-- Campo de Usuario -->
            <div class="form-group mb-3">
                <label for="usu_usuario">Usuario:</label>
                <input type="text" class="form-control" id="usu_usuario" name="usu_usuario" placeholder="Ingresa tu usuario" value="<?php echo $usu_usuario; ?>">
            </div>

            <!-- Campo de Contraseña -->
            <div class="form-group mb-3">
                <label for="">Contraseña Anterior:</label>
                <input readonly type="password" class="form-control" id="" value="<?php echo $usu_password; ?>" name="" placeholder="Ingresa la nueva contraseña">
            </div>

                 <!-- Campo de Contraseña -->
                 <div class="form-group mb-3">
                <label for="usu_password">Nueva Contraseña:</label>
                <input type="password" class="form-control" id="usu_password" name="usu_password" placeholder="Ingresa la nueva contraseña">
            </div>

            <button type="submit" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:10px;" class="btn btn-sm">Actualizar</button>

        </form>

    </div>
</div>

<?php include("../../plantillas/pie.php"); ?>
