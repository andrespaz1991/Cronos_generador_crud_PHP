<?php 
ob_start();
echo '<center>';
require("conexion.php");
#require("funciones.php");  
function buscar_usuario( $datos='', $reporte=''){
require_once ("lib/Zebra_Pagination/Zebra_Pagination.php");
$resultados = (isset($_COOKIE['numeroresultadosusuario']) ? $_COOKIE['numeroresultadosusuario'] : 10);
$paginacion = new Zebra_Pagination();
$paginacion->records_per_page($resultados);
$paginacion->records_per_page($resultados);
$cookiepage="page_numeroresultadosusuario";
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
        header("Content-Disposition: attachment; Filename=usuario.xls");
    }
    
    #header("Location:usuario.php");
    }require("conexion.php");
$sql='select * from   usuario ';
$consulta = $mysqli->query($sql);
$paginacion->records($consulta->num_rows);

$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]='';
$cont =  0;
$sql .= ' WHERE ';
if(!empty($_GET['xls'])){
    $sql.= "  usuario.id_usuario= '".$_GET['xls']."'";
}else{
    foreach ($datos as $id => $dato){
        $sql .= 'concat(LOWER(usuario.id_usuario),"", LOWER(usuario.usuario),"", LOWER(usuario.clave),"", LOWER(usuario.mascota),"", LOWER(usuario.nombre),"", LOWER(usuario.apellido),"", LOWER(usuario.rol),"", LOWER(usuario.foto),"", LOWER(usuario.direccion),"", LOWER(usuario.telefono),"", LOWER(usuario.correo),"", LOWER(usuario.ultima_sesion),"", LOWER(usuario.num_visitas),"", LOWER(usuario.puntos),"", LOWER(usuario.estado),"", LOWER(usuario.tipo_sangre),"", LOWER(usuario.genero),"", LOWER(usuario.observaciones),"", LOWER(usuario.fecha_nacimiento),"", concat(LOWER(usuario.id_usuario),"", LOWER(usuario.usuario),"", LOWER(usuario.clave),"", LOWER(usuario.mascota),"", LOWER(usuario.nombre),"", LOWER(usuario.apellido),"", LOWER(usuario.rol),"", LOWER(usuario.foto),"", LOWER(usuario.direccion),"", LOWER(usuario.telefono),"", LOWER(usuario.correo),"", LOWER(usuario.ultima_sesion),"", LOWER(usuario.num_visitas),"", LOWER(usuario.puntos),"", LOWER(usuario.estado),"", LOWER(usuario.tipo_sangre),"", LOWER(usuario.genero),"", LOWER(usuario.observaciones),"", LOWER(usuario.fecha_nacimiento),"")) LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"' ;
        $cont ++;
        if (count($datos)>1 and count($datos)<>$cont){
            $sql .= ' and ';
        }
        }
        $sql .=  ' ORDER BY usuario.id_usuario desc  ';
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
  
<table class="table" border='1' id='tbusuario'>
<thead class="thead-dark">
<tr>
<th>Id Usuario</th><th>Usuario</th><th>Clave</th><th>Mascota</th><th>Nombre</th><th>Apellido</th><th>Rol</th><th>Foto</th><th>Direccion</th><th>Telefono</th><th>Correo</th><th>Ultima Sesion</th><th>Num Visitas</th><th>Puntos</th><th>Estado</th><th>Tipo Sangre</th><th>Genero</th><th>Observaciones</th><th>Fecha Nacimiento</th>
<?php if ($reporte==''){ ?>
    <th ><form id='formNuevo' name='formNuevo' method='post' action=usuario.php>
    <input name='cod' type='hidden' id='cod' value='0'>
    <input class="btn btn-light" type='submit' name='submit' id='submit' value='Nuevo'>
    </form>
    </th><th  ><form id="formNuevo" name="formNuevo" method="post" action=usuario.php?xls>
    <input name="cod" type="hidden" id="cod" value="0"><input class="btn btn-success" type="submit" name="submit" id="submit" value="XLS"><a target="_blank" href='reporte_usuario.php'><button type="button" class="btn btn-danger">PDF</button>
        </a></form>
    </th><?php } ?>
    </tr>
    </thead><tbody>
    <?php 
    while($row=$consulta->fetch_assoc()){
        ?>
       <tr>
       <td><?php echo $row['id_usuario']?></td><td><?php echo $row['usuario']?></td><td><?php echo $row['clave']?></td><td><?php echo $row['mascota']?></td><td><?php echo $row['nombre']?></td><td><?php echo $row['apellido']?></td><td><?php echo $row['rol']?></td><td><?php echo $row['foto']?></td><td><?php echo $row['direccion']?></td><td><?php echo $row['telefono']?></td><td><?php echo $row['correo']?></td><td><?php echo $row['ultima_sesion']?></td><td><?php echo $row['num_visitas']?></td><td><?php echo $row['puntos']?></td><td><?php echo $row['estado']?></td><td><?php echo $row['tipo_sangre']?></td><td><?php echo $row['genero']?></td><td><?php echo $row['observaciones']?></td><td><?php echo $row['fecha_nacimiento']?></td> 
       <?php if ($reporte==''){ ?>
       <td>
       <form id='formModificar' name='formModificar' method='post' action=''usuario.php'>
       <input name='cod' type='hidden' id='cod' value=' <?php echo $row['id_usuario']?>'>
       <input class="btn btn-outline-primary" type='submit' name='submit' id='submit' value='Modificar'>
       <button type="button" class="btn btn-outline-danger" onClick="confirmeliminar('usuario.php',{'del':'<?php echo $row['id_usuario'];?>'},'<?php echo $row['id_usuario'];?>');">Eliminar</button>
       </form>     
       </td><td>
       <a target="_blank" href='usuario.php?xls=<?php echo $row['id_usuario']?>'><button type="button" class="btn btn-success">XLS</button>
       </a><a target="_blank" href="reporte_usuario.php?id=<?php echo $row['id_usuario']?>"> <button type="button" class="btn btn-danger">PDF</button></a></td><?php } ?>
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
        buscar_usuario($_POST['datos']);
    exit();
    }
    if (isset($_GET['xls'])){
     buscar_usuario('','xls');
    exit();
    }

if (isset($_POST['del'])){
 /*Instrucción SQL que permite eliminar en la BD*/ 
 $sql = 'DELETE FROM usuario WHERE id_usuario="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con Éxito*/  echo '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Correcto!</h5>
                  Registro Eliminado
                  </div>' ;
?> <meta http-equiv="refresh" content="1; url="usuario.php" />
<?php
}else{ 
 echo  '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Alerta</h5>
              Eliminación Fallida
            </div>';
?> 
<meta http-equiv="refresh" content="1; url='usuario.php" />
<?php 
}
}
 ?>

 <center>
 <h1>Usuario</h1>
 </center><?php 
 if (isset($_POST['submit'])){
 if ($_POST['submit']=="Registrar"){
  /*recibo los campos del formulario proveniente con el método POST*/ 
  $sql = "INSERT INTO usuario(usuario,clave,mascota,nombre,apellido,rol,foto,direccion,telefono,correo,ultima_sesion,num_visitas,puntos,estado,tipo_sangre,genero,observaciones,fecha_nacimiento) Values ('".$_POST["usuario"]."','".$_POST["clave"]."','".$_POST["mascota"]."','".$_POST["nombre"]."','".$_POST["apellido"]."','".$_POST["rol"]."','".$_POST["foto"]."','".$_POST["direccion"]."','".$_POST["telefono"]."','".$_POST["correo"]."','".$_POST["ultima_sesion"]."','".$_POST["num_visitas"]."','".$_POST["puntos"]."','".$_POST["estado"]."','".$_POST["tipo_sangre"]."','".$_POST["genero"]."','".$_POST["observaciones"]."','".$_POST["fecha_nacimiento"]."')";

  /*echo $sql;*/
  if ($insertar = $mysqli->query($sql)) {
   /*Validamos si el registro fue ingresado con Éxito*/ 
    echo '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Correcto!</h5>
                  Registro Exitoso
                  </div>' 
   ; echo '<meta http-equiv="refresh" content="1; url=usuario.php" />';
   }else{ 
     echo  '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Alerta</h5>
              Registro fallido
            </div>'
    ; echo '<meta http-equiv="refresh" content="1; url=usuario.php" />';
  }
  } /*fin Registrar*/ 

  if ($_POST['submit']=="Nuevo" or $_POST['submit']=="Modificar"){
    if ($_POST['submit']=="Modificar"){
     $sql = 'SELECT * FROM usuario WHERE id_usuario ="'.$_POST['cod'].'" Limit 1'; 
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
     echo '<form id="form1" name="form1" method="post" action="usuario.php">
     <h1>'.$textoh1.'</h1>';
     
     echo '<form id="form1" name="form1" method="post" action="usuario.php">';
echo '<p><input name="cod" type="hidden" id="cod" value="<?php echo $textobtn ?>" size="120" required></p>';
 echo "<label >Id Usuario</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='id_usuario' name='id_usuario' value='";if (isset($row["id_usuario"])){
    echo $row["id_usuario"];
} echo "'  ' >
            </div>
            <br><label >Usuario</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='usuario' name='usuario' value='";if (isset($row["usuario"])){
    echo $row["usuario"];
} echo "'  ' >
            </div>
            <br><label >Clave</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='clave' name='clave' value='";if (isset($row["clave"])){
    echo $row["clave"];
} echo "'  ' >
            </div>
            <br><label >Mascota</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='mascota' name='mascota' value='";if (isset($row["mascota"])){
    echo $row["mascota"];
} echo "'  ' >
            </div>
            <br><label >Nombre</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='nombre' name='nombre' value='";if (isset($row["nombre"])){
    echo $row["nombre"];
} echo "'  ' >
            </div>
            <br><input class='form-control' type='hidden' id='apellido' name='apellido' value='";if (isset($row["apellido"])){
    echo $row["apellido"];
} echo "'  ' > <br><label >Rol</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='rol' name='rol' value='";if (isset($row["rol"])){
    echo $row["rol"];
} echo "'  ' >
            </div>
            <br><label >Foto</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='foto' name='foto' value='";if (isset($row["foto"])){
    echo $row["foto"];
} echo "'  ' >
            </div>
            <br><label >Direccion</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='direccion' name='direccion' value='";if (isset($row["direccion"])){
    echo $row["direccion"];
} echo "'  ' >
            </div>
            <br><label >Telefono</label><br>
            <div class='col-3'>
            <input  class='form-control' type='number' id='telefono' name='telefono' value='";if (isset($row["telefono"])){
    echo $row["telefono"];
} echo "'  ' >
            </div>
            <br><label >Correo</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='correo' name='correo' value='";if (isset($row["correo"])){
    echo $row["correo"];
} echo "'  ' >
            </div>
            <br><label >Ultima Sesion</label><br>
            <div class='col-3'>
            <input  class='form-control' type='' id='ultima_sesion' name='ultima_sesion' value='";if (isset($row["ultima_sesion"])){
    echo $row["ultima_sesion"];
} echo "'  ' >
            </div>
            <br><label >Num Visitas</label><br>
            <div class='col-3'>
            <input  class='form-control' type='' id='num_visitas' name='num_visitas' value='";if (isset($row["num_visitas"])){
    echo $row["num_visitas"];
} echo "'  ' >
            </div>
            <br><label >Puntos</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='puntos' name='puntos' value='";if (isset($row["puntos"])){
    echo $row["puntos"];
} echo "'  ' >
            </div>
            <br><label >Estado</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='estado' name='estado' value='";if (isset($row["estado"])){
    echo $row["estado"];
} echo "'  ' >
            </div>
            <br><label >Tipo Sangre</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='tipo_sangre' name='tipo_sangre' value='";if (isset($row["tipo_sangre"])){
    echo $row["tipo_sangre"];
} echo "'  ' >
            </div>
            <br><label >Genero</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='genero' name='genero' value='";if (isset($row["genero"])){
    echo $row["genero"];
} echo "'  ' >
            </div>
            <br><label >Observaciones</label><br>
            <div class='col-3'>
            <input  class='form-control' type='text' id='observaciones' name='observaciones' value='";if (isset($row["observaciones"])){
    echo $row["observaciones"];
} echo "'  ' >
            </div>
            <br><label >Fecha Nacimiento</label><br>
            <div class='col-3'>
            <input  class='form-control' type='date' id='fecha_nacimiento' name='fecha_nacimiento' value='";if (isset($row["fecha_nacimiento"])){
    echo $row["fecha_nacimiento"];
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
    $cod = $_POST['id_usuario'];
    /*Instrucción SQL que permite insertar en la BD */ 
    $sql = "UPDATE usuario SET usuario='".$_POST["usuario"]."',clave='".$_POST["clave"]."',mascota='".$_POST["mascota"]."',nombre='".$_POST["nombre"]."',apellido='".$_POST["apellido"]."',rol='".$_POST["rol"]."',foto='".$_POST["foto"]."',direccion='".$_POST["direccion"]."',telefono='".$_POST["telefono"]."',correo='".$_POST["correo"]."',ultima_sesion='".$_POST["ultima_sesion"]."',num_visitas='".$_POST["num_visitas"]."',puntos='".$_POST["puntos"]."',estado='".$_POST["estado"]."',tipo_sangre='".$_POST["tipo_sangre"]."',genero='".$_POST["genero"]."',observaciones='".$_POST["observaciones"]."',fecha_nacimiento='".$_POST["fecha_nacimiento"]."' WHERE  id_usuario  = ".$cod." ;" ;
 
 /* echo $sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con Éxito*/
  echo '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Correcto!</h5>
                  Modificación Exitosa
                  </div>'  ; echo '<meta http-equiv="refresh" content="1"; url="usuario.php" />';
 }else{ 
     echo  '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Alerta</h5>
              Modificación Fallida
            </div>'
; } 
echo '<meta http-equiv="refresh" content="1"; url="usuario.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
 <center>
<b><label>Buscar: </label></b><input placeholder="Buscar.." type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>
<input type="number" min="0" id="numeroresultadosusuario" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultadosusuario',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultadosusuario',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultadosusuario',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>

<span id="txtsugerencias">
<?php 
buscar_usuario();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById("menu_usuario").className ='active '+document.getElementById("menu_usuario").className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ('plantilla.php');
?>
 