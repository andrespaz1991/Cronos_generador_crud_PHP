<?php $mysqli= new mysqli("localhost","root","","guagua");
        if (mysqli_connect_errno()){
            echo "error";
        }
        if (isset($mysqli)) {
            mysqli_set_charset($mysqli,"utf8");
            if (!defined("PROYECTO")) {
                define("PROYECTO","GUAGUA");
            }
            
        } ?> 