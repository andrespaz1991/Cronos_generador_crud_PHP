<?php 
require 'conexion.php';
$sql='DROP DATABASE claro;';
$sql2='DELETE FROM `seguimiento`';
$mysqli->query($sql);
$mysqli->query($sql2);
rmDir_rf("proyectos/claro");

function rmDir_rf($carpeta)
    {
      foreach(glob($carpeta . "/*") as $archivos_carpeta){             
        if (is_dir($archivos_carpeta)){
          rmDir_rf($archivos_carpeta);
        } else {
        unlink($archivos_carpeta);
        }
      }
      rmdir($carpeta);
     }
?>