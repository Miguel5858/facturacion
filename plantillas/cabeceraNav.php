
<?php 
include("../../controladores/conexionBaseDatos.php");
   // Seleccionar registros de la base de datos y mostralos en un foreach
$sentencia=$conexion->prepare("SELECT * FROM `fac_empresa`");
$sentencia->execute();
$lista_empresa=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <!-- Referencia a los scripts de jQuery y Popper.js (necesarios para Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>

    <!-- Referencia al script de Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

     <!-- CSS personalizado para el efecto de carga -->
  <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        .circle {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: green;
            display: inline-block;
            margin-right: 1px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <title>secciones</title>
  </head>
  <body>



         <!-- Efecto de carga -->
         <div id="loadingOverlay" class="loading-overlay" style="display: none;">
            <div class="loading-spinner"></div>
        </div>


    <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: indigo; color:white;">

    <div class="container">

    <?php foreach($lista_empresa as $registros) { ?>
    <a class="navbar-brand" href="/facturacion/secciones/inicio/index.php">
    <img class="" width="100" src="../../imagenes/empresa/<?php echo $registros['emp_logo']; ?>" alt="Logo empresa">
    </a>
    <?php } ?>

   
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">

          <!-- <ul class="navbar-nav ">

          <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Mantenimientos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#"><i class="fas fa-users"></i> Usuarios</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-box-open"></i> Productos</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-user-circle"></i> Clientes</a>
                    <a class="dropdown-item" href="/facturacion/secciones/empresa/index.php"><i class="fas fa-user-circle"></i> Empresa</a>
                </div>
            </li>

            
          
            <li class="nav-item">
                <a class="nav-link" href="#">Facturacion</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Reportes</a>
            </li>

            </ul> -->

            <ul class="navbar-nav ml-auto">

            <!-- <li class="nav-item">
                <a class="nav-link" href="/facturacion/secciones/inicio/index.php"><i class="fas fa-home"></i></a>
            </li> -->

            <li class="nav-item dropdown">
            
                <a class="nav-link" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
                    <?php if (isset($_SESSION['logueado']) && $_SESSION['logueado']) ?>
                    <div class="circle"></div> <?php echo $_SESSION['usu_usuario']; ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="../../controladores/cerrar.php"> <i class="fas fa-sign-out-alt"></i> Cerrar Sesion</a>
                </div>
            </li>
        </ul>
    </div>
    </div>
    
</nav>

<br><br>
<div class="container mt-4">