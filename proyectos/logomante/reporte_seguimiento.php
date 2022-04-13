 ?> <?php require_once 'lib/dompdf/vendor/autoload.php';require("conexion.php");
$sql="select * from seguimiento";
if(isset($_GET['id'])){
$sql.= " WHERE seguimiento.id_seguimiento= '".$_GET['id']."'";
}
 $sql.=  " ORDER BY seguimiento.id_seguimiento desc ";
/*echo $sql;*/ 
$consulta = $mysqli->query($sql);
$html='
<h1 align="center">Seguimiento</h1>
<table  align="center" border="1" id="tb.seguimiento">
<thead>
<tr>
<th>Id Seguimiento</th><th>Id Estudiante</th><th>Id Carga</th><th>Id Competencia</th><th>Fecha Carga</th><th>Hora Carga</th><th>Nota</th><th>Puesto Grupo</th>
    </tr>
    </thead><tbody>';
        while($row=$consulta->fetch_assoc()){ 
        $html.='<tr>
       <td>'.$row['id_seguimiento'].'</td><td>'.$row['id_estudiante'].'</td><td>'.$row['id_carga'].'</td><td>'.$row['id_competencia'].'</td><td>'.$row['fecha_carga'].'</td><td>'.$row['hora_carga'].'</td><td>'.$row['nota'].'</td><td>'.$row['puesto_grupo'].'</td>
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
$mipdf->stream('seguimiento.pdf', array("Attachment" => 0) );