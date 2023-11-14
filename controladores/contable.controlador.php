<?php

// require('../modelos/log.modelo.php');

class ControladorContable{

	/*=============================================
	CARGAR ARCHIVO
	=============================================*/
	static public function ctrCargarArchivo(){

        function formatearNumero($number){

            return str_replace('*','.',
                                        str_replace(',','',
                                                    str_replace('.','*',
                                                                str_replace('?','',$number))));

        }

        function numberPaihuen($number){
            return str_replace('(','',str_replace(')','',str_replace('*','-',str_replace(',','.',str_replace('?','',$number)))));
        }
        // VALIDAR INGRESOS REPETIDOS⁄
        
        if(isset($_POST['btnCargar'])){
            
            require_once('extensiones/excel/php-excel-reader/excel_reader2.php');
            require_once('extensiones/excel/SpreadsheetReader.php');

            $tabla = 'contable';

            $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            $respuesta = null;

            if (isset($_FILES['nuevosDatosBarlovento'])){
    
                if(in_array($_FILES["nuevosDatosBarlovento"]["type"],$allowedFileType)){
    
                    $ruta = "carga/" . $_FILES['nuevosDatosBarlovento']['name'];
                    
                    move_uploaded_file($_FILES['nuevosDatosBarlovento']['tmp_name'], $ruta);

                    $nombreArchivo = $_FILES['nuevosDatosBarlovento']['name'];

                    $respuesta = ControladorContable::ctrProcesoExcel($ruta,$nombreArchivo,$tabla,'principal');
    
                }

            }
    
            if (isset($_FILES['nuevosDatosBarloventoConsolidado'])){
        
                if(in_array($_FILES["nuevosDatosBarloventoConsolidado"]["type"],$allowedFileType)){
    
                    $ruta = "carga/" . $_FILES['nuevosDatosBarloventoConsolidado']['name'];
                    
                    move_uploaded_file($_FILES['nuevosDatosBarloventoConsolidado']['tmp_name'], $ruta);
                        
                    $nombreArchivo = $_FILES['nuevosDatosBarloventoConsolidado']['name'];

                    $respuesta = ControladorContable::ctrProcesoExcel($ruta,$nombreArchivo,$tabla,'consolidado');

                }
    
            }
            
            /* if (isset($_FILES['nuevosDatosPaihuen'])){
    
                $tabla = 'contablePaihuen';

                $error = false;
                
                $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

                if(in_array($_FILES["nuevosDatosPaihuen"]["type"],$allowedFileType)){
    
                    $ruta = "carga/" . $_FILES['nuevosDatosPaihuen']['name'];
                    
                    move_uploaded_file($_FILES['nuevosDatosPaihuen']['tmp_name'], $ruta);
                    
                    $nombreArchivo = str_replace(' ', '',$_FILES['nuevosDatosPaihuen']['name']);
                                            
                    $rowNumber = 0;
                    
                    $rowValida = false;
                    
                    $dateTime = date('Y-m-d H:i:s');
    
                    $Reader = new SpreadsheetReader($ruta);	
                    
                    $sheetCount = count($Reader->sheets());
                    
                    $data = array('planilla'=>'paihuen','archivo'=>$_FILES['nuevosDatosPaihuen']['name'],'periodo'=>$_POST['periodoContable']."-01");

                    $item = NULL;
                            
                    $valor = 'Paihuen';

                    $item2 = 'periodo';

                    $valor2 = $data['periodo'];

                    $cargaValida = ControladorContable::ctrMostrarDatos($item,$valor,$item2,$valor2);
    
                    if($cargaValida){
                        
                        echo'<script>

                            swal({
                                    type: "error",
                                    title: "Ya hay una planilla Paihuen en este periodo!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar"
                                    }).then(function(result) {
                                    if (result.value) {
                                        
                                        window.location = "index.php?ruta=contable/contable"
        
                                    }
                                })
        
                            </script>';

                            die();
                            

                    }
    
                    for($i=0;$i<$sheetCount;$i++){
            
                        $Reader->ChangeSheet($i);
    
                        foreach ($Reader as $Row){
    
                            if($rowNumber == 0)
                                $rowValida = true;
    
                            if($rowValida){
                                if($rowNumber >= 69 AND $rowNumber <= 76){
                                    $data['ganancias'][] = (trim(numberPaihuen($Row[1])) != '-') ? trim(numberPaihuen($Row[1])) : 0;  
                                }

                                if($rowNumber >= 77 AND $rowNumber <= 106){
                                    $data['perdidas'][] = (trim(numberPaihuen($Row[1])) != '-') ? trim(numberPaihuen($Row[1])) : 0;
                                }
    
                                if($Row[0] == 'Venta de Cereales y Oleaginosas'){
                                    $data['ventaAgricultura'] = (trim(numberPaihuen($Row[1])) != '-') ? floatval(trim(numberPaihuen($Row[1]))) : 0;
                                }
    
                                if($rowNumber >= 1 AND $rowNumber <= 43){   
                                    $data['activos'][] = (trim(numberPaihuen($Row[1])) != '-') ? trim(numberPaihuen($Row[1])) : 0;                            
                                }
    
                                if($Row[0] == 'IMP. PROVINCIALES' || $Row[0] == 'API'){                                    
                                    $data['inmobiliario'][] = (trim(numberPaihuen($Row[1])) != '-') ? trim(numberPaihuen($Row[1])) : 0;
                                }
                                
                                if($Row[0] == 'COMUNA DE BOUQUET'){                                   
                                    $data['comuna'] = (trim(numberPaihuen($Row[1])) != '-') ? floatval(trim(numberPaihuen($Row[1]))) : 0;
                                }
 
                            }
        
                            $rowNumber++;
    
                        }
                            
                    }

                    $data['ganancias'] = floatval(array_sum($data['ganancias']));
                    $data['perdidas'] = floatval(array_sum($data['perdidas']));
                    $data['activos'] = floatval(array_sum($data['activos']));
                    $data['inmobiliario'] = floatval(array_sum($data['inmobiliario']));

                    $respuesta = ModeloContable::mdlCargarArchivo($tabla,$data);

                    $errors = array($respuesta);
                
                    if(in_array('error',$errors)){
    
                        echo'<script>
        
                            swal({
                                    type: "error",
                                    title: "¡No se pudo cargar la planilla!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar"
                                    }).then(function(result) {
                                    if (result.value) {
                                        
                                        
        
                                    }
                                })
        
                            </script>';
    
                    }else{
    
                        echo'<script>
    
                        swal({
                                type: "success",
                                title: "La planilla ha sido cargada correctamente",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"
                                }).then(function(result) {
                                        if (result.value) {
    
    
                                        }
                                    })
    
                        </script>';
    
                    }
    
                }
    
            } */

            if ($respuesta != 'ok'){
    
                echo'<script>

                swal({
                        type: "error",
                        title: `¡No se pudo cargar la planilla!';

                        if($_SESSION['usuario'] == 'tecnicoContable'){
                            echo json_encode($respuesta);
                        }

                        echo '`,
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result) {
                        if (result.value) {
                            
                            

                        }
                    })

                </script>';

            } else {

                echo'<script>

                swal({
                        type: "success",
                        title: "La planilla ha sido cargada correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result) {
                                if (result.value) {


                                }
                            })

                </script>';

            }
        }
            
        
	}

    /*=============================================
	PROCESO EXCEL DATOS
	=============================================*/
    static public function ctrProcesoExcel($ruta,$nombreArchivo,$tabla,$libro){

        $mapping = [
            '1.00.00.00.000' => 'activos',
            '1.01.00.00.000' => 'activoCorriente',
            '2.01.01.00.000' => 'deudaTotal',
            '2.01.01.02.010' => 'prestamos',
            '2.01.01.02.011' => 'prestamos',
            '2.01.01.02.015' => 'prestamos',
            '2.01.01.02.016' => 'prestamos',
            '2.01.01.08.000' => 'prestamos',
            '2.01.01.02.012' => 'tarjetas',
            '2.01.01.02.013' => 'tarjetas',
            '2.01.01.02.014' => 'tarjetas',
            '2.01.01.07.000' => 'mutuales',
            '2.01.01.06.002' => 'cerealPL',
            '2.01.01.01.000' => 'proveedores',
            '5.01.01.14.004' => 'seguros',
            '5.01.01.14.005' => 'seguros',
            '5.01.01.14.006' => 'seguros',
            '5.02.01.02.017' => 'seguros',
            '5.02.01.05.003' => 'seguros',
            '5.02.01.06.011' => 'seguros',
            '2.01.01.09.000' => 'sgr',
            '4.00.00.00.000' => 'ganancias',
            '5.00.00.00.000' => 'perdidas',
            '5.01.00.00.000' => 'perdidasDirectas',
            '2.01.01.02.000' => 'deudaBancaria',
            '1.01.03.03.006' => 'saldoTecnico',
            '1.01.03.03.008' => 'sld',
            '1.01.04.00.000' => 'bienesDeCambio',
            '1.01.01.01.000' => 'cajaBancos',
            '2.01.00.00.000' => 'pasivoCorriente',
            '2.00.00.00.000' => 'pasivoTotal',
            '3.00.00.00.000' => 'patrimonioNeto',
            '4.01.01.00.000' => 'agricultura',
            '4.01.00.00.000' => 'ganaderia',
            '4.01.01.01.000' => 'ganaderia',
            '5.02.01.01.001' => 'ingresoBrutoMensual',
            '5.02.01.06.005' => 'inmobiliario',
            '5.02.01.05.002' => 'cargasSocialesReales',
            '5.02.01.05.001' => 'sueldos',
            '5.02.01.06.006' => 'honorarios',
            '5.02.01.06.007' => 'honorarios',
            '5.02.01.06.008' => 'honorarios',
            '5.02.01.06.009' => 'honorarios',
            '5.03.01.01.004' => 'interesesPagados',
            '4.01.02.01.002' => 'vaquillonasNovillos',
            '4.01.02.01.003' => 'vaquillonasNovillos',
            '4.01.02.01.010' => 'carneSubproductos',
            '4.01.02.01.011' => 'carneSubproductos',
            '4.01.04.01.000' => 'exportacion',
            '4.01.02.01.012' => 'produccionHacienda',
        ];

        $rowNumber = 0;

        $Reader = new SpreadsheetReader($ruta);	
                    
        $sheetCount = count($Reader->sheets());
        
        $data = array('archivo'=>$nombreArchivo,
                      'libro'=>$libro,
                      'prestamos' => array(),
                      'tarjetas' => array(),
                      'activos'=>0,
                      'activoCorriente'=>0,
                      'deudaTotal'=>0,
                      'mutuales'=>0,
                      'proveedores'=>0,
                      'cerealPL'=>0,
                      'seguros'=>array(),
                      'sgr'=>0,
                      'ganancias'=>0,
                      'perdidas'=>0,
                      'perdidasDirectas'=>0,
                      'deudaBancaria'=>0,
                      'saldoTecnico'=>0,
                      'sld'=>0,
                      'bienesDeCambio'=>0,
                      'cajaBancos'=>0,
                      'pasivoCorriente'=>0,
                      'pasivoTotal'=>0,
                      'patrimonioNeto'=>0,
                      'agricultura'=>0,
                      'ganaderia'=>array('4.01.00.00.000'=>0,'4.01.01.01.000'=>0),
                      'resto'=>0,
                      'ingresoBrutoMensual'=>0,
                      'inmobiliario'=>0,
                      'cargasSocialesReales'=>0,
                      'sueldos'=>0,
                      'honorarios'=>array(),
                      'interesesPagados'=>0,
                      'vaquillonasNovillos'=>array(),
                      'carneSubproductos'=>array(),
                      'exportacion'=>0,
                      'produccionHacienda'=>0
        );
       
        for($i=0;$i<$sheetCount;$i++){

            $Reader->ChangeSheet($i);

            foreach ($Reader as $Row){

                if($rowNumber == 4){


                    $año = substr(substr($Row[4],-7),3);

                    $mes = substr(substr($Row[4],-7),0,2);

                    $date = "$año-$mes-01";

                    $data['periodo'] = $date;

                    $item = 'libro';
                    
                    $valor = $libro;

                    $item2 = 'periodo';

                    $valor2 = $data['periodo'];
                                           
                    $cargaValida = ControladorContable::ctrMostrarDatos($item,$valor,$item2,$valor2);
                 
                    if($cargaValida){
                        
                        echo '<script>

                            swal({
                                    type: "error",
                                    title: "Ya hay una planilla ' . ucfirst($libro) . ' en este periodo!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar"
                                    }).then(function(result) {
                                    if (result.value) {
                                        
                                        window.location = "index.php?ruta=contable/contable"
        
                                    }
                                })
        
                            </script>';

                            die();
                    }
                    
                    
                }
                
                if($rowNumber >= 6){

                    if (isset($mapping[$Row[1]])) {

                        $key = $mapping[$Row[1]];
                        
                        if (!isset($data[$key])) {

                            $data[$key] = [];

                        }

                        if(is_array($data[$key])){

                            if ($key == 'ganaderia'){

                                $data[$key][$Row[1]] = $Row[5];

                            } else {

                                $data[$key][] = $Row[5];

                            }

                        } else {

                            $data[$key] = $Row[5];

                        }

                    }

                }
                    
                $rowNumber++;

            }
                
        }
                    
        $data['sgr'] = ($data['sgr'] != null) ? $data['sgr'] : 0; 
        $data['cerealPL'] = ($data['cerealPL'] != null) ? $data['cerealPL'] : 0; 
        $data['inmobiliario'] = ($data['inmobiliario'] != null) ? $data['inmobiliario'] : 0; 
        $data['resto'] = 0;
        $data['prestamos'] = array_sum($data['prestamos']);
        $data['tarjetas'] = array_sum($data['tarjetas']);
        $data['seguros'] = array_sum($data['seguros']);
        $data['honorarios'] = array_sum($data['honorarios']);
        $data['ganaderia'] = $data['ganaderia']['4.01.00.00.000'] - $data['ganaderia']['4.01.01.01.000'];
        $data['vaquillonasNovillos'] = array_sum($data['vaquillonasNovillos']);
        $data['carneSubproductos'] = array_sum($data['carneSubproductos']);

        return ModeloContable::mdlCargarArchivo($tabla,$data);

    }

    /*=============================================
	MOSTRAR DATOS
	=============================================*/
    static public function ctrMostrarDatos($item,$valor,$item2,$valor2){
        
        $tabla = ($valor == 'Paihuen') ? 'contablePaihuen' : 'contable';
        
        return $respuesta = ModeloContable::mdlMostrarDatos($tabla,$item,$valor,$item2,$valor2);
        
    }
    
    
    /*=============================================
    ULTIMO PERIODO
    =============================================*/
    
    static public function ctrUltimoPeriodo(){
        
        $principal = ModeloContable::mdlUltimoPeriodo('Principal','contable');        
        $consolidado = ModeloContable::mdlUltimoPeriodo('Consolidado','contable');
        // $paihuen = ModeloContable::mdlUltimoPeriodo(null,'contablePaihuen');

        $periodos = array($principal[0],$consolidado[0]);

        if(count(array_unique($periodos)) == 1){

            return $principal[0];

        }else{

            return array('error'=>true,'principal'=>$principal[0],'consolidado'=>$consolidado[0]);

        }
        
    }
    
    /*============================================
    CALCULAR DATA
    =============================================*/
    
    static public function ctrCalcularData($periodo){
        
        
        function calcularData($consolidado,$principal,$ultimoMes){
            
            $labelMeses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            $anio = explode('-',$consolidado['periodo']);
            $anio = $anio[0];
            // CAJAS
                /* ECONOMICO */

                    // AGRICULTURA
                        $agricultura1 = $principal['agricultura'];
                        $agricultura2 = $consolidado['agricultura'] - $agricultura1;
                    
                    // GANADERIAS  Y RESTOS
                        $ganaderiaResto1 = $principal['ganaderia'];
                        $ganaderiaResto2 = $consolidado['ganaderia'] - $ganaderiaResto1;
                    
                    // DESGLOSE GANADERIA
                        // $vaquillonasNovillos1 = $principal['vaquillonasNovillos'];
                        // $vaquillonasNovillos = $consolidado['vaquillonasNovillos'] - $vaquillonasNovillos1;

                        // $carneSubproductos1 = $principal['carneSubproductos'];
                        // $carneSubproductos = $consolidado['carneSubproductos'] - $carneSubproductos1;

                        // $exportacion1 = $principal['ganaderiaExportacion'];
                        // $exportacion = $consolidado['ganaderiaExportacion'] - $exportacion1;

                        // $produccionHacienda1 = $principal['produccionHacienda'];
                        // $produccionHacienda = $consolidado['produccionHacienda'] - $produccionHacienda1;

                    // VENTAS TOTALES
                        $ventasTotales = $agricultura1 + $agricultura2 + $ganaderiaResto1 + $ganaderiaResto2; 
                /* FINANCIERO */

                    // DEUDA TOTAL
                        $deudaTotal = floatVal($consolidado['deudaTotal']);

                    // PASIVO TOTAL
                        $pasivoTotal = floatVal($consolidado['pasivoTotal']);

                    // ACTIVO CIRCULANTE

                        $cajaBancos = floatVal($consolidado['cajaBancos']); 
                
                        $activoCirculante = $cajaBancos;
                    
                    // PATRIMONIO NETO
                        $patrimonioNeto = floatVal($consolidado['patrimonioNeto']) + (floatVal($consolidado['ganancias']) - floatVal($consolidado['perdidas']));

                    // DEUDA BANCARIA
                        $deudaBancaria = floatVal($consolidado['deudaBancaria']);

                    // BIENES DE CAMBIO
                        $bienesDeCambio = floatVal($consolidado['bienesDeCambio']);
                    
                    // PASIVO TOTAL
                        $pasivoTotal = floatVal($consolidado['pasivoTotal']);

                /* IMPOSITIVO */

                    // INGRESO BRUTO
                        $ingresosBrutos = floatVal($consolidado['ingresoBrutoMensual']);
                    
                    // INMOBILIARIO / COMUNA
                        $inmobiliarioBarlovento = floatVal($consolidado['inmobiliario']);
                        // $inmobiliarioPaihuen = $paihuen['inmobiliario'];
                        // $comuna = $paihuen['comuna'];

                        // $inmobiliarioComuna = ($inmobiliarioBarlovento + $inmobiliarioPaihuen) / $comuna;
                        // $inmobiliarioComuna =  $inmobiliarioPaihuen + $comuna;
                    
                    // CARGAS SOCIALES REALES
                        $cargasSociales = floatVal($consolidado['cargasSocialesReales']);
                    
                    // SUELDOS 
                        $sueldos = floatVal($consolidado['sueldos']);
                        $sueldos12 = floatVal($consolidado['sueldos']);
                        $sueldos12Honorarios = floatVal($consolidado['sueldos'] + $consolidado['honorarios'] );
                    

            // GRAFICOS
                /* ECONOMICO */

                    // MARGEN SOBRE VENTAS

                        $resultadoExplotacion = (floatVal($consolidado['ganancias']) - floatVal($consolidado['perdidas']));
                        $resultadoExplotacion2 = (floatVal($consolidado['ganancias']) - floatVal($consolidado['perdidasDirectas']));
            
                        $ingresosExplotacion = $consolidado['ganancias'];
            
                        $margenSobreVentas = ($ingresosExplotacion != 0) ? ($resultadoExplotacion2 / $ingresosExplotacion) * 100 : 0;

                    // RENTABILIDAD ECONOMICA

                        $rentabilidadEconomica = ($consolidado['activos'] != 0) ? ($resultadoExplotacion / ($consolidado['activos'])) * 100 : 0;

                /* FINANCIERO */

                    // PRESTAMOS
                        $prestamos = $consolidado['prestamos'];
                        $tarjetas = $consolidado['tarjetas'];
                        $mutuales = $consolidado['mutuales'];
                        $sgr = $consolidado['sgr'];
                        $proveedores = $consolidado['proveedores'];
                        $cerealPL = $consolidado['cerealPL'];

                    // ENDEUDAMIENTO    
    
                        $endeudamiento = array('prestamos'=>$prestamos,'tarjetas'=>$tarjetas,'mutuales'=>$mutuales,'sgr'=>$sgr,'proveedores'=>$proveedores,'cerealPL'=>$cerealPL,'total'=>($prestamos + $tarjetas + $mutuales + $sgr + $proveedores + $cerealPL));

                    // DEUDA BANCARIA 

                        $deudaBancaria = $consolidado['deudaBancaria'];

                /* IMPOSITIVO */

                    // SALDOS
                        $saldos = array('sld'=>$consolidado['sld'],'saldoTecnico'=>$consolidado['saldoTecnico']);
                        
                    // SUELDOS HONORARIOS
                
                        $sueldosHonorarios = ($consolidado['sueldos'] + $consolidado['honorarios']);

                    // SUELDOS HONORARIOS / VENTAS
                
                        $sueldos12Ventas = ($ventasTotales > 0) ? $sueldos12 / $ventasTotales : 0;
                        $sueldos12HonorariosVentas = ($ventasTotales > 0) ? $sueldos12Honorarios / $ventasTotales : 0;           

            return array(
                'periodo'=>$labelMeses[$ultimoMes - 1],
                'periodoVisible'=> $labelMeses[$ultimoMes - 1] . ' ' . $anio,
                'cajas'=>array('agricultura1'=>floatVal($agricultura1),
                               'agricultura2'=>floatVal($agricultura2),
                               'ganaderiaResto1'=>floatVal($ganaderiaResto1),
                               'ganaderiaResto2'=>floatVal($ganaderiaResto2),
                               'deudaTotal'=>($mutuales + $sgr + $deudaBancaria),
                               'deudaBancaria'=>$deudaBancaria,
                               'patrimonioNeto'=>floatVal($patrimonioNeto),
                               'activoCirculante'=>$activoCirculante,
                               'activoCorriente'=>$consolidado['activoCorriente'],
                               'pasivoTotal'=>$pasivoTotal,
                               'pasivoCorriente' => $consolidado['pasivoCorriente'],
                               'bienesDeCambio'=>$bienesDeCambio,
                               'ingresosBrutos'=>$ingresosBrutos,
                               'inmobiliarioComuna'=>0,
                               'cargasSociales'=>$cargasSociales,
                               'sueldos12'=>$sueldos12,
                               'sueldos12Honorarios'=>$sueldos12Honorarios,
                               'sueldos'=>$sueldos),     
                'graficos'=>array('ventasTotales'=>$ventasTotales,
                                  'agricultura1'=>(floatVal($agricultura1)),
                                  'ganaderiaResto1'=>floatVal($ganaderiaResto1),  
                                  'agricultura2'=>(floatVal($agricultura2)),
                                  'ganaderiaResto2'=>floatVal($ganaderiaResto2),
                                  'vaquillonasNovillos'=>floatVal($consolidado['vaquillonasNovillos']),
                                  'carneSubproductos'=>floatVal($consolidado['carneSubproductos']),
                                  'exportacion'=>floatVal($consolidado['ganaderiaExportacion']),
                                  'produccionHacienda'=>floatVal($consolidado['produccionHacienda']),
                                  'margenSobreVentas'=>$margenSobreVentas,
                                  'resultadoExplotacion'=>$resultadoExplotacion,
                                  'resultadoExplotacion2'=>$resultadoExplotacion2,
                                  'rentabilidadEconomica'=>$rentabilidadEconomica,
                                  'endeudamiento' => $endeudamiento,
                                  'deudaBancaria' => $deudaBancaria,
                                  'interesesPagados' => $consolidado['interesesPagados'],
                                  'saldos'=>$saldos,
                                  'sueldos12' => $sueldos12,
                                  'sueldos12Honorarios' => $sueldos12Honorarios,
                                  'sueldos12Ventas' => $sueldos12Ventas,
                                  'sueldos12HonorariosVentas' => $sueldos12HonorariosVentas)
            );

        }

        // ULTIMO MES⁄
        $item = 'libro';

        $valor = 'Principal';

        $item2 = 'periodo';

        $principal = ControladorContable::ctrMostrarDatos($item,$valor,$item2,$periodo);        

        // if(!$principal)
        //     return false;
            
        $valor = 'Consolidado';
        
        $consolidado = ControladorContable::ctrMostrarDatos($item,$valor,$item2,$periodo);
        
        // $item = null;
        
        // $valor = 'Paihuen';
        
        // $paihuen = ControladorContable::ctrMostrarDatos($item,$valor,$item2,$periodo);
                
        // MESES ANTERIORES
        
        $dateExplode = explode('-',$periodo);
        
        $ultimoMes = intval($dateExplode[1]);
        $ultimoAnio = intval($dateExplode[0]);
        
        $data = array(calcularData($consolidado,$principal,$ultimoMes));

        for ($i=0; $i < 6; $i++) { 
            
            
            if($ultimoMes == 1){
    
                $ultimoMes = 12;
                $ultimoAnio = $ultimoAnio - 1;
    
            }else{
    
                $ultimoMes--;
    
            }

            $item = 'libro';

            $valor = 'Principal';
    
            $item2 = 'periodo';

            $valor2 = array($ultimoMes,$ultimoAnio);

            $principal = ControladorContable::ctrMostrarDatos($item,$valor,$item2,$valor2);        

            if(!$principal){
                
                return $data;
            }
            
            $valor = 'Consolidado';
            
            $consolidado = ControladorContable::ctrMostrarDatos($item,$valor,$item2,$valor2);
            
            // $item = null;
    
            // $valor = 'Paihuen';
    
            // $paihuen = ControladorContable::ctrMostrarDatos($item,$valor,$item2,$valor2);

            $data[] = calcularData($consolidado,$principal,$ultimoMes);
            

        }

    
        // Cierra el archivo de log

        return $data;
    }

    
}

    