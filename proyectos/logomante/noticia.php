<?php 
ob_start();
@session_start();
#require_once("../funciones.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo '<center>';
date_default_timezone_set('America/Bogota');
require("conexion.php");
?>
<style>
.bootstrap-tagsinput .tag {
    margin-right: 2px;
    color: white;
}
.label-info {
    background-color: #5bc0de;
}
.label {
    display: inline;
    padding: .2em .6em .3em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25em;
}
    </style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
<script src="custom_tags_input.js"></script>
<script>
$('#skills').tagsinput({
confirmKeys: [13,44,188],
maxTags: 20
});
    </script>

<script>
function validartexto(texto){
    const editorEle = document.getElementById('noticia');
// Handle the `paste` event
editorEle.addEventListener('paste', function(e) {
    // Prevent the default action

    e.preventDefault();

    // Get the copied text from the clipboard
    const text = (e.clipboardData)
        ? (e.originalEvent || e).clipboardData.getData('text/plain')
        // For IE
        : (window.clipboardData ? window.clipboardData.getData('Text') : '');
    
    if (document.queryCommandSupported('insertText')) {
        document.execCommand('insertText', false, text);
    } else {
        // Insert text at the current position of caret
        const range = document.getSelection().getRangeAt(0);
        range.deleteContents();

        const textNode = document.createTextNode(text);
        range.insertNode(textNode);
        range.selectNodeContents(textNode);
        range.collapse(false);

        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    }
});   
}
</script>
<?php
function buscar_noticia( $datos='', $reporte=''){
require_once("funciones.php");  
    require_once ("lib/Zebra_Pagination/Zebra_Pagination.php");
    $resultados = (isset($_COOKIE['numeroresultadosnoticia']) ? $_COOKIE['numeroresultadosnoticia'] : 10);
    $paginacion = new Zebra_Pagination();
    $paginacion->records_per_page($resultados);
    $paginacion->records_per_page($resultados);
    $cookiepage="page_numeroresultadosnoticia";
    $funcionjs="buscar();";
    $paginacion->fn_js_page("$funcionjs");
    $paginacion->cookie_page($cookiepage);
    $paginacion->padding(true);
    if (isset($_COOKIE["$cookiepage"])) $_GET['page'] = $_COOKIE["$cookiepage"];


if ($reporte=="xls"){
header("Content-Type: application/vnd.ms-excel; charset=ISO-8859-1");
header("Content-Disposition: attachment; Filename=noticia.xls");
}
require("conexion.php");
$sql='select *  from noticia ';
$consulta = $mysqli->query($sql);
$paginacion->records($consulta->num_rows);

$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]='';
$cont =  0;
$sql.= ' WHERE ';
$sql.='';

foreach ($datos as $id => $dato){
    $sql .= 'concat(LOWER(noticia.id_noticia),"", LOWER(noticia.titulo),"", LOWER(noticia.noticia),"", LOWER(noticia.validacion),"", LOWER(noticia.fecha),"", LOWER(noticia.url),"", LOWER(noticia.imagen),"", LOWER(noticia.usuario),"", LOWER(noticia.hora),"", LOWER(noticia.visitas),"", concat(LOWER(noticia.id_noticia),"", LOWER(noticia.titulo),"", LOWER(noticia.noticia),"", LOWER(noticia.validacion),"", LOWER(noticia.fecha),"", LOWER(noticia.url),"", LOWER(noticia.imagen),"", LOWER(noticia.usuario),"", LOWER(noticia.hora),"", LOWER(noticia.visitas),"")) LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"' ;
    $cont ++;
    if (count($datos)>1 and count($datos)<>$cont){
        $sql .= ' and ';
    }
    }
    $sql .=  ' ORDER BY noticia.id_noticia desc ';
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
<table class="table table-bordered" border='1' id='tb'noticia''>
<thead>
<tr>
<th>
Info
</th><th>titulo</th><th>noticia</th><th>validacion</th>
<?php if ($reporte==''){ ?>
    <th><form id='formNuevo' name='formNuevo' method='post' action=noticia.php>
    <input name='cod' type='hidden' id='cod' value='0'>
    <input type='submit' name='submit' id='submit' value='Nuevo'>
    </form>
    </th>
    <th><form id="formNuevo" name="formNuevo" method="post" action="noticia.php?xls">
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
       <td><?php echo $row['id_noticia'].'<br>';
        echo ''.$row['usuario'].'<br>('.$row['visitas'].')';      
       ?></td><td><?php
       if(!empty($row['titulo'])){
        echo $row['titulo'];
       }else{
            echo puntos_suspensivos($row['noticia'],12);
       }
       ?></td><td>
       
       <?php #echo puntos_suspensivos($row['noticia'],12) ?>
       <button title='<?php echo $row['noticia'] ?>' type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?php echo $row['id_noticia']?>">
  Ver 
</button>
<!-- Modal -->
<div class="modal fade" id="exampleModal<?php echo $row['id_noticia']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $row['titulo']?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php echo $row['noticia'].'<br>';
      if(!empty($row['ulr'])){
echo 'url:'.$row['url'];
      }
      if(!empty($row['imagen'])){
        echo '<br> imagen:'.$row['imagen'];
              }
              if(!empty($row['tags'])){
                echo '<br> Tags:'.$row['tags'];
                      }
        

?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>          
</td><td><?php
echo iconografia($row['validacion']); ?><br><?php
if($row['fecha']==date('Y-m-d')){
    echo "Hoy".'<br>'.$row['hora'];
}else{
    echo $row['fecha'].'<br>'.$row['hora'];
}

?></td> 
       <?php if ($reporte==''){ ?>
       <td>
       <form id='formModificar' name='formModificar' method='post' action=''noticia.php'>
       <input name='cod' type='hidden' id='cod' value=' <?php echo $row['id_noticia']?>'>
       <input type='submit' name='submit' id='submit' value='Modificar'>
       </form>
       <form id='formModificar' name='formModificar' method='post' action='test.php'>
       <input name='validacion' type='hidden' id='validacion' value='<?php echo $row['validacion']?>'>
       <input name='cod' type='hidden' id='cod' value=' <?php echo $row['id_noticia']?>'>
       <input type='submit' name='submit' id='submit' value='Descargar'>
       </form>
         
    
    </td>
       <td>
       <input width="30px" type="image" src="img/eliminar.png" onClick="confirmeliminar('noticia.php',{'del':'<?php echo $row['id_noticia'];?>'},'<?php echo $row['id_noticia'];?>');" value="Eliminar">
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
        buscar_noticia($_POST['datos']);
    exit();
    }
    if (isset($_GET['xls'])){
     buscar_noticia('','xls');
    exit();
    }

if (isset($_POST['del'])){
 /*InstrucciÃ³n SQL que permite eliminar en la BD*/ 
 $sql = 'DELETE FROM noticia WHERE id_noticia="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucciÃ³n SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con Ã©xito*/ 
echo '
Registro eliminado
<meta http-equiv="refresh" content="1; url="noticia.php" />
'; 
}else{
?>
Eliminación fallida, por favor compruebe que la usuario no estÃ© en uso
<meta http-equiv="refresh" content="2; url='noticia.php" />
<?php 
}
}
 ?>

 <center>
 <h1>noticia</h1>
 </center><?php 

 require_once 'funciones.php';
 if (isset($_POST['submit'])){
 if ($_POST['submit']=="Registrar"){
    $_POST["noticia"]=quitar_emojis($_POST["noticia"]);
   $_POST["noticia"]=preg_replace("/[\r\n|\n|\r]+/","",$_POST["noticia"]);
  # $_POST["noticia"] = preg_replace('([^A-Za-z0-9 ])', ' ', $_POST["noticia"]);
  $_POST["noticia"]=replace_4byte($_POST["noticia"]);
  $_POST["noticia"]=remove_emoji($_POST["noticia"]);
  if(!empty($_POST['tags'])){
    $_POST['tags']=strtolower($_POST['tags']);
  }
  /*recibo los campos del formulario proveniente con el mÃ©todo POST*/ 
  $sql = "INSERT INTO noticia(titulo,noticia,validacion,fecha,`url`,imagen,usuario,hora,visitas,tags) Values ('".$_POST["titulo"]."','".$_POST["noticia"]."','".$_POST["validacion"]."','".$_POST["fecha"]."','".$_POST["url"]."','".$_POST["imagen"]."','".$_POST["usuario"]."','".$_POST["hora"]."','".$_POST["visitas"]."','".$_POST["tags"]."')";
#echo $sql;
  if ($insertar = $mysqli->query($sql)) {
   /*Validamos si el registro fue ingresado con Ã©xito*/ 
  echo 'Registro exitoso';
  echo '<meta http-equiv="refresh" content="1; url=noticia.php" />';
   }else{ 
  echo 'Registro fallido';
  echo '<meta http-equiv="refresh" content="1; url=noticia.php" />';
  }
  } /*fin Registrar*/ 

  if ($_POST['submit']=="Nuevo" or $_POST['submit']=="Modificar"){
    if ($_POST['submit']=="Modificar"){
     $sql = 'SELECT * FROM noticia WHERE id_noticia ="'.$_POST['cod'].'" Limit 1'; 
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
     echo '<form id="form1" name="form1" method="post" action="noticia.php">
     <h1>'.$textoh1.'</h1>';
     
     echo '<form id="form1" name="form1" method="post" action="noticia.php">';
echo '<p><input name="cod" type="hidden" id="cod" value="<?php echo $textobtn ?>" size="120" required></p>';
 echo "<input class='form-control' type='hidden' id='id_noticia' name='id_noticia' value='";if (isset($row["id_noticia"])){
    echo $row["id_noticia"];
} echo "'  ' > <br><label >titulo</label><br><input class='form-control' type='text' id='titulo' name='titulo' value='";if (isset($row["titulo"])){
    echo $row["titulo"];
} echo "'  ' > <br><label >noticia(*)</label><br><textarea onblur='validartexto(this.value)' autofocus rows='30' cols='20' class='form-control' required name='noticia' id='noticia'>";if (isset($row["noticia"])){
    echo addslashes($row["noticia"]);
} echo " </textarea><br>";

?>

<label>Tags</label>
<div class="col-lg-12">
<input type="text" placeholder='Pulsa tab o , en el movil para registrar' id="skills" name="tags" data-role="tagsinput" value="<?php if(isset($row["tags"])) echo  $row["tags"]; ?>" />
</div>

<?php


echo "<label >validacion</label><br><select  class='form-control' name='validacion' id='validacion' required><option>selecciona</option><option
        "; if (isset($row["validacion"]) and $row["validacion"]=="VERDADERO" ){
            echo "selected";
        } echo "   '  >VERDADERO</option><option
        "; if (isset($row["validacion"]) and $row["validacion"]=="FALSO" ){
            echo "selected";
        } echo "   '  >FALSO</option>
        <option
        "; if (isset($row["validacion"]) and $row["validacion"]=="NOTICIA CADUCADA" ){
            echo "selected";
        } echo "   '  >NOTICIA CADUCADA</option>
        <option
        "; if (isset($row["validacion"]) and $row["validacion"]=="POR CONFIRMAR" ){
            echo "selected";
        } echo "   '  >POR CONFIRMAR</option>
        </select><br><label >fecha</label><br><input class='form-control' type='date' id='fecha' name='fecha' value='";if (isset($row["fecha"])){
    echo $row["fecha"];
}else{
    echo date('Y-m-d');
}


echo "'  ' > <br><label >url</label><br><input class='form-control' type='text' id='url' name='url' value='";if (isset($row["url"])){
    echo $row["url"];
} echo "'  ' > <br><label >imagen</label><br><input class='form-control' type='text' id='imagen' name='imagen' value='";if (isset($row["imagen"])){
    echo $row["imagen"];
} echo "'  ' > <br><label >usuario</label><br><input class='form-control' type='text' id='usuario' name='usuario' value='";if (isset($row["usuario"])){
    echo $row["usuario"];
}else{
    echo "RVI";
} echo "'  ' > <br><label >hora</label><br><input class='form-control' type='time' id='hora' name='hora' value='";if (isset($row["hora"])){
    echo $row["hora"];
}else{
    echo date("H:i:s");
}

echo "'  ' > <br><label >visitas</label><br><input class='form-control' type='number' id='visitas' name='visitas' value='";if (isset($row["visitas"])){
    echo $row["visitas"];
}else{
    echo "0";
}

echo "'  ' > <br>";
#print_r($_POST);
 if ($_POST['submit']=="Nuevo"){
    echo '<p><input type="submit" name="submit" id="submit" value="Registrar"></p></form>';
 }else{
    echo '<p><input type="submit" name="submit" id="submit" value="Actualizar"></p></form>';
 }


} /*fin mixto*/ 
if ($_POST['submit']=='Actualizar'){
    /*recibo los campos del formulario proveniente con el mÃ©todo POST*/ 
    $cod = $_POST['id_noticia'];
    /*InstrucciÃ³n SQL que permite insertar en la BD */ 
    $_POST["noticia"]=quitar_emojis($_POST["noticia"]);
    $_POST["noticia"]=preg_replace("/[\r\n|\n|\r]+/","",$_POST["noticia"]);
    $_POST["noticia"]=replace_4byte($_POST["noticia"]);
    $_POST["noticia"]=remove_emoji($_POST["noticia"]);
    #$_POST["noticia"] = preg_replace('([^A-Za-z0-9 ])', ' ', $_POST["noticia"]);

    #$_POST["url"]=preg_replace("/[\r\n|\n|\r]+/","",$_POST["url"]);
    #$_POST["usuario"]=preg_replace("/[\r\n|\n|\r]+/","",$_POST["usuario"]);
    #$_POST["hora"]=preg_replace("/[\r\n|\n|\r]+/","",$_POST["hora"]);
    #$_POST["visitas"]=preg_replace("/[\r\n|\n|\r]+/","",$_POST["visitas"]);
    if(!empty($_POST['tags'])){
        $_POST['tags']=strtolower($_POST['tags']);
      }

    $sql = "UPDATE noticia SET titulo='".addslashes($_POST["titulo"])."',noticia='".$_POST["noticia"]."',validacion='".$_POST["validacion"]."',fecha='".$_POST["fecha"]."',url='".$_POST["url"]."',imagen='".$_POST["imagen"]."',usuario='".$_POST["usuario"]."',hora='".$_POST["hora"]."',visitas='".$_POST["visitas"]."', tags='".$_POST["tags"]."' WHERE  id_noticia  = ".$cod." ;" ;
 
  #echo $sql;
 /*Se conecta a la BD y luego ejecuta la instrucciÃ³n SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con Ã©xito*/
echo 'Modificación exitosa';
echo '<meta http-equiv="refresh" content="1"; url="noticia.php" />';
 }else{ 
echo 'Modificacion fallida';
}
echo '<meta http-equiv="refresh" content="2"; url="noticia.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
 <center>
<b><label>Buscar: </label></b><input type="search" placeholder='Buscar resultados' id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>

<input type="number" min="0" id="numeroresultadosnoticia" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultadosnoticia',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultadosnoticia',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultadosnoticia',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>

<span id="txtsugerencias">
<?php 
buscar_noticia();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById("menu_noticia").className ='active '+document.getElementById("menu_noticia").className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ('plantilla.php');
?>
 