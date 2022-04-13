<?php 
ob_start();
echo '<center>';
require("conexion.php");
#require("funciones.php");  
function buscar_departamentos( $datos='', $reporte=''){
require_once ("lib/Zebra_Pagination/Zebra_Pagination.php");
$resultados = (isset($_COOKIE['numeroresultadosdepartamentos']) ? $_COOKIE['numeroresultadosdepartamentos'] : 10);
$paginacion = new Zebra_Pagination();
$paginacion->records_per_page($resultados);
$paginacion->records_per_page($resultados);
$cookiepage="page_numeroresultadosdepartamentos";
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
        header("Content-Disposition: attachment; Filename=departamentos.xls");
    }
    
    #header("Location:departamentos.php");
    }require("conexion.php");
$sql='select * from   departamentos ';
$consulta = $mysqli->query($sql);
$paginacion->records($consulta->num_rows);

$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]='';
$cont =  0;
$sql .= ' WHERE ';
if(!empty($_GET['xls'])){
    $sql.= "  departamentos.id= '".$_GET['xls']."'";
}else{
    foreach ($datos as $id => $dato){
        $sql .= 'concat(LOWER(departamentos.id),"", LOWER(departamentos.nombre),"", LOWER(departamentos.created_at),"", LOWER(departamentos.updated_at),"", concat(LOWER(departamentos.id),"", LOWER(departamentos.nombre),"", LOWER(departamentos.created_at),"", LOWER(departamentos.updated_at),"")) LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"' ;
        $cont ++;
        if (count($datos)>1 and count($datos)<>$cont){
            $sql .= ' and ';
        }
        }
        $sql .=  ' ORDER BY departamentos.id desc  ';
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
  
<table class="table" border='1' id='tbdepartamentos'>
<thead class="thead-dark">
<tr>
<th>Id</th><th>Nombre</th><th>Created At</th><th>Updated At</th>
<?php if ($reporte==''){ ?>
    <th ><form id='formNuevo' name='formNuevo' method='post' action=departamentos.php>
    <input name='cod' type='hidden' id='cod' value='0'>
    <input class="btn btn-light" type='submit' name='submit' id='submit' value='Nuevo'>
    </form>
    </th><th  ><form id="formNuevo" name="formNuevo" method="post" action=departamentos.php?xls>
    <input name="cod" type="hidden" id="cod" value="0"><input class="btn btn-success" type="submit" name="submit" id="submit" value="XLS"><a target="_blank" href='reporte_departamentos.php'><button type="button" class="btn btn-danger">PDF</button>
        </a></form>
    </th><?php } ?>
    </tr>
    </thead><tbody>
    <?php 
    while($row=$consulta->fetch_assoc()){
        ?>
       <tr>
       <td><?php echo $row['id']?></td><td><?php echo $row['nombre']?></td><td><?php echo $row['created_at']?></td><td><?php echo $row['updated_at']?></td> 
       <?php if ($reporte==''){ ?>
       <td>
       <form id='formModificar' name='formModificar' method='post' action=''departamentos.php'>
       <input name='cod' type='hidden' id='cod' value=' <?php echo $row['id']?>'>
       <input class="btn btn-outline-primary" type='submit' name='submit' id='submit' value='Modificar'>
       <button type="button" class="btn btn-outline-danger" onClick="confirmeliminar('departamentos.php',{'del':'<?php echo $row['id'];?>'},'<?php echo $row['id'];?>');">Eliminar</button>
       </form>     
       </td><td>
       <a target="_blank" href='departamentos.php?xls=<?php echo $row['id']?>'><button type="button" class="btn btn-success">XLS</button>
       </a><a target="_blank" href="reporte_departamentos.php?id=<?php echo $row['id']?>"> <button type="button" class="btn btn-danger">PDF</button></a></td><?php } ?>
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
        buscar_departamentos($_POST['datos']);
    exit();
    }
    if (isset($_GET['xls'])){
     buscar_departamentos('','xls');
    exit();
    }

if (isset($_POST['del'])){
 /*Instrucción SQL que permite eliminar en la BD*/ 
 $sql = 'DELETE FROM departamentos WHERE id="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con Éxito*/  echo '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Correcto!</h5>
                  Registro Eliminado
                  </div>' ;
?> <meta http-equiv="refresh" content="1; url="departamentos.php" />
<?php
}else{ 
 echo  '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Alerta</h5>
              Eliminación Fallida
            </div>';
?> 
<meta http-equiv="refresh" content="1; url='departamentos.php" />
<?php 
}
}
 ?>

 <center>
 <h1>Departamentos</h1>
 </center><?php 
 if (isset($_POST['submit'])){
 if ($_POST['submit']=="Registrar"){
  /*recibo los campos del formulario proveniente con el método POST*/ 
  $sql = "INSERT INTO departamentos(nombre,created_at,updated_at) Values ('".$_POST["nombre"]."','".$_POST["created_at"]."','".$_POST["updated_at"]."')";

  /*echo $sql;*/
  if ($insertar = $mysqli->query($sql)) {
   /*Validamos si el registro fue ingresado con Éxito*/ 
    echo '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Correcto!</h5>
                  Registro Exitoso
                  </div>' 
   ; echo '<meta http-equiv="refresh" content="1; url=departamentos.php" />';
   }else{ 
     echo  '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Alerta</h5>
              Registro fallido
            </div>'
    ; echo '<meta http-equiv="refresh" content="1; url=departamentos.php" />';
  }
  } /*fin Registrar*/ 

  if ($_POST['submit']=="Nuevo" or $_POST['submit']=="Modificar"){
    if ($_POST['submit']=="Modificar"){
     $sql = 'SELECT * FROM departamentos WHERE id ="'.$_POST['cod'].'" Limit 1'; 
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
     echo '<form id="form1" name="form1" method="post" action="departamentos.php">
     <h1>'.$textoh1.'</h1>';
     
     echo '<form id="form1" name="form1" method="post" action="departamentos.php">';
echo '<p><input name="cod" type="hidden" id="cod" value="<?php echo $textobtn ?>" size="120" required></p>';
 echo "<input class='form-control' type='hidden' id='id' name='id' value='";if (isset($row["id"])){
    echo $row["id"];
} echo "'  ' > <br><label >Nombre</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='nombre' name='nombre' value='";if (isset($row["nombre"])){
    echo $row["nombre"];
} echo "'  ' >
            </div>
            <br><label >Created At</label><br>
            <div class='col-3'>
            <input  class='form-control' type='' id='created_at' name='created_at' value='";if (isset($row["created_at"])){
    echo $row["created_at"];
} echo "'  ' >
            </div>
            <br><label >Updated At</label><br>
            <div class='col-3'>
            <input  class='form-control' type='' id='updated_at' name='updated_at' value='";if (isset($row["updated_at"])){
    echo $row["updated_at"];
} echo "'  ' >
            </div>
            <br>";
#print_r($_POST);
 if ($_POST['submit']=="Nuevo"){
    echo '<p><input class="btn btn-outline-secondary" type="submit" name="submit" id="submit" value="Registrar"></p></form>';
 }else{
    echo '<p><input class="btn btn-outline-secondary" type="submit" name="submit" id="submit" value="Actualizar"></p></form>';
 }


} /*fin mixto*/ 
if ($_POST['submit']=='Actualizar'){
    /*recibo los campos del formulario proveniente con el método POST*/ 
    $cod = $_POST['id'];
    /*Instrucción SQL que permite insertar en la BD */ 
    $sql = "UPDATE departamentos SET nombre='".$_POST["nombre"]."',created_at='".$_POST["created_at"]."',updated_at='".$_POST["updated_at"]."' WHERE  id  = ".$cod." ;" ;
 
 /* echo $sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con Éxito*/
  echo '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Correcto!</h5>
                  Modificación Exitosa
                  </div>'  ; echo '<meta http-equiv="refresh" content="1"; url="departamentos.php" />';
 }else{ 
     echo  '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Alerta</h5>
              Modificación Fallida
            </div>'
; } 
echo '<meta http-equiv="refresh" content="1"; url="departamentos.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
 <center>
<b><label>Buscar: </label></b><input placeholder="Buscar.." type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>
<input type="number" min="0" id="numeroresultadosdepartamentos" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultadosdepartamentos',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultadosdepartamentos',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultadosdepartamentos',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>

<span id="txtsugerencias">
<?php 
buscar_departamentos();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById("menu_departamentos").className ='active '+document.getElementById("menu_departamentos").className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ('plantilla.php');
?>
 