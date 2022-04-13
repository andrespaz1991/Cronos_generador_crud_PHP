 ?> <?php require_once 'lib/dompdf/vendor/autoload.php';require("conexion.php");
$sql="select * from persona";
if(isset($_GET['id'])){
$sql.= " WHERE persona.id_persona= '".$_GET['id']."'";
}
 $sql.=  " ORDER BY persona.id_persona desc ";
/*echo $sql;*/ 
$consulta = $mysqli->query($sql);
$html='
<h1 align="center">Persona</h1>
<table  align="center" border="1" id="tb.persona">
<thead>
<tr>
<th>Id Persona</th><th>Nombre Persona</th><th>Apellido Persona</th><th>Institucion</th><th>Departamento</th>
    </tr>
    </thead><tbody>';
        while($row=$consulta->fetch_assoc()){ 
        $html.='<tr>
       <td>'.$row['id_persona'].'</td><td>'.$row['nombre_persona'].'</td><td>'.$row['apellido_persona'].'</td><td>'.$row['institucion'].'</td><td>'.$row['departamento'].'</td>
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
$mipdf->stream('persona.pdf', array("Attachment" => 0) );