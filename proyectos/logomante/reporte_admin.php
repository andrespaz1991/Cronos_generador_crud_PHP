 ?> <?php require_once 'lib/dompdf/vendor/autoload.php';require("conexion.php");
$sql="select * from admin";
if(isset($_GET['id'])){
$sql.= " WHERE admin.id_admin= '".$_GET['id']."'";
}
 $sql.=  " ORDER BY admin.id_admin desc ";
/*echo $sql;*/ 
$consulta = $mysqli->query($sql);
$html='
<h1 align="center">Admin</h1>
<table  align="center" border="1" id="tb.admin">
<thead>
<tr>
<th>Id Admin</th><th>Usuario</th><th>Contraseña</th>
    </tr>
    </thead><tbody>';
        while($row=$consulta->fetch_assoc()){ 
        $html.='<tr>
       <td>'.$row['id_admin'].'</td><td>'.$row['usuario'].'</td><td>'.$row['contraseña'].'</td>
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
$mipdf->stream('admin.pdf', array("Attachment" => 0) );