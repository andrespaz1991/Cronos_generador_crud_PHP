<?php
require 'funciones.php';
require 'conexion.php';
$ruta="proyectos/";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
#echo "###########";
$opciones=array();
if(!empty($_POST['opcion'])){
  $opciones=$_POST['opcion'];
}else{
    $opciones='';
}
$campos=$_POST['field_name'];
$tipos=$_POST['tipo'];
$modulo=$_POST['modulo'];

if(isset($_POST['xls'])){
  $xls=1;
}else{
  $xls=0;
}
if(isset($_POST['pdf'])){
  $pdf=1;
}else{
  $pdf=0;
}

$tipoCampo=$_POST['TipoCampo'];
$primaria=$_POST['primaria'];
$auto=$_POST['auto'];

if(isset($_POST['nproyecto'])){
    $existencia=consultar_proyecto($_POST['nproyecto']);
 if($existencia<1){
    crear_proyecto($_POST['nproyecto'],$_POST['plantilla']);
    $rutaconexion=$ruta.$modulo;
    crear_modulo($ruta,$_POST['nproyecto'],$campos,$tipos,$modulo,$_POST['nproyecto'],$tipoCampo,$primaria,$auto,$opciones,$xls,$pdf);
    fopen($ruta.$_POST['nproyecto']."/index.php","w");
    $tabla=$modulo;

   # header("location:".$ruta.$_POST['nproyecto']);
   # header("location:https://cronos.educatec.com.co/proyectos/a/a.php");
    
}else{
    crear_proyecto($_POST['nproyecto'],$_POST['plantilla']);
    crear_modulo($ruta,$_POST['nproyecto'],$campos,$tipos,$modulo,$_POST['nproyecto'],$tipoCampo,$primaria,$auto,$opciones,$xls,$pdf);
   # header("location:".$ruta.$_POST['nproyecto']);
   # header("location:https://cronos.educatec.com.co/proyectos/b/b.php");
}
   
}
?>
<!--meta http-equiv="refresh" content="0;url=/cronos/proyectos/"<?php #echo $_POST['nproyecto']?>-->
<?php
header("location:proyectos/".$_POST['nproyecto'].'/'.$modulo.'.php');
?>