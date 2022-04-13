 ?> <?php require_once 'lib/dompdf/vendor/autoload.php';require("conexion.php");
$sql="select * from tipos";
if(isset($_GET['id'])){
$sql.= " WHERE tipos.id_tipo= '".$_GET['id']."'";
}
 $sql.=  " ORDER BY tipos.id_tipo desc ";
/*echo $sql;*/ 
$consulta = $mysqli->query($sql);
$html='
<h1 align="center">Tipos</h1>
<table  align="center" border="1" id="tb.tipos">
<thead>
<tr>
<th>Id Tipo</th><th>Tipo Dato</th><th>Tipo Input</th>
    </tr>
    </thead><tbody>';
        while($row=$consulta->fetch_assoc()){ 
        $html.='<tr>
       <td>'.$row['id_tipo'].'</td><td>'.$row['tipo_dato'].'</td><td>'.$row['tipo_input'].'</td>
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
$mipdf->stream('tipos.pdf', array("Attachment" => 0) );