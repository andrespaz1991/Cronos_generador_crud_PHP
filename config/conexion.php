<?php $mysqli= new mysqli("localhost","root","","cronos");
        if (mysqli_connect_errno()){
            echo "error";
        }
        if (isset($mysqli)) {
            mysqli_set_charset($mysqli,"utf8");
            if (!defined("PROYECTO")) {
                define("PROYECTO","ASOCIACION");
            }
            
        } ?> 