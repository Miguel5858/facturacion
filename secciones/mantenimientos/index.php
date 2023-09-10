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
        
        .card:hover {
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

  .frame {
  position: relative;
  border: 1px solid #e2e8f0;
  padding: 10px;
  border-radius: 15px;
}

.close-button {
  position: absolute;
  top: 0;
  right: 0;
  padding: 5px 10px;
  text-decoration: none;
  font-weight: bold;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
  background-color: indigo;
  color: white;
  border-radius:50px;

}
.close-button:hover  {
            transform: translateY(-2px);
        }


    </style>




<div class="container mt-4 mb-4">

<div class="frame">

<a title="Salir" href="../inicio/index.php" class="btn btn-sm" style=" position: absolute; top: 0; right: 0; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color:white; border-radius:50px;" > <i class="fas fa-times"></i>
    </a>

  <div class="row mt-4">
    <div class="col-md-3 mb-3">
      <a title="Administrar Usuarios" href="/facturacion/secciones/usuarios/index.php" class="card-link">
        <div class="card amber-card">
          <div class="card-body">
            <h5 class="card-title">Usuarios</h5>
            <i class="fa fa-user-circle" style="font-size: 4rem;"></i>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3 mb-3">
      <a title="Administrar Productos" href="/facturacion/secciones/productos/index.php" class="card-link">
        <div class="card sky-card">
          <div class="card-body">
            <h5 class="card-title">Productos</h5>
            <i class="fa fa-box-open" style="font-size: 4rem;"></i>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3 mb-3">
      <a title="Administrar Clientes" href="/facturacion/secciones/clientes/index.php" class="card-link">
        <div class="card sky-card">
          <div class="card-body">
            <h5 class="card-title">Clientes</h5>
            <i class="fa fa-users" style="font-size: 4rem;"></i>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-3 mb-3">
      <a title="Administrar Empresa" href="/facturacion/secciones/empresa/index.php" class="card-link">
        <div class="card sky-card">
          <div class="card-body">
            <h5 class="card-title mb-4">Empresa</h5>
            <i class="fa fa-building fa-lg" style="font-size: 4rem;"></i>
          </div>
        </div>
      </a>
    </div>
    
  </div>
  
</div>

</div>



<?php include("../../plantillas/pie.php"); ?>