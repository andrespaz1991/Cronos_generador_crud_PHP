<?php
class Configuracion extends Clase_mysqli{  
    public $id;
    public $servidor;
    public $usuario;
    public $clave;
    public $desarrollador;
    public $correo;

public function __SET($atributo,$valor){
        return	$this-> $atributo= $valor ;
}
public function consultar_config(){
  $sql='select * from configuracion';
  $datos=json_decode($this->consultar_datos($sql,true),true);
  echo "<pre>";
  print_r($datos);
  echo "</pre>";
}

public function insertar_config(){
 # $sql='INSERT INTO `configuracion`(`id`, `servidor`, `usuario`, `clave`, `desarrollador`, `correo`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]') ';
 # return $this->query_insertar($sql);	  
}
    
}
?>