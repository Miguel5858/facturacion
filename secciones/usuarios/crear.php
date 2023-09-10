<?php
include("../../controladores/conexionBaseDatos.php");

session_start();
if(!isset($_SESSION['usu_usuario'])){
  header("Location:../../index.php");  
}

// Mensaje de error por defecto
$mensaje = "";


//indicamos que se están enviando datos al servidor a través de un formulario
// Recepcionamos los valores del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usu_nombre = isset($_POST['usu_nombre']) ? $_POST['usu_nombre'] : "";
    $usu_usuario = isset($_POST['usu_usuario']) ? $_POST['usu_usuario'] : "";
    $usu_password = isset($_POST['usu_password']) ? $_POST['usu_password'] : "";


        // Verificar si el usuario ya existe
        $verificarStmt = $conexion->prepare("SELECT COUNT(*) FROM `fac_usuarios` WHERE `usu_usuario` = :usu_usuario");
        $verificarStmt->bindParam(':usu_usuario', $usu_usuario);
        $verificarStmt->execute();
        $usuarioExistente = $verificarStmt->fetchColumn();

    // Verificar que los campos no estén vacíos
    if (empty($usu_nombre) || empty($usu_usuario) || empty($usu_password)) {
        $mensaje = "Por favor, completa todos los campos.";
    } else {
        // Encriptar el password utilizando password_hash()
      

        if (!$usuarioExistente) {
            // El usuario no existe, realizar la inserción
            $passwordEncriptado = password_hash($usu_password, PASSWORD_DEFAULT);
            $insercionStmt = $conexion->prepare("INSERT INTO `fac_usuarios`(`usu_id`, `usu_nombre`, `usu_usuario`, `usu_password`)
                                                 VALUES (NULL, :usu_nombre, :usu_usuario, :usu_password)");
            $insercionStmt->bindParam(':usu_nombre', $usu_nombre);
            $insercionStmt->bindParam(':usu_usuario', $usu_usuario);
            $insercionStmt->bindParam(':usu_password', $passwordEncriptado); // Usar el hash en lugar de la contraseña en texto plano
            
            if ($insercionStmt->execute()) {
        $mensaje = "Usuario registrado con éxito.";
        // Redireccionar después de realizar la inserción
        header('Location: index.php');
        exit; // Detener la ejecución del script después de redireccionar

            } else {
            // Error al insertar el registro
            $mensaje = "Error al insertar el registro.";
            $claseAlerta = "danger";
            }
        } else {

            // Usuario ya existe
            $mensaje = "El usuario ya existe. No se puede crear.";
            $claseAlerta = "warning";
        }
        
    }


    
}

include("../../plantillas/cabeceraNav.php");
?>





<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-3">Registrar Usuario</h4>
        <a href="index.php" class="btn btn-sm" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" > <i class="fas fa-times"></i>
        </a>
    </div>
    <div class="card-body">


            <!-- Formulario de Registro/Login -->
            <form method="post">         

                     <!-- Mostrar mensaje de error si existe -->
                     <?php if (!empty($mensaje)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><?php echo $mensaje ;?></strong>         
                        </div>
                     <?php endif; ?>

                <!-- Campo de Nombre -->
                <div class="form-group mb-3">
                    <label for="usu_nombre">Nombre:</label>
                    <input type="text" class="form-control" id="usu_nombre" name="usu_nombre" placeholder="Ingresa tu nombre">
                </div>

                <!-- Campo de Usuario -->
                <div class="form-group mb-3">
                    <label for="usu_usuario">Usuario:</label>
                    <input type="text" class="form-control" id="usu_usuario" name="usu_usuario"  placeholder="Ingresa el usuario">
                </div>

                <!-- Campo de Contraseña -->
                <div class="form-group mb-3">
                    <label for="usu_password">Contraseña:</label>
                    <input type="password" class="form-control" id="usu_password" name="usu_password" placeholder="Ingresa tu contraseña">
                </div>
          
 
                <button type="submit" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:10px;" class="btn btn-sm">Registrar</button>

            </form>

        </div>


</div>




<?php include("../../plantillas/pie.php"); ?>
