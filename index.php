
<?php
session_start();

include("controladores/conexionBaseDatos.php");

$mensaje = ""; // Inicializar la variable $mensaje

if ($_POST) {
  $usu_usuario = isset($_POST['usu_usuario']) ? $_POST['usu_usuario'] : "";
  $usu_password = isset($_POST['usu_password']) ? $_POST['usu_password'] : "";

  if (empty($usu_usuario) || empty($usu_password)) {
    $mensaje = "Error: Debes llenar todos los campos.";
  } else {
    $sentencia = $conexion->prepare("SELECT * FROM fac_usuarios WHERE usu_usuario = :usu_usuario");
    $sentencia->bindParam(":usu_usuario", $usu_usuario);
    $sentencia->execute();

    $usuarioEncontrado = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($usuarioEncontrado) {
      if (password_verify($usu_password, $usuarioEncontrado['usu_password'])) {
        $_SESSION['usu_usuario'] = $usuarioEncontrado['usu_usuario'];
        $_SESSION['logueado'] = true;
        header("Location: secciones/inicio/index.php");
        exit();
      } else {
        $mensaje = "Error: Contraseña incorrecta.";
      }
    } else {
      $mensaje = "Error: El usuario no existe.";
    }
  }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    /* Personalizar la transición de desvanecimiento */
    .carousel-fade .carousel-item {
      opacity: 0;
      transition: opacity 0.9s ease-in-out;
    }
    .carousel-fade .carousel-item.active {
      opacity: 1;
    }
    img {
        border-radius: 15px;
    }
    /* Estilo para el icono de carga */
    .loading-icon {
        margin-left: 5px;
    }

    /* Estilo para ocultar el texto del botón y mostrar el icono de carga */
    .btn-loading.loading .loading-text {
        display: none;
    }

    .btn-loading.loading .loading-icon {
        display: inline-block;
    }

  </style>
  <title>Ingreso al sistema</title>
</head>
<body>

    <div class="container pt-5">
    <div class="row">
      <div class="col-md-6 mb-5">
        <div id="imageCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="imagenes/login/1.jpg" class="d-block w-100" alt="Imagen 1">
            </div>
            <div class="carousel-item">
              <img src="imagenes/login/2.jpg" class="d-block w-100" alt="Imagen 2">
            </div>
            <div class="carousel-item">
              <img src="imagenes/login/3.jpg" class="d-block w-100" alt="Imagen 3">
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body">

               <!-- Mensaje de Error-->
                <?php if ($_POST && isset($_POST['usu_usuario'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $mensaje ;?></strong>         
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php } ?>

            <h2 class="card-title">Iniciar Sesión</h2>

            <form  method="post">

               <!-- Campo de Usuario -->
                <div class="form-group mb-3">
                    <label for="usu_usuario">Usuario:</label>
                    <input type="text" class="form-control" name="usu_usuario" id="usu_usuario" placeholder="Ingresa tu usuario">
                </div>

                  <!-- Campo de Contraseña -->
                <div class="form-group mb-3">
                    <label for="usu_password">Contraseña:</label>
                    <input type="password" class="form-control" name="usu_password" id="usu_password" placeholder="Ingresa tu contraseña">
                </div>
             
              
                 <!-- Botón de Registro/Login -->   
             
                <button type="submit" class="btn btn-block btn-loading" style="background-color: indigo; color:white;">
                <span class="loading-text">Ingresar</span>
                <span class="spinner-border spinner-border-sm d-none loading-icon" role="status" aria-hidden="true"></span>
                </button>


            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Inicializar el carrusel y configurar el intervalo
    var carousel = new bootstrap.Carousel(document.getElementById('imageCarousel'), {
      interval: 3000, // Cambio cada 3 segundos
      wrap: true // Vuelve al principio después de la última imagen
    });

    // Espera a que se cargue completamente el contenido del documento
    document.addEventListener("DOMContentLoaded", function () {
        // Obtén una referencia al botón de envío con la clase "btn-loading"
        const submitButton = document.querySelector(".btn-loading");

        // Obtén referencias a los elementos dentro del botón que mostrarán el mensaje de "Ingresando" y el icono de carga
        const loadingText = submitButton.querySelector(".loading-text");
        const loadingIcon = submitButton.querySelector(".loading-icon");

        // Agrega un "event listener" al botón de envío para manejar el clic
        submitButton.addEventListener("click", function () {
            // Cambia el estado del botón cuando se hace clic
            submitButton.classList.add("loading"); // Agrega la clase "loading" para activar los estilos
            loadingText.textContent = "Ingresando..."; // Cambia el texto del mensaje de "Ingresando"
            loadingIcon.classList.remove("d-none"); // Muestra el icono de carga (elimina la clase "d-none" que lo oculta)
        });
    });

  </script>
</body>
</html>


