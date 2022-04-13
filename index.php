<!DOCTYPE html>
<html lang="es">
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/script.js" type="text/javascript">
</script>
<script>
    $(document).ready(function(){
        contador=0;
       nombre="field_name";
       nombre2="tipo";
       nombre3="TipoCampo";
       campos(nombre,0);
      
       $(".add_button").click();  
   
   });
  </script>
<meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Cronos</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,400,500,600,700" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/vendor/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
  <header id="header" class="fixed-top header-transparent">
  <div class="container d-flex align-items-center">
      <h1 class="logo mr-auto"><a href="index.html">Cronos</a></h1>
       <?php require_once('menu.html'); ?>
    </div>
  </header>
<?php 
require_once 'funciones.php';
$rutaFinal = "resultados";
$proyecto="proyectos.zip";
if(isset($_GET['id'])){
    $dir = 'proyectos/'.$_GET['id'].'/';
    descargar_zip($rutaFinal,$_GET['id'],$dir);
}
if(isset($_GET['borrar'])){
  borrar_proyecto($_GET['id']);
}
require_once 'conexion.php';
?>
<br><br><br><br><br>

<section  id="services" class="services section-bg">
    
  <form style="margin-left:30%" target='_blank' action="cronos.php" method="post">
<div class="field_wrapper">
  <h1 align="left">Configuración</h1>
  <script src="assets/js/ajax.js"></script>
<input type="checkbox" checked  name="primaria" ></input>
<label>Llave Primaria</label>
<input checked  type="checkbox" name="auto" ></input>
<label>Auto Incrementable</label>
<br>
<input  type="radio" id="basica" name="plantilla" value="plantilla1">
<label for="basica">Plantilla Básica</label>
<input checked type="radio" id="adminlte" name="plantilla" value="plantilla2">
<label for="adminlte">Plantilla AdminLTE</label><br>
<label id='txtsugerencias'></label>
<input checked  type="checkbox" name="xls" ></input>
<label>Reporte Xls</label>
<input checked  type="checkbox" name="pdf" ></input>
<label>Reporte pdf</label><br>
<label>Nuevo Proyecto</label>
<input Placeholder="Cronos" autofocus align="center" class="form" type="text" name="nproyecto" onKeyPress='buscar(this.value);' onkeyup='buscar(this.value);'  value="">
<label>Modulo</label>
<input Placeholder="Persona" align="center" class="form" type="text" name="modulo" value="" onkeyup="sugerencia();">
<a href="javascript:void(0);" onclick=""  class="add_button" title="Add field"><img width="1%" src="multimedia/add.svg"/></a>
    <div>
       
    </div>
</div>
<input type="submit" class="btn btn-info" value="Generar"></input>
</form>  
</section>
<section id="services" class="services section-bg">
      <div class="container" data-aos="fade-up">

        <header class="section-header">
          <h3>Listado de Proyecto</h3>
          <p>Gestor de proyectos</p>
        </header>
        <div class="row">
        <?php echo listar_directorios_ruta("./proyectos/");?>         
      </div>

      </div>
    </section>
  <section id="why-us" class="why-us">
      <div class="container-fluid" data-aos="fade-up">

        <header class="section-header">
          <h3>Seguimiento</h3>
        </header>

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

          <div class="col-lg-3 col-6 text-center">
            <span data-toggle="counter-up"><?php 
      require_once 'conexion.php';
    $sql='SELECT count(id_proyecto) as cantidad FROM `seguimiento` ';
    $consulta=$mysqli->query($sql);
    $total=$consulta->num_rows;
    while($resultado= $consulta->fetch_assoc()){
        echo $resultado['cantidad'];  
    }

            ?>
             </span>
            <p>Proyectos Actuales</p>
          </div>

          <div class="col-lg-3 col-6 text-center">
            <span data-toggle="counter-up"><?php
            require_once 'conexion.php';
            $sql='SELECT id_proyecto  as cantidad FROM `seguimiento` order by id_proyecto desc limit 1 ';
            $consulta=$mysqli->query($sql);
            $total=$consulta->num_rows;
            while($resultado= $consulta->fetch_assoc()){
                echo $resultado['cantidad'];  
            }
            ?></span>
            <p>Proyectos Totales</p>
          </div>

          <div class="col-lg-3 col-6 text-center">
            <span data-toggle="counter-up">
            <?php 
            list($plantilla,$cantidad)=plantilla_mas_usada();
            echo $cantidad ?></span>
            <p><?php
            echo 'Plantilla Recomendada:'.ucwords($plantilla);
            ?> </p>
          </div>

          <div class="col-lg-3 col-6 text-center">
            <span data-toggle="counter-up">18</span>
            <p>Hard Workers</p>
          </div>

        </div>

      </div>
    </section><!-- End Why Us Section -->

    <div class="container">
      <div class="copyright">
        &copy; Creado <strong>por</strong>.Andres Paz
      </div>
      <div class="credits">
        <!--
        All the links in the footer should remain intact.
        You can delete the links only if you purchased the pro version.
        Licensing information: https://bootstrapmade.com/license/
        Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Rapid
      --></a>
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

<?php


?>