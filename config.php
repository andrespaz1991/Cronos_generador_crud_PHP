<?php
require 'conexion.php';
$sql='select * from configuracion';
$consulta=$mysqli->query($sql);
$resultados= $consulta->num_rows;
if($resultados>0){
    while($row=$consulta->fetch_assoc()){
        define("SERVIDOR", $row['servidor']);
        define("USUARIO", $row['usuario']);
        define("CLAVE", $row['clave']);
        define("AUTOR", $row['desarrollador']);
    }
}else{
    define("SERVIDOR", "localhost");
    define("USUARIO", "root");
    define("CLAVE", "");
    define("AUTOR", "Andrés Paz");    
}
?>