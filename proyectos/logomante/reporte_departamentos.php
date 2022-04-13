 ?> <?php require_once 'lib/dompdf/vendor/autoload.php';require("conexion.php");
$sql="select * from departamentos";
if(isset($_GET['id'])){
$sql.= " WHERE departamentos.id= '".$_GET['id']."'";
}
 $sql.=  " ORDER BY departamentos.id desc ";
/*echo $sql;*/ 
$consulta = $mysqli->query($sql);
$html='
<h1 align="center">Departamentos</h1>
<table  align="center" border="1" id="tb.departamentos">
<thead>
<tr>
<th>Id</th><th>Nombre</th><th>Created At</th><th>Updated At</th>
    </tr>
    </thead><tbody>';
        while($row=$consulta->fetch_assoc()){ 
        $html.='<tr>
       <td>'.$row['id'].'</td><td>'.$row['nombre'].'</td><td>'.$row['created_at'].'</td><td>'.$row['updated_at'].'</td>
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
$mipdf->stream('departamentos.pdf', array("Attachment" => 0) );