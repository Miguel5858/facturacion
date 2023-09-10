<?php
include("../../controladores/conexionBaseDatos.php");

session_start();
if (!isset($_SESSION['usu_usuario'])) {
    header("Location:../../index.php");
}




// Seleccionar registros Productos
$sentencia = $conexion->prepare("SELECT * FROM `fac_clientes`");
$sentencia->execute();
$lista_clientes = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Inicializa o recupera la lista de productos seleccionados
if (!isset($_SESSION['productosSeleccionados'])) {
    $_SESSION['productosSeleccionados'] = array();
}

// Seleccionar registros Productos
$sentencia = $conexion->prepare("SELECT * FROM `fac_productos`");
$sentencia->execute();
$lista_productos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Verifica si se ha enviado el formulario
if (isset($_POST['agregarProducto'])) {
    // Obtiene el ID del producto seleccionado
    $productoSeleccionado = $_POST['productoSeleccionado'];

    // Busca el producto en la lista de productos
    $productoEncontrado = false;
    foreach ($_SESSION['productosSeleccionados'] as $key => $producto) {
        if ($producto['prod_codigo'] == $productoSeleccionado) {
            // Si el producto ya está en la lista, aumenta la cantidad
            $_SESSION['productosSeleccionados'][$key]['cantidad'] += 1;
            $productoEncontrado = true;
            break;
        }
    }

    // Si el producto no se encontró en la lista, agrégalo con cantidad 1
    if (!$productoEncontrado) {
        foreach ($lista_productos as $producto) {
            if ($producto['prod_codigo'] == $productoSeleccionado) {
                $producto['cantidad'] = 1;
                $_SESSION['productosSeleccionados'][] = $producto;
                break;
            }
        }
    }

    // Redirige al usuario después de agregar un producto
    header("Location: crear.php"); // Cambia 'tu_pagina.php' al nombre de tu página actual
    exit();
}

// Verifica si se ha enviado el formulario para eliminar un producto
if (isset($_POST['eliminarProducto'])) {
    $productoAEliminar = $_POST['eliminarProducto'];

    // Recorre la lista de productos seleccionados y elimina el producto con el código especificado
    foreach ($_SESSION['productosSeleccionados'] as $key => $producto) {
        if ($producto['prod_codigo'] == $productoAEliminar) {
            unset($_SESSION['productosSeleccionados'][$key]);
            break; // Rompe el bucle una vez que se haya eliminado el producto
        }
    }

    // Redirige al usuario después de eliminar un producto
    header("Location: crear.php"); // Cambia 'tu_pagina.php' al nombre de tu página actual
    exit();
}

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $key = $_POST['key'];

    if ($accion === 'incrementar') {
        // Incrementar la cantidad
        $_SESSION['productosSeleccionados'][$key]['cantidad']++;
    } elseif ($accion === 'decrementar') {
        // Decrementar la cantidad, asegurándose de que no sea menor que 1
        if ($_SESSION['productosSeleccionados'][$key]['cantidad'] > 1) {
            $_SESSION['productosSeleccionados'][$key]['cantidad']--;
        }
    }

    // Redirige al usuario después de incrementar o decrementar la cantidad
    header("Location: crear.php"); // Cambia 'tu_pagina.php' al nombre de tu página actual
    exit();
}


$subtotalConIVA0 = 0.00; // Variable para almacenar el subtotal con IVA del 0%
$subtotalConIVA12 = 0.00; // Variable para almacenar el subtotal con IVA al 12%
$subtotalGeneral = 0.00;
$totalFinal = 0.00;


// Verificar si la factura se ha guardado correctamente
if (isset($_GET['factura_guardada']) && $_GET['factura_guardada'] == "true") {
    // Si la factura se ha guardado, limpiar la lista de productos y otros campos
    unset($_SESSION['productosSeleccionados']);
    $mensajeExito = "Factura guardada correctamente.";
}

include("../../plantillas/cabeceraNav.php");
?>

<style>
    .frame {
        position: relative;
        border: 1px solid #e2e8f0;
        padding: 15px;
        border-radius: 15px;
    }
</style>

<div class="container mt-4">
    <div class="frame mb-4">
        <h3 class="box-title">Nueva Factura</h3>
        <a title="Salir" href="../inicio/index.php" class="btn btn-sm" style="position: absolute; top: 0; right: 0; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: indigo; color: white; border-radius: 50px;"><i class="fas fa-times"></i></a>
 


        <?php if (!empty($mensajeExito)) : ?>
            <div class="alert alert-success mt-3">
                <?php echo $mensajeExito; ?>
            </div>
        <?php endif; ?>


    <div class="row mb-4">
        <div class="col-md-6">
            <form method="post">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="background-color: indigo; color: white;"><i class="fa fa-box-open" style="font-size: 1.5rem;"></i></span>
                    </div>

                    <select class="form-control mb-3" name="productoSeleccionado">
                        <option value="" disabled selected>Selecciona un producto</option>
                        <?php foreach ($lista_productos as $producto) : ?>
                            <option value="<?php echo $producto['prod_codigo']; ?>"><?php echo $producto['prod_nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="submit" class="btn" style="background-color: indigo; color: white;" name="agregarProducto" value="Agregar Producto">
            </form>
        </div>

        <div class="col-md-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="background-color: indigo; color: white;"><i class="fa fa-user" style="font-size: 1.5rem;"></i></span>
                    </div>

                    <select class="form-control" id="clienteSelect" onchange="guardarClienteSeleccionado()">
                    <option value="" disabled selected>Selecciona un cliente</option>
                    <?php foreach ($lista_clientes as $index => $cliente) : ?>
                        <option value="<?php echo $cliente['cli_id']; ?>" data-index="<?php echo $index; ?>"><?php echo $cliente['cli_nombre']; ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
              
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" id="cliente-info">
            <!-- Mostrar la información del cliente si está disponible -->
          
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="fecha">Seleccionar fecha:</label>
                <input type="date" class="form-control" id="fechaEmision" name="fechaEmision" value="" onchange="guardarFechaEmision()">   
            </div>
        </div>
    </div>


<!-- La tabla de productos -->
<form method="post">
    <?php
    // Resto de tu código ...

    $subtotalConIVA0 = 0; // Variable para almacenar el subtotal con IVA del 0%
    $subtotalConIVA12 = 0; // Variable para almacenar el subtotal con IVA al 12%
    $totalIva12 = 0; // Variable para almacenar la suma de IVAs al 12%
    


    if (!empty($_SESSION['productosSeleccionados'])) {
        
        echo '<div class="table-responsive">';
        echo '<table class="table">';
        echo '<tr>';
        echo '<th>PRODUCTO</th>';
        echo '<th>PRECIO</th>';
        echo '<th>CANTIDAD</th>';
        echo '<th>SUBTOTAL</th>';
        echo '<th>IVA</th>';
        echo '<th>TOTAL CON IVA</th>';
        echo '<th>ELIMINAR</th>';
        echo '</tr>';

        foreach ($_SESSION['productosSeleccionados'] as $key => $producto) {
            echo '<tr>';
            echo '<td>' . $producto['prod_nombre'] . '</td>';
            echo '<td>' . $producto['prod_precio'] . '</td>';

            // Campo de cantidad
            echo '<td>';
            // Grupo de botones de incremento y decremento
            echo '<div class="input-group">';
            // Botón de decremento
            echo '<form method="post">';
            echo '<input type="hidden" name="accion" value="decrementar">';
            echo '<input type="hidden" name="key" value="' . $key . '">';
            echo '<button type="submit" class="btn btn-secondary btn-sm">-</button>';
            echo '</form>';

            // Cantidad
            echo '<span style="margin: 5px;">' . $producto['cantidad'] . '</span>';

            // Botón de incremento
            echo '<form method="post">';
            echo '<input type="hidden" name="accion" value="incrementar">';
            echo '<input type="hidden" name="key" value="' . $key . '">';
            echo '<button type="submit" class="btn btn-primary btn-sm">+</button>';
            echo '</form>';

            echo '</div>'; // Cierre del grupo de botones y cantidad
            echo '</td>';

            // Calcula el subtotal
            $subtotal = $producto['prod_precio'] * $producto['cantidad'];
            echo '<td>' . $subtotal . '</td>';

            // Verificar si el IVA es mayor que 0 antes de calcularlo
            $iva = 0;
            if ($producto['prod_iva'] > 0) {
                $iva = $subtotal * ($producto['prod_iva'] / 100);
            }

            // Si el producto tiene IVA al 0%, suma su subtotal a la variable $subtotalConIVA0
            if ($producto['prod_iva'] == 0) {
                $subtotalConIVA0 += $subtotal;
            }
             // Si el producto tiene IVA al 12%, suma su subtotal a la variable $subtotalConIVA12
                elseif ($producto['prod_iva'] == 12) {
                    $subtotalConIVA12 += $subtotal;
            }
              // Si el producto tiene IVA al 12%, suma su IVA a la variable $totalIva12
            if ($producto['prod_iva'] == 12) {
                $totalIva12 += $iva;
            }

            echo '<td>' . $iva . '</td>';

            // Calcula el total con IVA como el subtotal + IVA
            $totalConIva = $subtotal + $iva;
            echo '<td>' . $totalConIva . '</td>';

            // Agregar un botón de eliminación con el código del producto como valor
            echo '<td><button type="submit" class="btn" style="background-color: indigo; color: white;" name="eliminarProducto" value="' . $producto['prod_codigo'] . '">Eliminar</button></td>';
            echo '</tr>';
        }


    $subtotalGeneral = $subtotalConIVA0 + $subtotalConIVA12;

    $totalFinal = $subtotalGeneral +  $totalIva12;
        echo '</table>';
        echo '</div>'; // tablas responsiva
    }
    ?>
</form>








     <div class="container mt-4">
                <div class="row">
                    <div class="col-md-9">
                        
                    <div class="card mb-3">
                        <div class="card-body text-left">
                            <h5 class="card-title">Formas de Pago</h5>

                            <select class="form-control mb-2" id="formaPago" onchange="guardarFormaPagoSeleccionada()">
                                <option value="" disabled selected>Selecciona una forma de pago</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Dinero Electrónico">Dinero Electrónico</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Otros con utilización del sistema financiero">Otros con utilización del sistema financiero</option>
                                <option value="Otros sin utilización del sistema financiero">Otros sin utilización del sistema financiero</option>
                            </select>


                          
                        </div>
                    </div>                        
                    
                    </div>
                    

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-left">
                                <table class="table">
                                    <tr>
                                        <td class="font-weight-bold">Sub total IVA Vigente:</td>
                                        <td><span id="totalConIVA"><?php echo $subtotalConIVA12 ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Sub total 0%:</td>
                                        <td><span id="totalSinIVA"><?php echo $subtotalConIVA0 ?></span></td>
                                    </tr>
                                    <!-- Nuevo subtotal general -->
                                    <tr>
                                        <td class="font-weight-bold">SUBTOTAL:</td>
                                        <td><span id="subtotalGeneral"><?php echo $subtotalGeneral ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Valor IVA 12%:</td>
                                        <td><span id="valorIVA12"><?php echo $totalIva12 ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">VALOR TOTAL:</td>
                                        <td><span id="valorTotal"><?php echo $totalFinal ?></span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    
    



    
    <!-- Segundo formulario para recibir los campos ocultos -->
    <form method="post" action="guardar.php">
        <?php
        // Dentro de este formulario, puedes incluir los campos ocultos
        if (!empty($_SESSION['productosSeleccionados'])) {
            foreach ($_SESSION['productosSeleccionados'] as $key => $producto) {
                echo '<input type="hidden" name="productosSeleccionados[' . $key . '][prod_id]" value="' . $producto['prod_id'] . '">';
                echo '<input type="hidden" name="productosSeleccionados[' . $key . '][prod_precio]" value="' . $producto['prod_precio'] . '">';
                echo '<input type="hidden" name="productosSeleccionados[' . $key . '][cantidad]" value="' . $producto['cantidad'] . '">';

                // Calcula el subtotal nuevamente en el segundo formulario
                $subtotal = $producto['prod_precio'] * $producto['cantidad'];
                echo '<input type="hidden" name="productosSeleccionados[' . $key . '][subtotal]" value="' . $subtotal . '">';

                // Calcula el IVA nuevamente en el segundo formulario
                $iva = 0;
                if ($producto['prod_iva'] > 0) {
                    $iva = $subtotal * ($producto['prod_iva'] / 100);
                }
                echo '<input type="hidden" name="productosSeleccionados[' . $key . '][prod_iva]" value="' . $iva . '">';

                // Calcula el total con IVA nuevamente en el segundo formulario
                $totalConIva = $subtotal + $iva;
                echo '<input type="hidden" name="productosSeleccionados[' . $key . '][totalConIva]" value="' . $totalConIva . '">';
            }
        }
     
        
        ?>
         <input type="hidden" name="cliente" id="clienteInput">
         <input type="hidden" name="hiddenFechaHora" id="hiddenFechaHora" value="">
         <input type="hidden" name="formaPago" id="formaPagoInput">
          <input type="hidden" name="subtotalConIVA12" id="subtotalConIVA12" value="<?php echo $subtotalConIVA12 ?>">
          <input type="hidden" name="subtotalConIVA0" id="subtotalConIVA0" value="<?php echo $subtotalConIVA0 ?>">
          <input type="hidden" name="subtotalGeneral" id="subtotalGeneral" value="<?php echo $subtotalGeneral ?>">
          <input type="hidden" name="totalIva12" id="totalIva12" value="<?php echo $totalIva12 ?>">
         <input type="hidden" name="totalFinal" id="totalFinal" value="<?php echo $totalFinal ?>">
        <button type="submit" class="btn" style="background-color: indigo; color: white;" name="guardarEnBaseDeDatos">Generar Factura</button>
    </form>


</div>


<!-- Agrega el enlace al archivo JavaScript de Bootstrap (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
 function guardarFechaEmision() {
    const fechaEmisionInput = document.getElementById('fechaEmision');
    const hiddenFechaHoraInput = document.getElementById('hiddenFechaHora');

    hiddenFechaHoraInput.value = fechaEmisionInput.value;
}

function guardarFormaPagoSeleccionada() {
    var formaPagoSelect = document.getElementById("formaPago");
    var formaPagoInput = document.getElementById("formaPagoInput");

    var formaPagoSeleccionada = formaPagoSelect.value;

    formaPagoInput.value = formaPagoSeleccionada;
}

function guardarClienteSeleccionado() {
    var clienteSelect = document.getElementById("clienteSelect");
    var clienteInput = document.getElementById("clienteInput");
    

    var clienteSeleccionado = clienteSelect.value;

    clienteInput.value = clienteSeleccionado;
    
}

document.addEventListener("DOMContentLoaded", function () {
  var select = document.getElementById("clienteSelect");
  var clienteInfoDiv = document.getElementById("cliente-info");

  select.addEventListener("change", function () {
    var selectedOption = select.options[select.selectedIndex];
    var clienteIndex = selectedOption.getAttribute("data-index");

    if (clienteIndex !== null) {
      // Obtener el cliente seleccionado
      var clienteSeleccionado = <?php echo json_encode($lista_clientes); ?>[clienteIndex];

      // Mostrar la información del cliente
      clienteInfoDiv.innerHTML = `
        <h4>${clienteSeleccionado.cli_nombre}</h4>
        <p>RUC/CC: ${clienteSeleccionado.cli_numero_identificacion}</p>
        <p>Correo: ${clienteSeleccionado.cli_email}</p>
        <p>Teléfono: ${clienteSeleccionado.cli_celular}</p>
        <p>Teléfono: ${clienteSeleccionado.cli_direccion}</p>
      `;
    } else {
      // Limpiar el div si no se ha seleccionado ningún cliente válido
      clienteInfoDiv.innerHTML = "";
    }
  });
});



</script>

<?php include("../../plantillas/pie.php"); ?>
