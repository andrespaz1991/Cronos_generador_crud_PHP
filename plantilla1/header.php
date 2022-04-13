<?php require_once('conexion.php');
$consulta=($mysqli->query("SHOW DATABASES"));
$bd=($consulta->fetch_assoc());
 ?>
<header>
    <center>
    <img style="position:absolute;width:5%;margin-left:-36%;padding-bottom:20%" src="img/logo.png"></img >	
    <link  rel="icon"   href="img/favicon.png" type="image/png" />
        <h1><?php 
        if(isset($bd['Database'])){
            echo ucwords($bd['Database']);
        }else{
            echo "Proyecto";
        }
         ?></h1>
        <img style="position:absolute;width:5%;margin-left:25%;padding-bottom:20%;margin-top:-4%;" src="img/logo.PNG"></img >	
    </center>
</header><br><br><br>