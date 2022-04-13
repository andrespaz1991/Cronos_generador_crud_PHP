 ?> <?php require_once 'lib/dompdf/vendor/autoload.php';require("conexion.php");
$sql="select * from usuario";
if(isset($_GET['id'])){
$sql.= " WHERE usuario.id_usuario= '".$_GET['id']."'";
}
 $sql.=  " ORDER BY usuario.id_usuario desc ";
/*echo $sql;*/ 
$consulta = $mysqli->query($sql);
$html='
<h1 align="center">Usuario</h1>
<table  align="center" border="1" id="tb.usuario">
<thead>
<tr>
<th>Id Usuario</th><th>Usuario</th><th>Clave</th><th>Mascota</th><th>Nombre</th><th>Apellido</th><th>Rol</th><th>Foto</th><th>Direccion</th><th>Telefono</th><th>Correo</th><th>Ultima Sesion</th><th>Num Visitas</th><th>Puntos</th><th>Estado</th><th>Tipo Sangre</th><th>Genero</th><th>Observaciones</th><th>Fecha Nacimiento</th>
    </tr>
    </thead><tbody>';
        while($row=$consulta->fetch_assoc()){ 
        $html.='<tr>
       <td>'.$row['id_usuario'].'</td><td>'.$row['usuario'].'</td><td>'.$row['clave'].'</td><td>'.$row['mascota'].'</td><td>'.$row['nombre'].'</td><td>'.$row['apellido'].'</td><td>'.$row['rol'].'</td><td>'.$row['foto'].'</td><td>'.$row['direccion'].'</td><td>'.$row['telefono'].'</td><td>'.$row['correo'].'</td><td>'.$row['ultima_sesion'].'</td><td>'.$row['num_visitas'].'</td><td>'.$row['puntos'].'</td><td>'.$row['estado'].'</td><td>'.$row['tipo_sangre'].'</td><td>'.$row['genero'].'</td><td>'.$row['observaciones'].'</td><td>'.$row['fecha_nacimiento'].'</td>
       </tr>';
       }/*fin while*/
          $html.='</tbody>
       </table>';  
use Dompdf\Dompdf;
set_time_limit(27000);
$mipdf = new DOMPDF();
$mipdf->set_paper('A4', 'landascape'); 
$mipdf->load_html($html,'UTF-8');
$mipdf->render();
$output = $mipdf->output();
$mipdf->stream('usuario.pdf', array("Attachment" => 0) );