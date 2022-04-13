<?php 
ob_start();
echo '<center>';
require_once("conexion.php");

#require_once("../funciones.php");  
function buscar_seguimiento( $datos='', $reporte=''){
    require_once ("lib/Zebra_Pagination/Zebra_Pagination.php");
    $resultados = (isset($_COOKIE['numeroresultadosseguimiento']) ? $_COOKIE['numeroresultadosseguimiento'] : 10);
    $paginacion = new Zebra_Pagination();
    $paginacion->records_per_page($resultados);
    $paginacion->records_per_page($resultados);
    $cookiepage="page_numeroresultadosseguimiento";
    $funcionjs="buscar();";
    $paginacion->fn_js_page("$funcionjs");
    $paginacion->cookie_page($cookiepage);
    $paginacion->padding(true);
    if (isset($_COOKIE["$cookiepage"])) $_GET['page'] = $_COOKIE["$cookiepage"];



if ($reporte=="xls"){
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename=seguimiento.xls");
}
require("conexion.php");
$sql='select *  from seguimiento ';
$consulta = $mysqli->query($sql);
$paginacion->records($consulta->num_rows);
$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]='';
$cont =  0;
$sql.= ' WHERE ';
$sql.='';

foreach ($datos as $id => $dato){
    $sql .= 'concat(LOWER(seguimiento.id_seguimiento),"", LOWER(seguimiento.respuesta),"", LOWER(seguimiento.idusuariochat),"", LOWER(seguimiento.nombre),"", LOWER(seguimiento.fechayhora),"", LOWER(seguimiento.detalle),"", concat(LOWER(seguimiento.id_seguimiento),"", LOWER(seguimiento.respuesta),"", LOWER(seguimiento.idusuariochat),"", LOWER(seguimiento.nombre),"", LOWER(seguimiento.fechayhora),"", LOWER(seguimiento.detalle),"")) LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"' ;
    $cont ++;
    if (count($datos)>1 and count($datos)<>$cont){
        $sql .= ' and ';
    }
    }
    $sql .=  ' ORDER BY seguimiento.id_seguimiento desc  ';
    if (!isset($_GET['xls'])){
        $sql .=  "  LIMIT " . (($paginacion->get_page() - 1) * $resultados) . ", " . $resultados;
        #echo $sql;
        }
       
        $consulta = $mysqli->query($sql);
        $numero_usuario = $consulta->num_rows;
        $minimo_usuario = (($paginacion->get_page() - 1) * $resultados)+1;
        $maximo_usuario = (($paginacion->get_page() - 1) * $resultados) + $resultados;
        if ($maximo_usuario>$numero_usuario) $maximo_usuario=$numero_usuario;
        $maximo_usuario += $minimo_usuario-1;
        echo "<p>Resultados de $minimo_usuario a $maximo_usuario del total de ".$numero_usuario." en página ".$paginacion->get_page()."</p>";
    
        
    
    ?>
    <div align='center'>
<table border='1' id='tb'seguimiento''>
<thead>
<tr>
<th>id_seguimiento</th><th>respuesta</th><th>idusuariochat</th><th>nombre</th><th>fechayhora</th><th>detalle</th>
<?php if ($reporte==''){ ?>
    <th><form id='formNuevo' name='formNuevo' method='post' action=seguimiento.php>
    <input name='cod' type='hidden' id='cod' value='0'>
    <input type='submit' name='submit' id='submit' value='Nuevo'>
    </form>
    </th>
    <th><form id="formNuevo" name="formNuevo" method="post" action="seguimiento.php?xls">
    <input name="cod" type="hidden" id="cod" value="0">
    <input type="submit" name="submit" id="submit" value="XLS">
    </form>
    </th>
    <?php } ?>
    </tr>
    </thead><tbody>
    <?php 
    while($row=$consulta->fetch_assoc()){
        ?>
       <tr>
       <td><?php echo $row['id_seguimiento']?></td><td><?php echo $row['respuesta']?></td><td><?php echo $row['idusuariochat']?></td><td><?php echo $row['nombre'].'<br>'.$row['detalle']?>    

       </td><td><?php 
       if($row['fechayhora']==date('Y-m-d')){
        echo "hoy";
       }else{
        echo $row['fechayhora'];
       }
       ?></td><td>
       <button title='<?php echo $row['consulta'] ?>' type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?php echo $row['id_seguimiento']?>">
        Ver 
        </button>
       <!-- Modal -->
<div class="modal fade" id="exampleModal<?php echo $row['id_seguimiento']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo 'Consulta de :'.$row['nombre'] ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php echo $row['consulta'].'<br>'.$row['detalle'].'<br>'.($row['fechayhora']); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>     
       
       <?php ?></td> 
       <?php if ($reporte==''){ ?>
       <td>
       <form id='formModificar' name='formModificar' method='post' action=''seguimiento.php'>
       <input name='cod' type='hidden' id='cod' value=' <?php echo $row['id_seguimiento']?>'>
       <input type='submit' name='submit' id='submit' value='Modificar'>
       </form>
       </td>
       <td>
       <input width="50px" type="image" src="img/eliminar.png" onClick="confirmeliminar('seguimiento.php',{'del':'<?php echo $row['id_seguimiento'];?>'},'<?php echo $row['id_seguimiento'];?>');" value="Eliminar">
       </td>
       <?php } ?>
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
        buscar_seguimiento($_POST['datos']);
    exit();
    }
    if (isset($_GET['xls'])){
     buscar_seguimiento('','xls');
    exit();
    }

if (isset($_POST['del'])){
 /*InstrucciÃ³n SQL que permite eliminar en la BD*/ 
 $sql = 'DELETE FROM seguimiento WHERE id_seguimiento="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucciÃ³n SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con Ã©xito*/ 
echo '
Registro eliminado
<meta http-equiv="refresh" content="1; url="seguimiento.php" />
'; 
}else{
?>
EliminaciÃ³n fallida, por favor compruebe que la usuario no estÃ© en uso
<meta http-equiv="refresh" content="2; url='seguimiento.php" />
<?php 
}
}
 ?>

 <center>
 <h1>seguimiento</h1>
 </center><?php 
 if (isset($_POST['submit'])){
 if ($_POST['submit']=="Registrar"){
  /*recibo los campos del formulario proveniente con el mÃ©todo POST*/ 
  $sql = "INSERT INTO seguimiento(respuesta,idusuariochat,nombre,fechayhora,detalle) Values ('".$_POST["respuesta"]."','".$_POST["idusuariochat"]."','".$_POST["nombre"]."','".$_POST["fechayhora"]."','".$_POST["detalle"]."')";

  /*echo $sql;*/
  if ($insertar = $mysqli->query($sql)) {
   /*Validamos si el registro fue ingresado con Ã©xito*/ 
  echo 'Registro exitoso';
  echo '<meta http-equiv="refresh" content="1; url=seguimiento.php" />';
   }else{ 
  echo 'Registro fallido';
  echo '<meta http-equiv="refresh" content="1; url=seguimiento.php" />';
  }
  } /*fin Registrar*/ 

  if ($_POST['submit']=="Nuevo" or $_POST['submit']=="Modificar"){
    if ($_POST['submit']=="Modificar"){
     $sql = 'SELECT * FROM seguimiento WHERE id_seguimiento ="'.$_POST['cod'].'" Limit 1'; 
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
     echo '<form id="form1" name="form1" method="post" action="seguimiento.php">
     <h1>'.$textoh1.'</h1>';
     
     echo '<form id="form1" name="form1" method="post" action="seguimiento.php">';
echo '<p><input name="cod" type="hidden" id="cod" value="<?php echo $textobtn ?>" size="120" required></p>';
 echo "<input class='form-control' type='hidden' id='id_seguimiento' name='id_seguimiento' value='";if (isset($row["id_seguimiento"])){
    echo $row["id_seguimiento"];
} echo "'  ' > <br><label >respuesta</label><br><input class='form-control' type='text' id='respuesta' name='respuesta' value='";if (isset($row["respuesta"])){
    echo $row["respuesta"];
} echo "'  ' > <br><label >idusuariochat</label><br><input class='form-control' type='' id='idusuariochat' name='idusuariochat' value='";if (isset($row["idusuariochat"])){
    echo $row["idusuariochat"];
} echo "'  ' > <br><label >nombre</label><br><input class='form-control' type='text' id='nombre' name='nombre' value='";if (isset($row["nombre"])){
    echo $row["nombre"];
} echo "'  ' > <br><label >fechayhora</label><br><input class='form-control' type='text' id='fechayhora' name='fechayhora' value='";if (isset($row["fechayhora"])){
    echo $row["fechayhora"];
} echo "'  ' > <br><label >detalle</label><br><input class='form-control' type='text' id='detalle' name='detalle' value='";if (isset($row["detalle"])){
    echo $row["detalle"];
} echo "'  ' > <br>";
#print_r($_POST);
 if ($_POST['submit']=="Nuevo"){
    echo '<p><input type="submit" name="submit" id="submit" value="Registrar"></p></form>';
 }else{
    echo '<p><input type="submit" name="submit" id="submit" value="Actualizar"></p></form>';
 }


} /*fin mixto*/ 
if ($_POST['submit']=='Actualizar'){
    /*recibo los campos del formulario proveniente con el mÃ©todo POST*/ 
    $cod = $_POST['id_seguimiento'];
    /*InstrucciÃ³n SQL que permite insertar en la BD */ 
    $sql = "UPDATE seguimiento SET respuesta='".$_POST["respuesta"]."',idusuariochat='".$_POST["idusuariochat"]."',nombre='".$_POST["nombre"]."',fechayhora='".$_POST["fechayhora"]."',detalle='".$_POST["detalle"]."' WHERE  id_seguimiento  = ".$cod." ;" ;
 
 /* echo $sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucciÃ³n SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con Ã©xito*/
echo 'ModificaciÃ³n exitosa';
echo '<meta http-equiv="refresh" content="1"; url="seguimiento.php" />';
 }else{ 
echo 'Modificacion fallida';
}
echo '<meta http-equiv="refresh" content="2"; url="seguimiento.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
 <center>
<b><label>Buscar: </label></b><input type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>

<input type="number" min="0" id="numeroresultadosseguimiento" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultadosseguimiento',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultadosseguimiento',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultadosseguimiento',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>

<span id="txtsugerencias">
<?php 
buscar_seguimiento();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById("menu_seguimiento").className ='active '+document.getElementById("menu_seguimiento").className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ('plantilla.php');
?>
 