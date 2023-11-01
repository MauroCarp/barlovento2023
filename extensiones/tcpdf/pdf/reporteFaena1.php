<?php
ini_set('display_errors', 0);
require_once('tcpdf_include.php');
require_once "../../../controladores/trazabilidad.controlador.php";
require_once "../../../modelos/trazabilidad.modelo.php";

$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// ---------------------------------------------------------

$pdf->setFontSubsetting(true);
$pdf->SetFont('dejavusans', '', 11, '', true);
$pdf->AddPage();

$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));


$idFaena = $_GET['idFaena'];

$data = ControladorTrazabilidad::ctrDataReporte1($idFaena);

$nombre = $data['faena']['nombre'];

$fecha = date('d-m-Y',strtotime($data['faena']['fecha']));

$html = <<<EOF
 
<h2>Reporte Principal Trazabilidad</h2>
<h3>$nombre - $fecha</h3>

<table>
    <tr style="text-decoration:underline">
        <th>Caravana</th>
        <th>Garr&oacute;n</th>
        <th>Peso Vivo</th>
        <th>Ecograf&iacute;a</th>
        <th>Peso Media</th>
        <th>Tipificaci&oacute;n</th>
        <th>Conversi&oacute;n</th>
        <th>Proveedor</th>
    </tr>
EOF;

foreach ($data['animales'] as $key => $value) {
    $color = ($key % 2) ? 'rgb(220,220,220)' : 'white';
    $html .='<tr style="line-height:20em;background-color:' . $color . '">
                <td>' . $value['rfid']. '</td>
                <td>' . $value['garron']. '</td>
                <td>' . $value['kgEgreso']. '</td>
                <td>' . $value['clasificacion']. '</td>
                <td>' . $value['kilos']. '</td>
                <td>' . $value['tipificacion']. '</td>
                <td>' . $value['convMS']. '</td>
                <td>' . $value['proveedor']. '</td>
            </tr>';

}

$html .= '</table>';

$pdf->writeHTML($html, false, false, false, false, '');

$pdf->Output('Reporte Principal Trazabilidad.pdf', 'I');

?>