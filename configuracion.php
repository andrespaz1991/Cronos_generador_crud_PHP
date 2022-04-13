<?php 
#echo '<center>';
require("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Cronos</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,400,500,600,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/vendor/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Rapid - v2.2.0
  * Template URL: https://bootstrapmade.com/rapid-multipurpose-bootstrap-business-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Top Bar ======= -->

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-transparent">
    <div class="container d-flex align-items-center">

      <h1 class="logo mr-auto"><a href="index.html">Cronos</a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo mr-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
<?php require_once('menu.html'); ?>
    

    </div>
  </header><!-- End Header -->
<br><br><br><br><br>

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container" data-aos="fade-up">
      <?php
#require("funciones.php");  
function buscar_configuracion( $datos='', $reporte=''){
if ($reporte=="xls"){
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename=configuracion.xls");
}
require("conexion.php");
$sql='select * from   configuracion ';
$sql.= '';
$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]='';
$cont =  0;
$sql .= ' WHERE ';
foreach ($datos as $id => $dato){
    $sql .= 'concat(LOWER(configuracion.id),"", LOWER(configuracion.servidor),"", LOWER(configuracion.usuario),"", LOWER(configuracion.clave),"", LOWER(configuracion.desarrollador),"", concat(LOWER(configuracion.id),"", LOWER(configuracion.servidor),"", LOWER(configuracion.usuario),"", LOWER(configuracion.clave),"", LOWER(configuracion.desarrollador),"")) LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"' ;
    $cont ++;
    if (count($datos)>1 and count($datos)<>$cont){
        $sql .= ' and ';
    }
    }
    $sql .=  ' ORDER BY configuracion.id desc LIMIT ';
    if (isset($_COOKIE['numeroresultados_configuracion']) and $_COOKIE['numeroresultados_configuracion']!='') $sql .=$_COOKIE['numeroresultados_configuracion'];
    else $sql .= '10';
    /*echo $sql;*/ 
    $consulta = $mysqli->query($sql);
    ?>
    <div align='center'>
<table border='1' id='tb'configuracion''>
<thead>
<tr>
<th>id</th><th>servidor</th><th>usuario</th><th>clave</th><th>desarrollador</th>
<?php if ($reporte==''){ ?>
    <th><form id='formNuevo' name='formNuevo' method='post' action=configuracion.php>
    <input name='cod' type='hidden' id='cod' value='0'>
    <input type='submit' name='submit' id='submit' value='Nuevo'>
    </form>
    </th>
    <th><form id="formNuevo" name="formNuevo" method="post" action=configuracion.php?xls">
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
       <td><?php echo $row['id']?></td><td><?php echo $row['servidor']?></td><td><?php echo $row['usuario']?></td><td><?php echo $row['clave']?></td><td><?php echo $row['desarrollador']?></td> 
       <?php if ($reporte==''){ ?>
       <td>
       <form id='formModificar' name='formModificar' method='post' action=''configuracion.php'>
       <input name='cod' type='hidden' id='cod' value=' <?php echo $row['id']?>'>
       <input type='submit' name='submit' id='submit' value='Modificar'>
       </form>
       </td>
       <td>
       <input width="35px" type="image" src="assets/img/eliminar.png" onClick="confirmeliminar('configuracion.php',{'del':'<?php echo $row['id'];?>'},'<?php echo $row['id'];?>');" value="Eliminar">
       </td>
       <?php } ?>
       </tr>
       <?php 
       }/*fin while*/
        ?>
       </tbody>
       </table></div>
       <?php 
    }/*fin function buscar*/
    if (isset($_GET['buscar'])){
        buscar_configuracion($_POST['datos']);
    exit();
    }
    if (isset($_GET['xls'])){
     buscar_configuracion('','xls');
    exit();
    }

if (isset($_POST['del'])){
 /*InstrucciÃ³n SQL que permite eliminar en la BD*/ 
 $sql = 'DELETE FROM configuracion WHERE id="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucciÃ³n SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con Ã©xito*/ 
echo '
Registro eliminado
<meta http-equiv="refresh" content="1; url="configuracion.php" />
'; 
}else{
?>
EliminaciÃ³n fallida, por favor compruebe que la usuario no estÃ© en uso
<meta http-equiv="refresh" content="2; url='configuracion.php" />
<?php 
}
}
 ?>

 <center>
 <h1>Configuración</h1>
 </center><?php 
 if (isset($_POST['submit'])){
 if ($_POST['submit']=="Registrar"){
  /*recibo los campos del formulario proveniente con el mÃ©todo POST*/ 
  $sql = "INSERT INTO configuracion(servidor,usuario,clave,desarrollador) Values ('".$_POST["servidor"]."','".$_POST["usuario"]."','".$_POST["clave"]."','".$_POST["desarrollador"]."')";

  /*echo $sql;*/
  if ($insertar = $mysqli->query($sql)) {
   /*Validamos si el registro fue ingresado con Ã©xito*/ 
  echo 'Registro exitoso';
  echo '<meta http-equiv="refresh" content="1; url=configuracion.php" />';
   }else{ 
  echo 'Registro fallido';
  echo '<meta http-equiv="refresh" content="1; url=configuracion.php" />';
  }
  } /*fin Registrar*/ 

  if ($_POST['submit']=="Nuevo" or $_POST['submit']=="Modificar"){
    if ($_POST['submit']=="Modificar"){
     $sql = 'SELECT * FROM configuracion WHERE id ="'.$_POST['cod'].'" Limit 1'; 
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
     echo '<form id="form1" name="form1" method="post" action="configuracion.php">
     <h1>'.$textoh1.'</h1>';
     
     echo '<form id="form1" name="form1" method="post" action="configuracion.php">';
echo '<p><input name="cod" type="hidden" id="cod" value="<?php echo $textobtn ?>" size="120" required></p>';
 echo "<input class='form-control' type='hidden' id='id' name='id' value='";if (isset($row["id"])){
    echo $row["id"];
} echo "'  ' > <br><label >servidor</label><br><input class='form-control' type='text' id='servidor' name='servidor' value='";if (isset($row["servidor"])){
    echo $row["servidor"];
} echo "'  ' > <br><label >usuario</label><br><input class='form-control' type='text' id='usuario' name='usuario' value='";if (isset($row["usuario"])){
    echo $row["usuario"];
} echo "'  ' > <br><label >clave</label><br><input class='form-control' type='text' id='clave' name='clave' value='";if (isset($row["clave"])){
    echo $row["clave"];
} echo "'  ' > <br><label >desarrollador</label><br><input class='form-control' type='text' id='desarrollador' name='desarrollador' value='";if (isset($row["desarrollador"])){
    echo $row["desarrollador"];
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
    $cod = $_POST['id'];
    /*InstrucciÃ³n SQL que permite insertar en la BD */ 
    $sql = "UPDATE configuracion SET servidor='".$_POST["servidor"]."',usuario='".$_POST["usuario"]."',clave='".$_POST["clave"]."',desarrollador='".$_POST["desarrollador"]."' WHERE  id  = ".$cod." ;" ;
 
 /* echo $sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucciÃ³n SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con Ã©xito*/
echo 'ModificaciÃ³n exitosa';
echo '<meta http-equiv="refresh" content="1"; url="configuracion.php" />';
 }else{ 
echo 'Modificacion fallida';
}
echo '<meta http-equiv="refresh" content="2"; url="configuracion.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
 <center>
<b><label>Buscar: </label></b><input type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>

<input type="number" min="0" id="numeroresultadosconfiguracion" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultadosconfiguracion',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultadosconfiguracion',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultadosconfiguracion',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>

<span id="txtsugerencias">
<?php 
buscar_configuracion();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById("menu_configuracion").className ='active '+document.getElementById("menu_configuracion").className;
</script>

          
      </div>

      </div>
    </section><!-- End Services Section -->

    <!-- ======= Why Us Section ======= -->
    <section id="why-us" class="why-us">
      <div class="container-fluid" data-aos="fade-up">
        <div class="row">

          <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="100">
            <div class="why-us-img">
             
            </div>
          </div>

          <div class="col-lg-6">
            <div class="why-us-content">
  

            </div>

          </div>

        </div>

      </div>

      <div class="container">
        <div class="row counters" data-aos="fade-up" data-aos-delay="100">

    

      </div>
    </section><!-- End Why Us Section -->

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>Andrés Paz Burbano</strong>. Todos los derechos reservados
      </div>
      <div class="credits">

        Diseñado por <a href="https://bootstrapmade.com/">2021</a>
      </div>
    </div>
  </footer><!-- End  Footer -->

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>

 