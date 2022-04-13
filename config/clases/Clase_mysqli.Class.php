<?php

class Clase_mysqli extends Comun{
    private $servidorbd = "localhost";
    private $usuariobd = "root";
    private $clavebd = "";
    private $basededatos = "cronos";
    private $con;

 public  function real_escape($str){
  $con = $this->conectar();
  $escape = $con->real_escape_string($str);
  return $escape;
}

function __construct( $Nombre = null ){

    $this->con = $this->conectar();

}

public function conectar(){

   $conexion_mysqli = new mysqli ($this->servidorbd,$this->usuariobd,$this->clavebd,$this->basededatos);

   if (mysqli_connect_errno()){

            echo "error".mysqli_connect_errno();

    }else{

            if($conexion_mysqli){

                  mysqli_set_charset($conexion_mysqli,'utf8');

            }

    }

    return $conexion_mysqli;

}











public function desconectar(&$mysqli)

{

  if(isset($mysqli))

  {

    mysqli_close($mysqli);

    unset($mysqli);

  }



}




public static function limpiar_metodo($metodo){
$input_arr = array();
if (is_array($metodo) and !empty($metodo)){
    foreach ($metodo as $key => $input_arr) 
    { 
        if(!is_array($input_arr)){
    	$metodo[$key] = addslashes(Clase_mysqli::limpiarCadena($input_arr)); 
        }
    }
}
}

public static function limpiarCadena($valor)

{

	$valor = str_ireplace("SELECT","",$valor);

	$valor = str_ireplace("COPY","",$valor);

	$valor = str_ireplace("DELETE","",$valor);

	$valor = str_ireplace("DROP","",$valor);

	$valor = str_ireplace("DUMP","",$valor);

	$valor = str_ireplace(" OR ","",$valor);

	$valor = str_ireplace("%","",$valor);

	$valor = str_ireplace("LIKE","",$valor);

	$valor = str_ireplace("--","",$valor);

	$valor = str_ireplace("^","",$valor);

	$valor = str_ireplace("[","",$valor);

	$valor = str_ireplace("]","",$valor);

	$valor = str_ireplace("!","",$valor);

	$valor = str_ireplace("¡","",$valor);

	$valor = str_ireplace("?","",$valor);

	$valor = str_ireplace("=","",$valor);

	$valor = str_ireplace("&","",$valor);

	return $valor;

}

/*------------------------------------------------------------- */

/* function for clean string and avoid sql inyection (by Andrés Paz )

/*---------------------------------------------------------------*/











function valida_existe(){

    $tabla = $_POST['tabla'];

    $campo = $_POST['campo'];

    $valor = $_POST['valor'];

    $retorno = $_POST['retorno'];

    $sql ="SELECT * FROM `".$tabla."` WHERE `".$campo."` = '".$valor."'";

    $consulta = $this->consultar_datos($sql,true);

    if($retorno==""){

        if (count(json_decode($consulta)) > 0) {

            return "1";

        }else{

            return "0";

        } 

    }else{

        return $consulta;

    }

}

public function consultar_datos($consulta='',$mysqli_assoc=false){

if ($consulta==''){//prueba desde 55 - 58

    $consulta = $_POST['consulta'];

    $mysqli_assoc = true;

}

$consulta = str_replace("DELETE","",$consulta);

$consulta = str_replace("UPDATE","",$consulta);

$consulta = str_replace("DROP","",$consulta);

$consulta = str_replace("CREATE","",$consulta);

$consulta = str_replace("ALTER","",$consulta);

//validar solo SELECT

if (strlen(stristr($consulta,"SELECT"))>0) {

$mysqli = $this->conectar();

if ($gconsulta_red = $mysqli->prepare($consulta)){

$gconsulta_red = $mysqli->prepare($consulta);

$gconsulta_red->execute();

$arraydedatos = $gconsulta_red->get_result();



if($mysqli_assoc){

$datos = $arraydedatos->fetch_all(MYSQLI_ASSOC);

}else{

$datos = $arraydedatos->fetch_all();

}

$this->desconectar($mysqli);

#mysql_free_result($datos);

#return $datos;

return json_encode($datos);

}

}

}

public function query_insertar($sql,$insert_id=false){

    $mysqli = $this->conectar();

    $salida = 0; 

    $consulta = $mysqli->query($sql);

     if ($mysqli->errno != 1062){

        if ($consulta==1 and $mysqli->affected_rows>0){

            if ($insert_id) $salida = $mysqli->insert_id;

            else $salida =  1;

        }else{

            $motivo = "EL usuario intentó realizar la consulta: ".$sql;

			Comun::insertar_log($motivo);

            if ($insert_id){

                 $salida = false;

            }else{

                $salida =  0;

            }

        }

    }else{

        $motivo = "EL usuario intentó realizar la consulta con error de registro duplicado Codigo 1062. ".$sql;

			Comun::insertar_log($motivo);

        if ($insert_id) $salida = false;

        else $salida = 1062;

    }



    $this->desconectar($mysqli);

    return $salida;

}

public function sql_actualizar($array,$tabla,$query=true,$where=""){

$sql = "";

if (count($array)>0){

array_walk($array, array($this, 'escape_string'));

if (isset($array['clave']) and $array['clave']!="") $array['clave']=$this->encriptar($array['clave']);

$sql = "UPDATE `$tabla` SET ";

$sql_array = array();

foreach ($array as $campo => $valor){

$sql_array[] = "`$campo`='$valor'";

//if (end($array) != $valor) $sql .= ",";

}

$sql .= implode (",",$sql_array);

//$sql .= "`$campo`='$valor'";

$sql .= $where;

}

if ($query)

return $this->query_actualizar($sql);

else

return $sql;

}

public function query_actualizar($sql){

    $mysqli = $this->conectar();

    $salida = 0;

    $consulta = $mysqli->query($sql);

    if ($consulta==1 and $mysqli->affected_rows>0){

        $salida =  1;

    }else{

        $salida =  0;

    }

    $this->desconectar($mysqli);

    return $salida;

}



public function sql_insertar($array,$tabla,$query=true,$update = false,$insert_id=false,$where = ""){

/*

    $sql = "";

if (count($array)>0){

if (isset($array['clave']) and $array['clave']!="") $array['clave']=$this->encriptar($array['clave']);

$columns = implode("`, `",array_keys($array));

foreach($array as $campo => $valor){

    if(is_array($valor)) $array[$campo]=json_encode($valor);

}

array_walk($array, array($this, 'escape_string'));

$escaped_values = array_values($array);

$values  = implode("', '", $escaped_values);

$array_values = $this->values_columnas($array);

$value_columns = implode(", ",array_values($array_values));

$sql = "INSERT INTO `$tabla`(`$columns`) VALUES ('$values') $where ";

if($update) $sql .=" ON DUPLICATE KEY UPDATE $value_columns;";}

if ($query) { 

return $this->query_insertar($sql,$insert_id);}

else {return $sql; }

*/

}



public function quitar_vacios(&$array)

{

	foreach($array as $id=>$valor){

    if ($valor=="") unset($array[$id]);

	}

}

public function values_columnas($array)

{

    foreach ($array as $id => $value){

        $array[$id]= " `".$id."` = VALUES(".$id.")";

    }

  return($array);

}



public function escape_string(&$elemento1, $clave)

{

    $mysqli = $this->conectar();

    if ($elemento1!=NULL or $elemento1 != "")

    $elemento1 = $mysqli->real_escape_string($elemento1);

    $this->desconectar($mysqli);

}

}//fin clase

?>