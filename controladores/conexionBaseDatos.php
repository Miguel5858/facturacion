
<?php

$servidor="localhost";
$baseDeDatos="facturacion";
$usuario="root";
$contrasenia="";

try{

//PDO es una forma de conectarse y trabajar con bases de datos utilizando una 
//interfaz común y abstracción para diferentes tipos de bases de datos en PHP.

$conexion=new PDO("mysql:host=$servidor;dbname=$baseDeDatos",$usuario,$contrasenia);
//echo "Conexión realizada...";

}catch(Exception $error){
echo $error->getMessage();
}



?>






