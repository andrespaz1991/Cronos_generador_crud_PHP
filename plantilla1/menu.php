<nav class='navbar navbar-default' role='navigation'><div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse"
            data-target=".navbar-ex1-collapse">
      <span class="sr-only">Desplegar navegaci&oacute;n</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <!--a class="navbar-brand" href="#">Evaluaci&oacute;n</a-->
  </div>
  <div class="collapse navbar-collapse navbar-ex1-collapse">
<ul  class='nav navbar-nav'>
<?php
@session_start();
require 'conexion.php';
$sql='select * from menu_items GROUP by menu_item_name;';
#echo $sql;
$consulta=$mysqli->query($sql);
while($row=$consulta->fetch_assoc()){
?>
<li><a href="<?php echo $row['menu_url']; ?>" target="_self" title=""><?php echo $row['menu_item_name']; ?></a></li>
<?php }  
#if($_SESSION['rol']<>"admin"){ ?>
<li ><a href="#">Bienvenido(a) <?php #echo $_SESSION['nombre']; ?></a></li>

<!--li style="margin-left:60%;position:absolute;"><a href="Estudiante.php">Bienvenido(a) <?php echo $_SESSION['nombre']; ?></a></li-->
<?php #} ?>
<li ><a href="index.php">Salir</a></li>
<!--li style="margin-left:90%;position:absolute;"><a href="index.php">Salir</a></li-->
</ul>
</div></nav>