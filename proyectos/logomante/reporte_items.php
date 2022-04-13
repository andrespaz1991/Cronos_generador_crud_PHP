 ?> <?php require_once 'lib/dompdf/vendor/autoload.php';require("conexion.php");
$sql="select * from items";
if(isset($_GET['id'])){
$sql.= " WHERE items.id= '".$_GET['id']."'";
}
 $sql.=  " ORDER BY items.id desc ";
/*echo $sql;*/ 
$consulta = $mysqli->query($sql);
$html='
<h1 align="center">Items</h1>
<table  align="center" border="1" id="tb.items">
<thead>
<tr>
<th>Id</th><th>Nombre</th><th>Area</th><th>Materia</th><th>Categoria</th><th>Padre</th><th>Puntaje</th><th>Prefijo</th>
    </tr>
    </thead><tbody>';
        while($row=$consulta->fetch_assoc()){ 
        $html.='<tr>
       <td>'.$row['id'].'</td><td>'.$row['nombre'].'</td><td>'.$row['area'].'</td><td>'.$row['materia'].'</td><td>'.$row['categoria'].'</td><td>'.$row['padre'].'</td><td>'.$row['puntaje'].'</td><td>'.$row['prefijo'].'</td>
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
$mipdf->stream('items.pdf', array("Attachment" => 0) ); ?> <?php require_once 'lib/dompdf/vendor/autoload.php';require("conexion.php");
$sql="select * from items";
if(isset($_GET['id'])){
$sql.= " WHERE items.id= '".$_GET['id']."'";
}
 $sql.=  " ORDER BY items.id desc ";
/*echo $sql;*/ 
$consulta = $mysqli->query($sql);
$html='
<h1 align="center">Items</h1>
<table  align="center" border="1" id="tb.items">
<thead>
<tr>
<th>Id</th><th>Nombre</th><th>Area</th><th>Materia</th><th>Categoria</th><th>Padre</th><th>Puntaje</th><th>Prefijo</th>
    </tr>
    </thead><tbody>';
        while($row=$consulta->fetch_assoc()){ 
        $html.='<tr>
       <td>'.$row['id'].'</td><td>'.$row['nombre'].'</td><td>'.$row['area'].'</td><td>'.$row['materia'].'</td><td>'.$row['categoria'].'</td><td>'.$row['padre'].'</td><td>'.$row['puntaje'].'</td><td>'.$row['prefijo'].'</td>
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
$mipdf->stream('items.pdf', array("Attachment" => 0) );