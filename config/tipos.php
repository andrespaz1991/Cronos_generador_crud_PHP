<?php 
ob_start();
echo '<center>';
require("conexion.php");
#require("funciones.php");  
function buscar_tipos( $datos='', $reporte=''){
require_once ("lib/Zebra_Pagination/Zebra_Pagination.php");
$resultados = (isset($_COOKIE['numeroresultadostipos']) ? $_COOKIE['numeroresultadostipos'] : 10);
$paginacion = new Zebra_Pagination();
$paginacion->records_per_page($resultados);
$paginacion->records_per_page($resultados);
$cookiepage="page_numeroresultadostipos";
$funcionjs="buscar();";
$paginacion->fn_js_page("$funcionjs");
$paginacion->cookie_page($cookiepage);
$paginacion->padding(true);
if (isset($_COOKIE["$cookiepage"])) $_GET['page'] = $_COOKIE["$cookiepage"];



if ($reporte=="xls" or  isset($_GET['xls'])){
    header("Content-type: application/vnd.ms-excel");
    if(!empty($_GET['xls'])){
        header("Content-Disposition: attachment; Filename=".$_GET['xls'].".xls");   
    }else{
        header("Content-Disposition: attachment; Filename=tipos.xls");
    }
    
    #header("Location:tipos.php");
    }require("conexion.php");
$sql='select * from   tipos ';
$consulta = $mysqli->query($sql);
$paginacion->records($consulta->num_rows);

$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]='';
$cont =  0;
$sql .= ' WHERE ';
if(!empty($_GET['xls'])){
    $sql.= "  tipos.id_tipo= '".$_GET['xls']."'";
}else{
    foreach ($datos as $id => $dato){
        $sql .= 'concat(LOWER(tipos.id_tipo),"", LOWER(tipos.tipo_dato),"", LOWER(tipos.tipo_input),"", concat(LOWER(tipos.id_tipo),"", LOWER(tipos.tipo_dato),"", LOWER(tipos.tipo_input),"")) LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"' ;
        $cont ++;
        if (count($datos)>1 and count($datos)<>$cont){
            $sql .= ' and ';
        }
        }
        $sql .=  ' ORDER BY tipos.id_tipo desc  ';
        if (!isset($_GET['xls'])){
            $sql.=  "  LIMIT " . (($paginacion->get_page() - 1) * $resultados) . ", " .$resultados;
            #echo $sql;
            }
}

    /*echo $sql;*/ 
    $consulta = $mysqli->query($sql);
    $numero_usuario = $consulta->num_rows;
    $minimo_usuario = (($paginacion->get_page() - 1) * $resultados)+1;
    $maximo_usuario = (($paginacion->get_page() - 1) * $resultados) + $resultados;
    if ($maximo_usuario>$numero_usuario) $maximo_usuario=$numero_usuario;
    $maximo_usuario += $minimo_usuario-1;
    echo "<p>Resultados de $minimo_usuario a $maximo_usuario del total de ".$numero_usuario." en página ".$paginacion->get_page()."</p>";

    ?>
    <div align="center">
  
<table class="table" border='1' id='tbtipos'>
<thead class="thead-dark">
<tr>
<th>Id Tipo</th><th>Tipo Dato</th><th>Tipo Input</th>
<?php if ($reporte==''){ ?>
    <th ><form id='formNuevo' name='formNuevo' method='post' action=tipos.php>
    <input name='cod' type='hidden' id='cod' value='0'>
    <input class="btn btn-light" type='submit' name='submit' id='submit' value='Nuevo'>
    </form>
    </th><th  ><form id="formNuevo" name="formNuevo" method="post" action=tipos.php?xls>
    <input name="cod" type="hidden" id="cod" value="0"><input class="btn btn-success" type="submit" name="submit" id="submit" value="XLS"><a target="_blank" href='reporte_tipos.php'><button type="button" class="btn btn-danger">PDF</button>
        </a></form>
    </th><?php } ?>
    </tr>
    </thead><tbody>
    <?php 
    while($row=$consulta->fetch_assoc()){
        ?>
       <tr>
       <td><?php echo $row['id_tipo']?></td><td><?php echo $row['tipo_dato']?></td><td><?php echo $row['tipo_input']?></td> 
       <?php if ($reporte==''){ ?>
       <td>
       <form id='formModificar' name='formModificar' method='post' action=''tipos.php'>
       <input name='cod' type='hidden' id='cod' value=' <?php echo $row['id_tipo']?>'>
       <input class="btn btn-outline-primary" type='submit' name='submit' id='submit' value='Modificar'>
       <button type="button" class="btn btn-outline-danger" onClick="confirmeliminar('tipos.php',{'del':'<?php echo $row['id_tipo'];?>'},'<?php echo $row['id_tipo'];?>');">Eliminar</button>
       </form>     
       </td><td>
       <a target="_blank" href='tipos.php?xls=<?php echo $row['id_tipo']?>'><button type="button" class="btn btn-success">XLS</button>
       </a><a target="_blank" href="reporte_tipos.php?id=<?php echo $row['id_tipo']?>"> <button type="button" class="btn btn-danger">PDF</button></a></td><?php } ?>
       </tr>
       <?php 
       }/*fin while*/
        ?>
       </tbody>
       </table>
       <div class="text-center">
       <?php
       if (!isset($_GET['xls'])){
       echo $paginacion->render2();
       }
       ?>
       </div>
       
       </div>
       <?php 
    }/*fin function buscar*/
    if (isset($_GET['buscar'])){
        buscar_tipos($_POST['datos']);
    exit();
    }
    if (isset($_GET['xls'])){
     buscar_tipos('','xls');
    exit();
    }

if (isset($_POST['del'])){
 /*Instrucción SQL que permite eliminar en la BD*/ 
 $sql = 'DELETE FROM tipos WHERE id_tipo="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con Ã©xito*/ 
echo '
Registro eliminado
<meta http-equiv="refresh" content="1; url="tipos.php" />
'; 
}else{
?>
Eliminación fallida, por favor compruebe que la usuario no estÃ© en uso
<meta http-equiv="refresh" content="2; url='tipos.php" />
<?php 
}
}
 ?>

 <center>
 <h1>Tipos</h1>
 </center><?php 
 if (isset($_POST['submit'])){
 if ($_POST['submit']=="Registrar"){
  /*recibo los campos del formulario proveniente con el mÃ©todo POST*/ 
  $sql = "INSERT INTO tipos(tipo_dato,tipo_input) Values ('".$_POST["tipo_dato"]."','".$_POST["tipo_input"]."')";

  /*echo $sql;*/
  if ($insertar = $mysqli->query($sql)) {
   /*Validamos si el registro fue ingresado con Ã©xito*/ 
  echo 'Registro exitoso';
  echo '<meta http-equiv="refresh" content="1; url=tipos.php" />';
   }else{ 
  echo 'Registro fallido';
  echo '<meta http-equiv="refresh" content="1; url=tipos.php" />';
  }
  } /*fin Registrar*/ 

  if ($_POST['submit']=="Nuevo" or $_POST['submit']=="Modificar"){
    if ($_POST['submit']=="Modificar"){
     $sql = 'SELECT * FROM tipos WHERE id_tipo ="'.$_POST['cod'].'" Limit 1'; 
        $consulta = $mysqli->query($sql);
     /*echo $sql;*/ 
     $row=$consulta->fetch_assoc();
     $textoh1 ="Modificar";
     $textobtn ="Actualizar";
     }
     if ($_POST['submit']=="Nuevo"){
        $textoh1 ="Registrar";
        $textobtn ="Registrar";
     }
     echo '<form id="form1" name="form1" method="post" action="tipos.php">
     <h1>'.$textoh1.'</h1>';
     
     echo '<form id="form1" name="form1" method="post" action="tipos.php">';
echo '<p><input name="cod" type="hidden" id="cod" value="<?php echo $textobtn ?>" size="120" required></p>';
 echo "<input class='form-control' type='hidden' id='id_tipo' name='id_tipo' value='";if (isset($row["id_tipo"])){
    echo $row["id_tipo"];
} echo "'  ' > <br><label >Tipo Dato</label><br><input  class='form-control' type='text' id='tipo_dato' name='tipo_dato' value='";if (isset($row["tipo_dato"])){
    echo $row["tipo_dato"];
} echo "'  ' > <br><label >Tipo Input</label><br><input  class='form-control' type='text' id='tipo_input' name='tipo_input' value='";if (isset($row["tipo_input"])){
    echo $row["tipo_input"];
} echo "'  ' > <br>";
#print_r($_POST);
 if ($_POST['submit']=="Nuevo"){
    echo '<p><input class="btn btn-outline-secondary" type="submit" name="submit" id="submit" value="Registrar"></p></form>';
 }else{
    echo '<p><input class="btn btn-outline-secondary" type="submit" name="submit" id="submit" value="Actualizar"></p></form>';
 }


} /*fin mixto*/ 
if ($_POST['submit']=='Actualizar'){
    /*recibo los campos del formulario proveniente con el mÃ©todo POST*/ 
    $cod = $_POST['id_tipo'];
    /*Instrucción SQL que permite insertar en la BD */ 
    $sql = "UPDATE tipos SET tipo_dato='".$_POST["tipo_dato"]."',tipo_input='".$_POST["tipo_input"]."' WHERE  id_tipo  = ".$cod." ;" ;
 
 /* echo $sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con Ã©xito*/
echo 'Modificación exitosa';
echo '<meta http-equiv="refresh" content="1"; url="tipos.php" />';
 }else{ 
echo 'Modificación fallida';
}
echo '<meta http-equiv="refresh" content="2"; url="tipos.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
 <center>
<b><label>Buscar: </label></b><input placeholder="Buscar.." type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>
<input type="number" min="0" id="numeroresultadostipos" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultadostipos',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultadostipos',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultadostipos',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>

<span id="txtsugerencias">
<?php 
buscar_tipos();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById("menu_tipos").className ='active '+document.getElementById("menu_tipos").className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ('plantilla.php');
?>
 