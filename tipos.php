<?php
require 'conexion.php';
if(!empty($_GET['tipo'])){
    $dato=$_GET['tipo'];
    $sql='SELECT tipo_input FROM `tipos` where lower(tipo_dato) like "'.strtolower($dato).'%"';
    $consulta=$mysqli->query($sql);
    echo json_encode($consulta->fetch_assoc());
}
