<?php
ob_start();

$contenido = ob_get_contents();
ob_clean();
include ('plantilla.php');
?>
