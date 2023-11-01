<?php

ini_set('display_errors', 0);

require_once 'Classes/PHPExcel.php';
require_once "../../controladores/trazabilidad.controlador.php";
require_once "../../modelos/trazabilidad.modelo.php";

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Barlovento SRL")
							 ->setTitle("Reporte Principal Trazabilidad");


$idFaena = $_GET['idFaena'];

$data = ControladorTrazabilidad::ctrDataReporte1($idFaena);

$nombre = $data['faena']['nombre'];

$fecha = date('d-m-Y',strtotime($data['faena']['fecha']));

$hoja = $objPHPExcel->setActiveSheetIndex(0);

$hoja->mergeCells('A1:I1');
$hoja->mergeCells('A2:C2');
$hoja->setCellValue('A1', 'Reporte Principal Trazabilidad')
     ->setCellValue('A2', $nombre . ' - ' . $fecha)
     ->setCellValue('A3', 'Caravana')
     ->setCellValue('B3', 'Garrón')
     ->setCellValue('C3', 'Peso Vivo')
     ->setCellValue('D3', 'Ecografía')
     ->setCellValue('E3', 'Peso Media')
     ->mergeCells('F3:G3')
     ->setCellValue('F3', 'Tipificación')
     ->setCellValue('H3', 'Conversión')
     ->setCellValue('I3', 'Proveedor');

$index = 4;

foreach ($data['animales'] as $value) {
    
    $hoja->setCellValue('A' . $index, $value['rfid'])
         ->setCellValue('B' . $index, $value['garron'])
         ->setCellValue('C' . $index, $value['kgEgreso'])
         ->setCellValue('D' . $index, $value['clasificacion'])
         ->setCellValue('E' . $index, $value['kilos'])
         ->setCellValue('F' . $index, $value['tipificacion'])
         ->setCellValue('G' . $index, $value['gordo'])
         ->setCellValue('H' . $index, $value['convMS'])
         ->setCellValue('I' . $index, $value['proveedor']);

    $index++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte Principal Faena.xlsx"');
header('Cache-Control: max-age=0');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
