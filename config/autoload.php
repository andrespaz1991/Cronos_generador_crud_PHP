<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
@session_start();
spl_autoload_register(function ($clase) {
include $_SERVER['DOCUMENT_ROOT'].'/cronos/config'.'/clases/'.ucwords($clase).'.Class.php';
});
?>