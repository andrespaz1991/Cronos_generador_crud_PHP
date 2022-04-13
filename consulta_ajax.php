<?php
require 'conexion.php';
$sql='SELECT plantilla FROM `seguimiento` where LOWER(nombre_proyecto) = "'.strtolower($_POST['proyecto']).'"';
$consulta=$mysqli->query($sql);
if($consulta->num_rows>0){
    while($row=$consulta->fetch_assoc()){
        echo ($row['plantilla']);
        }    
}else{
    echo 0;
}


?>