<?php 
include("../../controladores/conexionBaseDatos.php");

session_start();
if(!isset($_SESSION['usu_usuario'])){
  header("Location:../../index.php");  
}


?>

<?php include("../../plantillas/cabeceraNav.php"); ?>


<style>
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: white;
            transition: transform 0.3s;
            width: auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;

        }
        
        .card:hover  {
            transform: translateY(-5px);
        }
            /* Estilos para el contenido de la tarjeta al hacer hover */
            .card:hover .card-title {
            color: indigo; /* Cambia el color del título */
        }
        
        .card:hover .fa {
            color: indigo; /* Cambia el color del icono FontAwesome */
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }

        .btn:hover  {
            transform: translateY(-2px);
        }
    </style>


<div class="container mt-4">

<div class="row mt-4">

  <div class="col-md-3 mb-3">
    <a href="../facturacion/crear.php" class="card-link">
      <div class="card amber-card">
        <div class="card-body">
          <h5 class="card-title">Facturacion</h5>
          <i class="fa fa-file-invoice" style="font-size: 4rem;"></i>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3 mb-3">
    <a href="../facturacion/index.php" class="card-link">
      <div class="card sky-card">
        <div class="card-body">
          <h5 class="card-title">Reportes</h5>
          <i class="fa fa-light fa-list" style="font-size: 4rem;"></i>
        </div>
      </div>
    </a>
  </div>

  

</div>
<a href="../mantenimientos/index.php" class="btn btn" style=" box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:10px;" ><i class="fa fa-cog fa-lg"></i>
 Mantenimientos</a>

<?php include("../../plantillas/pie.php"); ?>