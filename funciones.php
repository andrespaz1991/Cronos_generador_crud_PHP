<?php 
function consultar_preferencias($parametro){
    require 'conexion.php';
    $sql='select '.$parametro.' from preferencias';
    $consulta=$mysqli->query($sql);
    if($consulta->num_rows>0){
        while($row=$consulta->fetch_Assoc()){
            return $row[$parametro];
        }
    }else{
        return "";
    }
    
}
function input($tipo="",$nombre="",$valor="",$label="",$opciones,$contadorinput){
$tamañoinput= consultar_preferencias('input_size');
$label=str_replace ( '_',' ',$label );
$condiciones='";if (isset($row["'.$nombre.'"])){
    echo $row["'.$nombre.'"];
} echo "\'  ';
if($tipo<>"select"){
   if($tipo=="hidden"){ 
    $html='';
    $html.="<input class='form-control' type='".$tipo."' id='".$nombre."' name='".$nombre."' value='".($condiciones)."' > <br>";
    #$html='<label >'.$label.'</label><br>';
   }else{
    $html='<label >'.ucwords($label).'</label><br>'; 
        if($tipo=="textarea"){
            $html.="<div class='col-".$tamañoinput."'>";
            $html.= "<textarea class='form-control' name='".$nombre."' id='".$nombre."'>".$valor."</textarea><br>";
            $html.="</div>";
        }else{
            $html.="
            <div class='col-".$tamañoinput."'>
            <input  class='form-control' type='".$tipo."' id='".$nombre."' name='".$nombre."' value='".($condiciones)."' >
            </div>
            <br>";
        }
     }
    }

if($tipo=="select"){
    $html.= "
    <div class='col-".$tamañoinput."'>
    <select class='form-control' name='".$nombre."' id='".$nombre."'><option>selecciona</option>";
    foreach($opciones[$contadorinput] as $registro =>$opcion){
    // foreach($opcion as $aa =>$bb){
        $html.='<option
        "; if (isset($row["'.$nombre.'"]) and $row["'.$nombre.'"]=="'.$opcion.'" ){
            echo "selected";
        } echo "   \'  >'.$opcion.'</option>';
      //  }
        
       }
    $html.= "</select></div><br>";  
}
    return $html;
}

function crear_conexion($bd,$ruta){
$conexion='$mysqli= new mysqli("localhost","root","","'.$bd.'");
if (mysqli_connect_errno()){
	echo "error";
}
if (isset($mysqli)) {
	mysqli_set_charset($mysqli,"utf8");
}';
$miArchivo = fopen($ruta, "a+");
fwrite($miArchivo,$conexion);
fclose($miArchivo);    
}

function crear_menu(){
    return $tabla="CREATE TABLE `menu_items` (
        `id_menu_items` int(11) NOT NULL,
        `menu_item_name` varchar(255) NOT NULL,
        `menu_url` varchar(255) NOT NULL
      );";
      
}
function plantilla_mas_usada(){
    require 'conexion.php';
$sql='SELECT plantilla,count(id_proyecto) as cantidad FROM `seguimiento` GROUP by plantilla;';
$consulta=$mysqli->query($sql);
$informacion=array();
while($row=$consulta->fetch_assoc()){
    $informacion[]=$row['plantilla'];
    $informacion[]=$row['cantidad'];
}
return $informacion;
}
function base_datos($tablad,$campos,$tipo,$tipocampo,$primaria,$auto,$proyecto){

for ($k=0; $k <count($campos) ; $k++) { 
    $campost['campo'][]=$campos[$k];
}
foreach($tipo as $key=>$valor){
    $tipost['tipo'][]=$valor;
}
foreach($tipocampo as $key=>$valor){
    $tipocampost['tipocampo'][]=$valor;
}
$tabla= "CREATE TABLE $tablad (";
$conta=0;
require 'conexion.php';
$prim='';
for ($i=0; $i <(count($campos)); $i++) { 
    if($primaria=='on' and $i==0){
        $prim.=',PRIMARY KEY ('.$campost['campo'][$i].')) ';
    }

if($auto=='on' and $i==0){
    $auto='AUTO_INCREMENT';
}else{
    $auto='';
}    
$detalle='';
if($primaria=='on' and $i==0){
$detalle='PRIMARY';
}else{
$detalle='';
}
$tabla.=( $campost['campo'][$i]).' '.($tipost['tipo'][$i].' '.$auto).',';
$sqlInfoTabla=' INSERT INTO `info_tablas`(`campo`, `tipo_dato`, `tipo_campo`, `tabla`,`adicional`,`proyecto`) VALUES ("'.$campost['campo'][$i].'","'.$tipost['tipo'][$i].'","'.$tipocampost['tipocampo'][$i].'","'.$tablad.'","'.$detalle.'","'.$proyecto.'")';
$mysqli->query($sqlInfoTabla);
}
$tabla = substr($tabla, 0, -1);
$tabla.=$prim;
$tabla.= "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";  
echo $tabla;
return $tabla;
}

function validarprimaria($proyecto){
    require 'conexion.php';
    $sql="SELECT * FROM `info_tablas` WHERE tabla='".$proyecto."' limit 1";
    $consulta=$mysqli->query($sql);
    return $consulta->num_rows;
}

function listar_directorios_ruta($ruta){
    // abrir un directorio y listarlo recursivo
    if (is_dir($ruta) and $ruta<>"img") {
       if ($dh = opendir($ruta)) {
          while (($file = readdir($dh)) !== false) {
             //esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
             //mostraría tanto archivos como directorios
             //echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file);
             if (is_dir($ruta.$file) && $file!="." && $file!=".."){
                //solo si el archivo es un directorio, distinto que "." y ".."
                if($file<>"js" and $file<>"css" and $file<>"img"){                 
      echo ' 
     <a href="proyectos/'.$file.'/"> <div class="col-md-6 col-lg-4 wow bounceInUp" data-aos="zoom-in" data-aos-delay="100">
      <div class="box">
      <img width="40%" src="multimedia/proyecto.jpg"></img>
        <h4 class="title"><a href="">'.$file.'</a></h4>
        <a href="index.php?id='.$file.'">Descargar</a>
        <a onclick="eliminar(\''.$file.'\')" >Borrar</a>
      </div>
    </div></a>';                 
/*                   
echo " <label align='center'>".$file."</label>
                    
                    <a  href='proyectos/".$file."'>
                    <img width='20%' src='multimedia/proyecto.jpg'></img></a>";
  */                  
                   
                   // listar_directorios_ruta($ruta . $file . "/");
                }
              
             }
          }
       closedir($dh);
       }
    }else
       echo "<br>No es ruta valida";
 }


function borrar_proyecto($id){
    require 'conexion.php';  
    $sql1='DELETE FROM `seguimiento` WHERE nombre_proyecto="'.$id.'";';
    $sql2='DELETE FROM `info_tablas` WHERE proyecto="'.$id.'";';
    $sql3='DELETE FROM `seguimiento` WHERE nombre_proyecto="'.$id.'";';
    $sql4='DELETE FROM `menu_items` WHERE menu_item_name="'.$id.'";'; 
    $sql5='DROP TABLE '.$id.''; 
    $sql6='DROP DATABASE '.$id.''; 

    $mysqli->query($sql1);
    $mysqli->query($sql2);
    $mysqli->query($sql3);
    $mysqli->query($sql4);
    $mysqli->query($sql5);
    $mysqli->query($sql6);

    recurse_delete_dir('proyectos/'.$id.'/');
    if (file_exists('resultados/'.$id.'.zip')) {
        unlink('resultados/'.$id.'.zip');       
    }
    
}


function recurse_delete_dir(string $dir) : int {
    $count = 0;

    // ensure that $dir ends with a slash so that we can concatenate it with the filenames directly
    $dir = rtrim($dir, "/\\") . "/";

    // use dir() to list files
    $list = dir($dir);

    // store the next file name to $file. if $file is false, that's all -- end the loop.
    while(($file = $list->read()) !== false) {
        if($file === "." || $file === "..") continue;
        if(is_file($dir . $file)) {
            unlink($dir . $file);
            $count++;
        } elseif(is_dir($dir . $file)) {
            $count += recurse_delete_dir($dir . $file);
        }
    }

    // finally, safe to delete directory!
    rmdir($dir);

    return $count;
}



 function full_copy( $source, $target ) {
    if ( is_dir( $source ) ) {
        @mkdir( $target );
        $d = dir( $source );
        while ( FALSE !== ( $entry = $d->read() ) ) {
            if ( $entry == '.' || $entry == '..' ) {
                continue;
            }
            $Entry = $source . '/' . $entry; 
            if ( is_dir( $Entry ) ) {
                full_copy( $Entry, $target . '/' . $entry );
                continue;
            }
            copy( $Entry, $target . '/' . $entry );
        }
 
        $d->close();
    }else {
        copy($source,$target );
    }
}

function crear_proyecto($nuevoproyecto,$plantilla="plantilla1"){
    echo "plantilla".$plantilla;
    if (!is_dir("proyectos/".$nuevoproyecto)) {
        mkdir("proyectos/".$nuevoproyecto,0777);
/////////
if($plantilla=="plantilla1"){
    $estilo ='css';
    $rutaeslito=$plantilla.'/'.$estilo;
    $destinocss="proyectos/".$nuevoproyecto.'/'.$estilo;
    $destinoimg="proyectos/".$nuevoproyecto.'/img';
    $js ='js';
    $rutajs =$plantilla.'/'.$js ;
    $rutaimg =$plantilla.'/img' ;
    $destinojs="proyectos/".$nuevoproyecto.'/'.$js;
    $rutaheader =$plantilla.'/header.php'; 
    $rutaplantilla =$plantilla.'/plantilla.php'; 
    $rutafooter =$plantilla.'/footer.php'; 
    $rutamenu=$plantilla.'/menu.php'; 
    
    if (!is_dir("proyectos/".$nuevoproyecto.'/'.$estilo)) {
    mkdir("proyectos/".$nuevoproyecto.'/'.$estilo);
    }
    if (!is_dir("proyectos/".$nuevoproyecto.'/'.$js)) {
        mkdir("proyectos/".$nuevoproyecto.'/'.$js);
    }
    if (!is_dir("proyectos/".$nuevoproyecto.'/'.$rutaimg)) {
       # echo "proyectos/".$nuevoproyecto.'/img';
         mkdir("proyectos/".$nuevoproyecto.'/img');
    }
        
    full_copy($rutaimg,$destinoimg);
    full_copy($rutaeslito,$destinocss);
    full_copy($rutajs,$destinojs);
    full_copy($rutamenu,"proyectos/".$nuevoproyecto."/menu.php");
    full_copy($rutafooter,"proyectos/".$nuevoproyecto."/footer.php");
    full_copy($rutaplantilla,"proyectos/".$nuevoproyecto."/plantilla.php");
    full_copy($rutaheader,"proyectos/".$nuevoproyecto."/header.php");   
}
//////////////
//////////// plantilla 2


if($plantilla=="plantilla2"){
    //El destino donde se guardara la copia
    full_copy($plantilla,"proyectos/".$nuevoproyecto);
}

////////////


        require 'conexion.php';
        $sqlbd="CREATE DATABASE IF NOT EXISTS $nuevoproyecto;";
        $mysqli->query($sqlbd);
        require_once 'config.php';
        $conexion='<?php $mysqli= new mysqli("'.SERVIDOR.'","'.USUARIO.'","'.CLAVE.'","'.$nuevoproyecto.'");
        if (mysqli_connect_errno()){
            echo "error";
        }
        if (isset($mysqli)) {
            mysqli_set_charset($mysqli,"utf8");
            if (!defined("PROYECTO")) {
                define("PROYECTO","'.strtoupper($nuevoproyecto).'");
            }
            
        } ?> ';
        $miArchivo = fopen("proyectos/".$nuevoproyecto.'/conexion.php', "a+");
        fwrite($miArchivo,$conexion);
        fclose($miArchivo);    
        require 'conexion.php';
        $sql="INSERT INTO `seguimiento`( `nombre_proyecto`, `fecha_creacion`,plantilla) VALUES ('".$_POST['nproyecto']."','".date("Y-m-d")."','".$_POST['plantilla']."')";
        $insertar=$mysqli->query($sql);
        }
}

function crear_modulo($ruta='',$tabla='',$campos='',$tipos='',$modulo='',$proyecto='',$tipoCampo='',$primaria='',$auto='',$opciones='',$DatoReporte=1,$pdf=1){
    foreach( $campos as $key =>$valor){
        $campos['campo'][]=$valor;
    }
    foreach($tipos as $key =>$valor){
        $tipo['tipo'][]=$valor;
    }
    foreach($tipoCampo as $key =>$valor){
        $tipoCampo['tipoCampo'][]=$valor;
    }
    require_once("funciones.php");
    require $ruta.$tabla.'/conexion.php';
    $crearbd=(base_datos($modulo,$campos['campo'],$tipo['tipo'],$tipoCampo['tipoCampo'],$primaria,$auto,$proyecto));
    $tablasola=$modulo;
    $modulosinextension=$modulo;
    $modulo=$modulo.".php";
    $rutamodulo=$ruta.$proyecto.'/'.$modulo;
    #echo $ruta.$proyecto.'/'.$modulo;
    $miArchivo = fopen($ruta.$proyecto.'/'.$modulo, "w") or die("No se puede abrir/crear el archivo!");
    require_once("funciones.php");
    require $ruta.$tabla.'/conexion.php';
    #base_datos();
    //Creamos una variable personalizada
    if(isset($modulo)){
    $tabla=$tablasola;
    }else{
        $tabla ="persona";
    }
    require_once $ruta.$proyecto.'/'.'conexion.php';
    $titulo_funcion="buscar_".$tabla;
    $menutabla="menu_".$tabla;
    $nresultados="numeroresultados".$tabla;
    $crear_menu=crear_menu();
    #echo $crearbd;
    $mysqli->query($crearbd);
    $mysqli->query($crear_menu);
    $resultado00 = $mysqli->query("ALTER TABLE `menu_items`
    ADD PRIMARY KEY (`id_menu_items`);");
    $resultado0 = $mysqli->query("ALTER TABLE `menu_items`
    MODIFY `id_menu_items` int(11) NOT NULL AUTO_INCREMENT;");
    $archivophp=str_replace(".php","",$modulo);

    $sqlin="INSERT INTO `menu_items`(`menu_item_name`, `menu_url`) VALUES ('".$archivophp."','".$modulo."')";
 $resultado01 = $mysqli->query($sqlin);
    
    $resultado = $mysqli->query("SHOW COLUMNS FROM $tabla");
    $resultado2 = $mysqli->query("SHOW COLUMNS FROM $tabla");
    #echo "SHOW COLUMNS FROM $tabla";
    $primer_campo=($resultado2->fetch_assoc()['Field']);
    $sqltabla1="concat(";
    $encabezadotabla="";
    $rotulos="";
    $rotulos2="";
    $camposinsert="";
    $camposinsert.="(";
    $valoresinsert="";
    $listadodecampos="";
    $sqlupdate='UPDATE '.$tabla.' SET ';
    $sqlupdate2="";
    $contadorinput=0;
    while($row=$resultado->fetch_assoc()){
        $contadorinput++;
      $Tipoinput = consultarInput($tabla,$row['Field']);
    $listadodecampos.=input($Tipoinput,$row['Field'],'',$row['Field'],$opciones,$contadorinput);
        $campo=$row['Field'];
        $encabezadotabla.="<th>".ucwords(str_replace("_", " ",$row['Field']))."</th>";
        if($contadorinput==1 and $auto=='on'){
            
        }else{
        $sqlupdate2.=$row['Field'].'=';
        $sqlupdate2.="'";
        $sqlupdate2.='".$_POST["'.$campo.'"]."';
        $sqlupdate2.="',";
        $camposinsert.=$row['Field'].',';
        $valoresinsert.="'";
        $valoresinsert.='".$_POST["'.$campo.'"]."';
        $valoresinsert.="',";
        }

        $rotulos.="<td><?php echo \$row['".$row['Field']."']?></td>";
        $rotulos2.="<td>'.\$row['".$row['Field']."'].'</td>";
        $sqltabla1.="LOWER(".$tabla.".".$row['Field']."),\"\", ";
    }
    $sqlupdate=$sqlupdate.$sqlupdate2;
    $sqlupdate= substr($sqlupdate, 0, -1);
    ###aqui
    
    $sqlupdate.=" WHERE  ".$primer_campo."  = \".\$cod.\" ;";
    $valoresinsert = substr($valoresinsert, 0, -1);
    $camposinsert = substr($camposinsert, 0, -1);
    $camposinsert.=")";
    $valoresinsert='('.$valoresinsert.')';
    $sqltabla1.= substr($sqltabla1, 0, -1);
    $sqltabla1.=')) LIKE ';
    $sqltabla1 = str_replace(',)',')', $sqltabla1);
    $contenido=contenido($tabla,'',$sqltabla1,$primer_campo,$encabezadotabla,$rotulos,$titulo_funcion,$camposinsert,$valoresinsert,$listadodecampos,$sqlupdate,$nresultados,$menutabla,$DatoReporte,$pdf);  
    $pdf=crear_pdf($tabla,'',$sqltabla1,$primer_campo,$encabezadotabla,$rotulos2,$titulo_funcion,$camposinsert,$valoresinsert,$listadodecampos,$sqlupdate,$nresultados,$menutabla,$DatoReporte);
    fwrite($miArchivo, $contenido);
    fclose($miArchivo);    
    if(!file_exists()){
        $miArchivo2 = fopen("proyectos/".$proyecto.'/reporte_'.$tabla.'.php', "a+");
        fwrite($miArchivo2,$pdf);
        fclose($miArchivo2);    
    }
    
}

function crear_pdf($tabla,$dat,$sqltabla1,$primer_campo,$encabezadotabla,$rotulos,$titulo_funcion,$camposinsert,$valoresinsert,$listadodecampos,$sqlupdate,$nresultados,$menutabla,$DatoReporte){
$php=" ?> <?php require_once 'lib/dompdf/vendor/autoload.php';";
$php.="require(\"conexion.php\");
\$sql=\"select * from ".$tabla."\";
if(isset(\$_GET['id'])){
\$sql.= \" WHERE $tabla.$primer_campo= '\".\$_GET['id'].\"'\";
}
 \$sql.=  \" ORDER BY $tabla.$primer_campo desc \";
/*echo \$sql;*/ 
\$consulta = \$mysqli->query(\$sql);
\$html='
<h1 align=\"center\">".ucwords($tabla)."</h1>
<table  align=\"center\" border=\"1\" id=\"tb.$tabla\">
<thead>
<tr>
".$encabezadotabla."
    </tr>
    </thead><tbody>';
        while(\$row=\$consulta->fetch_assoc()){ 
        \$html.='<tr>
       ".$rotulos."
       </tr>';
       }/*fin while*/
          \$html.='</tbody>
       </table>'; ";
$php.=" 
use Dompdf\Dompdf;
set_time_limit(27000);
\$mipdf = new DOMPDF();
\$mipdf->set_paper('A4', 'landascape'); 
\$mipdf->load_html(\$html,'UTF-8');
\$mipdf->render();
\$output = \$mipdf->output();
\$mipdf->stream('".$tabla.".pdf', array(\"Attachment\" => 0) );";
return $php;
}
function consultarInput($tabla,$campo){
require 'conexion.php';
$sql='select tipo_campo from info_tablas where tabla="'.$tabla.'" and campo="'.$campo.'"';
$consultar= $mysqli->query($sql);
if($row= $consultar->fetch_assoc()){
return $row['tipo_campo'];
}
}

function consultar_proyecto($proyecto){
    require 'conexion.php';
    $sqlproyecto="select * from seguimiento where lower(nombre_proyecto)=lower('".$proyecto."')";
    $consultaproyecto=$mysqli->query($sqlproyecto);
    return $existencia= $consultaproyecto->num_rows;
}


function contenido($tabla,$sqltabla,$sqltabla1,$primer_campo,$encabezadotabla,$rotulos,$titulo_funcion,$camposinsert,$valoresinsert,$listadodecampos,$sqlupdate,$nresultados,$menutabla,$DatoReporte=1,$pdf=1){
$nresultados="numeroresultados".$tabla;
$time_insert=consultar_preferencias('time_insert');
$time_update=consultar_preferencias('time_update');
$time_delete=consultar_preferencias('time_delete');

$php = "<?php 
ob_start();
echo '<center>';
require(\"conexion.php\");
#require(\"funciones.php\");  
function ".$titulo_funcion."( \$datos='', \$reporte=''){";
$php.="
require_once (\"lib/Zebra_Pagination/Zebra_Pagination.php\");
\$resultados = (isset(\$_COOKIE['numeroresultados".$tabla."']) ? \$_COOKIE['numeroresultados".$tabla."'] : 10);
\$paginacion = new Zebra_Pagination();
\$paginacion->records_per_page(\$resultados);
\$paginacion->records_per_page(\$resultados);
\$cookiepage=\"page_numeroresultados".$tabla."\";
\$funcionjs=\"buscar();\";
\$paginacion->fn_js_page(\"\$funcionjs\");
\$paginacion->cookie_page(\$cookiepage);
\$paginacion->padding(true);
if (isset(\$_COOKIE[\"\$cookiepage\"])) \$_GET['page'] = \$_COOKIE[\"\$cookiepage\"];



if (\$reporte==\"xls\" or  isset(\$_GET['xls'])){
    header(\"Content-type: application/vnd.ms-excel\");
    if(!empty(\$_GET['xls'])){
        header(\"Content-Disposition: attachment; Filename=\".\$_GET['xls'].\".xls\");   
    }else{
        header(\"Content-Disposition: attachment; Filename=".$tabla.".xls\");
    }
    
    #header(\"Location:".$tabla.".php\");
    }";
$php.="require(\"conexion.php\");
\$sql='select * from   ".$tabla." ';
\$consulta = \$mysqli->query(\$sql);
\$paginacion->records(\$consulta->num_rows);

\$datosrecibidos = \$datos;
\$datos = explode(\" \",\$datosrecibidos);
\$datos[]='';
\$cont =  0;
\$sql .= ' WHERE ';
if(!empty(\$_GET['xls'])){
    \$sql.= \"  $tabla.$primer_campo= '\".\$_GET['xls'].\"'\";
}else{
    foreach (\$datos as \$id => \$dato){
        \$sql .= '".$sqltabla1."\"%'.mb_strtolower(\$dato, 'UTF-8').'%\"' ;
        \$cont ++;
        if (count(\$datos)>1 and count(\$datos)<>\$cont){
            \$sql .= ' and ';
        }
        }
        \$sql .=  ' ORDER BY ".$tabla.".".$primer_campo." desc  ';
        if (!isset(\$_GET['xls'])){
            \$sql.=  \"  LIMIT \" . ((\$paginacion->get_page() - 1) * \$resultados) . \", \" .\$resultados;
            #echo \$sql;
            }
}

    /*echo \$sql;*/ 
    \$consulta = \$mysqli->query(\$sql);
    \$numero_usuario = \$consulta->num_rows;
    \$minimo_usuario = ((\$paginacion->get_page() - 1) * \$resultados)+1;
    \$maximo_usuario = ((\$paginacion->get_page() - 1) * \$resultados) + \$resultados;
    if (\$maximo_usuario>\$numero_usuario) \$maximo_usuario=\$numero_usuario;
    \$maximo_usuario += \$minimo_usuario-1;
    echo \"<p>Resultados de \$minimo_usuario a \$maximo_usuario del total de \".\$numero_usuario.\" en página \".\$paginacion->get_page().\"</p>\";

    ?>
    <div align=\"center\">
  
<table class=\"table\" border='1' id='tb".$tabla."'>
<thead class=\"thead-dark\">
<tr>
".$encabezadotabla."
<?php if (\$reporte==''){ ?>
    <th ><form id='formNuevo' name='formNuevo' method='post' action=".$tabla.".php>
    <input name='cod' type='hidden' id='cod' value='0'>
    <input class=\"btn btn-light\" type='submit' name='submit' id='submit' value='Nuevo'>
    </form>
    </th>";
    if($DatoReporte==1 or $pdf==1){
    $php.="<th  ><form id=\"formNuevo\" name=\"formNuevo\" method=\"post\" action=".$tabla.".php?xls>
    <input name=\"cod\" type=\"hidden\" id=\"cod\" value=\"0\">";
     if($DatoReporte==1){
        $php.="<input class=\"btn btn-success\" type=\"submit\" name=\"submit\" id=\"submit\" value=\"XLS\">";
    }
    if($pdf==1){
                $php.="<a target=\"_blank\" href='reporte_".$tabla.".php'><button type=\"button\" class=\"btn btn-danger\">PDF</button>
        </a>";
    }

    $php.="</form>
    </th>";
}
    $php.="<?php } ?>
    </tr>
    </thead><tbody>
    <?php 
    while(\$row=\$consulta->fetch_assoc()){
        ?>
       <tr>
       ".$rotulos." 
       <?php if (\$reporte==''){ ?>
       <td>
       <form id='formModificar' name='formModificar' method='post' action=''".$tabla.".php'>
       <input name='cod' type='hidden' id='cod' value=' <?php echo \$row['".$primer_campo."']?>'>
       <input class=\"btn btn-outline-primary\" type='submit' name='submit' id='submit' value='Modificar'>
       <button type=\"button\" class=\"btn btn-outline-danger\" onClick=\"confirmeliminar('".$tabla.".php',{'del':'<?php echo \$row['".$primer_campo."'];?>'},'<?php echo \$row['".$primer_campo."'];?>');\">Eliminar</button>
       </form>     
       </td>";
       if($DatoReporte==1 or $pdf==1){
       $php.="<td>
       <a target=\"_blank\" href='".$tabla.".php?xls=<?php echo \$row['".$primer_campo."']?>'>";
       if($DatoReporte==1){
       $php.="<button type=\"button\" class=\"btn btn-success\">XLS</button>
       </a>";
       }
       if($pdf==1){
       $php.="<a target=\"_blank\" href=\"reporte_".$tabla.".php?id=<?php echo \$row['".$primer_campo."']?>\"> <button type=\"button\" class=\"btn btn-danger\">PDF</button></a>"    ;
       }
       $php.="</td>";
    }
    $php.="<?php } ?>
       </tr>
       <?php 
       }/*fin while*/
        ?>
       </tbody>
       </table>
       <div class=\"text-center\">
       <?php
       if (!isset(\$_GET['xls'])){
       echo \$paginacion->render2();
       }
       ?>
       </div>
       
       </div>
       <?php 
    }/*fin function buscar*/
    if (isset(\$_GET['buscar'])){
        ".$titulo_funcion."(\$_POST['datos']);
    exit();
    }
    if (isset(\$_GET['xls'])){
     ".$titulo_funcion."('','xls');
    exit();
    }

if (isset(\$_POST['del'])){
 /*Instrucción SQL que permite eliminar en la BD*/ 
 \$sql = 'DELETE FROM ".$tabla." WHERE ".$primer_campo."=\"'.\$_POST['del'].'\"';
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/
if (\$eliminar = \$mysqli->query(\$sql)){
 /*Validamos si el registro fue eliminado con Éxito*/ ".notificaciones("Registro Eliminado","verde").";
?> <meta http-equiv=\"refresh\" content=\"".$time_delete."; url=\"".$tabla.".php\" />
<?php
}else{ 
".notificaciones("Eliminación Fallida","rojo").";
?> 
<meta http-equiv=\"refresh\" content=\"".$time_delete."; url='".$tabla.".php\" />
<?php 
}
}
 ?>

 <center>
 <h1>".ucwords($tabla)."</h1>
 </center><?php 
 if (isset(\$_POST['submit'])){
 if (\$_POST['submit']==\"Registrar\"){
  /*recibo los campos del formulario proveniente con el método POST*/ 
  \$sql = \"INSERT INTO ".$tabla.$camposinsert.' Values '.$valoresinsert."\";

  /*echo \$sql;*/
  if (\$insertar = \$mysqli->query(\$sql)) {
   /*Validamos si el registro fue ingresado con Éxito*/ 
   ".notificaciones("Registro Exitoso","verde")."
   ; echo '<meta http-equiv=\"refresh\" content=\"".$time_insert."; url=".$tabla.".php\" />';
   }else{ 
    ".notificaciones("Registro fallido","rojo")."
    ; echo '<meta http-equiv=\"refresh\" content=\"".$time_insert."; url=".$tabla.".php\" />';
  }
  } /*fin Registrar*/ 

  if (\$_POST['submit']==\"Nuevo\" or \$_POST['submit']==\"Modificar\"){
    if (\$_POST['submit']==\"Modificar\"){
     \$sql = 'SELECT * FROM ".$tabla." WHERE ".$primer_campo." =\"'.\$_POST['cod'].'\" Limit 1'; 
        \$consulta = \$mysqli->query(\$sql);
     /*echo \$sql;*/ 
     \$row=\$consulta->fetch_assoc();
     \$textoh1 =\"Modificar\";
     \$textobtn =\"Actualizar\";
     }
     if (\$_POST['submit']==\"Nuevo\"){
        \$textoh1 =\"Registrar\";
        \$textobtn =\"Registrar\";
     }
     echo '<form id=\"form1\" name=\"form1\" method=\"post\" action=\"$tabla.php\">
     <h1>'.\$textoh1.'</h1>';
     
     echo '<form id=\"form1\" name=\"form1\" method=\"post\" action=\"$tabla.php\">';
echo '<p><input name=\"cod\" type=\"hidden\" id=\"cod\" value=\"<?php echo \$textobtn ?>\" size=\"120\" required></p>';
 echo \"$listadodecampos\";
#print_r(\$_POST);
 if (\$_POST['submit']==\"Nuevo\"){
    echo '<p><input class=\"btn btn-outline-secondary\" type=\"submit\" name=\"submit\" id=\"submit\" value=\"Registrar\"></p></form>';
 }else{
    echo '<p><input class=\"btn btn-outline-secondary\" type=\"submit\" name=\"submit\" id=\"submit\" value=\"Actualizar\"></p></form>';
 }


} /*fin mixto*/ 
if (\$_POST['submit']=='Actualizar'){
    /*recibo los campos del formulario proveniente con el método POST*/ 
    \$cod = \$_POST['".$primer_campo."'];
    /*Instrucción SQL que permite insertar en la BD */ 
    \$sql = \"$sqlupdate\" ;
 
 /* echo \$sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 
if (\$actualizar = \$mysqli->query(\$sql)) {
 /*Validamos si el registro fue ingresado con Éxito*/
 ".notificaciones("Modificación Exitosa","verde")." ; echo '<meta http-equiv=\"refresh\" content=\"".$time_update."\"; url=\"$tabla.php\" />';
 }else{ 
    ".notificaciones("Modificación Fallida","rojo")."
; } 
echo '<meta http-equiv=\"refresh\" content=\"".$time_update."\"; url=\"$tabla.php\" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
 <center>
<b><label>Buscar: </label></b><input placeholder=\"Buscar..\" type=\"search\" id=\"buscar\" onkeyup =\"buscar(this.value);\" onchange=\"buscar(this.value);\"  style=\"margin: 15px;\">
<b><label>N° de Resultados:</label></b>
<input type=\"number\" min=\"0\" id=\"$nresultados\" placeholder=\"Cantidad de resultados\" title=\"Cantidad de resultados\" value=\"10\" onkeyup=\"grabarcookie('$nresultados',this.value) ;buscar(document.getElementById('buscar').value);\" mousewheel=\"grabarcookie('$nresultados',this.value);buscar(document.getElementById('buscar').value);\" onchange=\"grabarcookie('$nresultados',this.value);buscar(document.getElementById('buscar').value);\" size=\"4\" style=\"width: 40px;\">
</center>

<span id=\"txtsugerencias\">
<?php 
".$titulo_funcion."();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById(\"$menutabla\").className ='active '+document.getElementById(\"$menutabla\").className;
</script>
<?php \$contenido = ob_get_contents();
ob_clean();
include ('plantilla.php');
?>
 ";
 return $php;
}
function notificaciones($mensaje="",$tipo,$estilo="a"){
    if(strtolower($estilo)=="a"){
        if($tipo=="rojo"){
            return $html=' echo  \'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Alerta</h5>
              '.$mensaje.'
            </div>\'';
          }
          if($tipo=="verde"){
                return $html=' echo \'<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Correcto!</h5>
                  '.$mensaje.'
                  </div>\' ';
          }
        
    }
    
  
}
/* primero creamos la función que hace la magia
 * esta funcion recorre carpetas y subcarpetas
 * añadiendo todo archivo que encuentre a su paso
 * recibe el directorio y el zip a utilizar 
 */
function agregar_zip($dir, $zip) {
    //verificamos si $dir es un directorio
    if (is_dir($dir)) {
      //abrimos el directorio y lo asignamos a $da
      if ($da = opendir($dir)) {
        //leemos del directorio hasta que termine
        while (($archivo = readdir($da)) !== false) {
          /*Si es un directorio imprimimos la ruta
           * y llamamos recursivamente esta función
           * para que verifique dentro del nuevo directorio
           * por mas directorios o archivos
           */
          if (is_dir($dir . $archivo) && $archivo != "." && $archivo != "..") {
           # echo "<strong>Creando directorio: $dir$archivo</strong><br/>";
            agregar_zip($dir . $archivo . "/", $zip);
   
            /*si encuentra un archivo imprimimos la ruta donde se encuentra
             * y agregamos el archivo al zip junto con su ruta 
             */
          } elseif (is_file($dir . $archivo) && $archivo != "." && $archivo != "..") {
            #echo "Agregando archivo: $dir$archivo <br/>";
            $zip->addFile($dir . $archivo, $dir . $archivo);
          }
        }
        //cerramos el directorio abierto en el momento
        closedir($da);
      }
    }
  }
   
  //fin de la función
  function descargar_zip($rutaFinal,$proyecto,$dir){
      
    if(!isset($_GET['borrar'])){
        $zip = new ZipArchive();
        if(!file_exists($rutaFinal)){
            mkdir($rutaFinal);
          }
          $archivoZip =$proyecto.'.zip';
          if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
            agregar_zip($dir, $zip);
            $zip->close();
            rename($archivoZip, "$rutaFinal/$archivoZip");
            if (file_exists($rutaFinal."/".$archivoZip)) {
           # echo $rutaFinal.'/'.$archivoZip;
            $rutadescarga=$rutaFinal.'/'.$archivoZip;
         echo "<script>
         window.open('$rutadescarga');
         </script>";
             } else {
              echo "Error, archivo zip no ha sido creado!!";
            }
          }
    }
          
}

function configuracion($data){
    $_POST=$data;
    require 'conexion.php';
    $sql= "UPDATE `preferencias` SET `input_size`=".$_POST['input_size'].",`time_insert`=".$_POST['time_insert'].",`time_update`=".$_POST['time_update'].",`time_delete`=".$_POST['time_delete']."";
    $sql_servidor="UPDATE `configuracion` SET `servidor`=".$_POST['servidor'].",`usuario`='".$_POST['usuario'].",`clave`=".$_POST['clave'].",`desarrollador`=".$_POST['desarrollador']." ";
    $consulta=$mysqli->query($sql);
    $consulta=$mysqli->query($sql_servidor);
}


?>