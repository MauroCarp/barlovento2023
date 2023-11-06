<?php
error_reporting(E_ERROR | E_PARSE);

function tipoEstInv($cultivo){

    switch ($cultivo) {
        case 'trigo':
        case 'carinata':
        case 'vicia':
        case 'triticale':
        case 'vicia-triticale':
        case 'triticale-vicia':
        case 'avena':
        case 'sevadilla':
        case 'camelina':
            $tipo = 'invernal';
            break;

        case 'maiz1':
        case 'maiz2':
        case 'soja1':
        case 'soja2':
            $tipo = 'estival';
            break;
    }

    return $tipo;

}

function tipoCultivo($cultivo){

    switch ($cultivo) {
        case 'trigo':
        case 'camelina':
        case 'carinata':
            $tipo = 'fina';
            break;

        case 'maiz1':
        case 'maiz2':
        case 'soja1':
        case 'soja2':
        case 'sorgo':
            $tipo = 'gruesa';
            break;

        case 'triticale':
        case 'sevadilla':
        case 'vicia':
        case 'avena':
            $tipo = 'cobertura';
            break;
    }

    return $tipo;

}

function getEtapa($etapa){

    switch ($etapa) {
        case 'Al 31 de Mayo':
            $value = 1;
            break;

        case 'Al 31 de Agosto':
            $value = 2;
            break;

        case 'Al 31 de Diciembre':
            $value = 3;
            break;
        
        default:
            $value = 1;
            break;
    }
    return $value;
}

class ControladorAgro{

	/*=============================================
	CARGAR ARCHIVO
	=============================================*/

	static public function ctrCargarArchivo(){

        
        require_once('extensiones/excel/php-excel-reader/excel_reader2.php');
        require_once('extensiones/excel/SpreadsheetReader.php');

        if(isset($_POST['btnCargar'])){
            
            $error = false;
            
            $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            
        // CARGA PLANIFIACION
            
            if(in_array($_FILES["nuevosDatosPlanificacion"]["type"],$allowedFileType)){
                
                $ruta = "carga/" . $_FILES['nuevosDatosPlanificacion']['name'];
                
                move_uploaded_file($_FILES['nuevosDatosPlanificacion']['tmp_name'], $ruta);
                                                        
                $rowNumber = 0;

                $data = array();
                
                $Reader = new SpreadsheetReader($ruta);	
                
                $sheetCount = count($Reader->sheets());
        
                $tabla = 'planificaciones';
                $campania = $_POST['campania1'] . '/' . $_POST['campania2'];
                $resultado = ModeloAgro::mdlUltimaCarga($tabla,$campania);
                $lastUpload = (is_null($resultado['lastUpload'])) ? -1 : $resultado['lastUpload'];

                $dataPlanificacion = array('tipo'=>$lastUpload + 1,'campania'=>$campania);

                $cargaPlanificacion = ModeloAgro::mdlCargarPlanificacion($tabla,$dataPlanificacion);

                for($i=0;$i<$sheetCount;$i++){
        
                    $Reader->ChangeSheet($i);

                    foreach ($Reader as $Row){
                        // TODO RESOLVER DE QUE FORMA CONVERTIR ESA CELDA PARA PODER SER INTERPRETADA

                        // if($rowNumber == 0 && str_replace(' ','',$Row[0]) != 'Utilizacion_Campo_Lote
                        //     var_dump('entre');
                        //     // echo'<script>

                        //     //     swal({
                        //     //             type: "error",
                        //     //             title: "La planilla seleccionada no corresponde a una planilla de Planificación",
                        //     //             showConfirmButton: true,
                        //     //             confirmButtonText: "Cerrar"
                        //     //             }).then(function(result) {
                        //     //                     if (result.value) {

                        //     //                         window.location = "index.php?ruta=agro/agro"

                        //     //                     }
                        //     //                 })

                        //     //     </script>';
                        //     die();

                        // }

                        // var_dump($Row[1]);

                        $cultivo = strtolower(trim(str_replace(' ','',str_replace('°','',$Row[1]))));

                        if(trim($Row[1]) == 'EL PICHI') $campo = 'pichi';
                        
                        if(trim($Row[1]) == 'LA BETY') $campo = 'bety';

                        if($rowValida && $cultivo != 'cerealesyoleaginosas' && $cultivo != 'elpichi' && $cultivo != 'labety' && $cultivo != ''){

                            $data[] = array('cultivo'=>$cultivo,
                                                 'tipo'=>tipoCultivo($cultivo),
                                                 'tipoEstInv'=>tipoEstInv($cultivo),
                                                 'lote'=>$Row[2],
                                                 'has'=>$Row[7],
                                                 'idPlanificacion'=>$cargaPlanificacion,
                                                 'campo'=> $campo
                            );

                        }

                        if($rowNumber == 3)
                            $rowValida = true;

                        $rowNumber++;

                    }
                        
                }

                $campos = implode(',',array_keys($data[0]));
                $dataSql = array();

                foreach ($data as $value) {

                    $tmp = array();
        
                    foreach ($value as $val) {
                        $tmp[] = (is_numeric($val)) ? $val : "'" . $val . "'";
                    }
        
                    $dataSql[] = "(" . implode(',',$tmp) . ")";
                }
        
                
                $tabla = 'cultivosplanificacion';

                $respuesta = ModeloAgro::mdlCargarArchivo($tabla,$campos,implode(',',$dataSql));

                if($respuesta == 'ok'){
                    echo "<script> window.location = 'index.php?ruta=agro/agro&idPlanificacion=" . $cargaPlanificacion . "&accion=costosCultivos'</script>";
                }else{
                    
                    echo'<script>

                    swal({
                            type: "error",
                            title: "Hubo un error al cargar el excel.Informar",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                            }).then(function(result) {
                                    if (result.value) {
                                        localStorage.removeItem("campaniaAgro")
                                        window.location = "index.php?ruta=agro/agro"

                                    }
                                })

                    </script>';
                die();
                }

            }
            
        // CARGA EJECUCION
            // if(in_array($_FILES["nuevosDatosEjecucion"]["type"],$allowedFileType)){
                
            //     $tabla = 'ejecucion';

            //     $ruta = "carga/" . $_FILES['nuevosDatosEjecucion']['name'];
                
            //     move_uploaded_file($_FILES['nuevosDatosEjecucion']['tmp_name'], $ruta);
                
            //     $nombreArchivo = str_replace(' ', '',$_FILES['nuevosDatosEjecucion']['name']);
                                        
            //     $rowNumber = 0;
                
            //     $rowValida = false;

            //     $data = array();

            //     $cultivoCosto = array();
                
            //     $dateTime = date('Y-m-d H:i:s');

            //     $Reader = new SpreadsheetReader($ruta);	
                
            //     $sheetCount = count($Reader->sheets());
        
            //     $primeraValida = true;

            //     for($i=0;$i<$sheetCount;$i++){
        
            //         $Reader->ChangeSheet($i);

            //         foreach ($Reader as $Row){
                        
            //             if($rowNumber == 1 AND $Row[0] != 'PLANILLA EJECUCION'){

            //                 echo'<script>

            //                     swal({
            //                             type: "error",
            //                             title: "La planilla seleccionada no corresponde a una planilla de Ejecución",
            //                             showConfirmButton: true,
            //                             confirmButtonText: "Cerrar"
            //                             }).then(function(result) {
            //                                     if (result.value) {

            //                                         window.location = "index.php?ruta=agro/agro"

            //                                     }
            //                                 })

            //                     </script>';
            //                 die();

            //             }

            //             if($rowNumber == 0){

            //                 $campania = trim(str_replace('EJECUCION','',$Row[4]));

            //                 list($campania1,$campania2) = explode('-',$campania);

            //             }

            //             if($rowNumber == 1){
            //                 $etapa = getEtapa($Row[4]);
                        
            //                 // VALIDAR SI YA ESTA CARGADA⁄

            //                 $tabla = 'ejecucion';

            //                 $item = 'campania1';
                            
            //                 $item2 = 'campania2';
                            
            //                 $item3 = 'etapa';

            //                 $resultado = ControladorAgro::ctrMostrarData($tabla,$item,$campania1,$item2,$campania2,$item3,$etapa);
                            
            //                if(sizeof($resultado) > 0){
            //                    echo'<script>

            //                        swal({
            //                                type: "error",
            //                                title: "La planilla de la campaña '.$campania1.'-'.$campania2.' , etapa '. $Row[4].' ya ha sido cargada.",
            //                                showConfirmButton: true,
            //                                confirmButtonText: "Cerrar"
            //                                }).then(function(result) {
            //                                if (result.value) {
                                               
            //                                    window.location = "index.php?ruta=agro/agro"

            //                                }
            //                            })

            //                        </script>';
            //                        die();
            //                }
            //             }

            //             if($Row[0] == 'TOTAL'){
            //                 $rowValida = false;
            //             }

            //             if($rowValida){

            //                 if($Row[0] == ''){

            //                     $rowValida = false;

            //                 }else{

            //                     $data = array('campania1'=>$campania1,'campania2'=>$campania2,'etapa'=>$etapa,'campo'=>$campo,'lote'=>$Row[0],'has'=>$Row[1],'cultivo'=>strtolower(trim($Row[2])),'actividad'=>strtolower(trim($Row[3])),'costoActividad'=>$Row[4],'actividad2'=>strtolower($Row[5]),'costoActividad2'=>$Row[6],'periodoTime'=>$dateTime);

            //                     $respuesta = ModeloAgro::mdlCargarArchivo($tabla,$data);                               
                                
            //                     $errors = array($respuesta);
            //                 }

            //             }

            //             if($Row[0] == 'LOTES'){

            //                 $rowValida = true;

            //                 if($primeraValida){

            //                     $campo = 'EL PICHI';
            //                     $primeraValida = false;

            //                 }else{

            //                     $campo = 'LA BETY';

            //                 }

            //             }

                        
            //             $rowNumber++;

            //         }

                    
            //     }
                
            // }

        // VALIDA PROGRAMA DE CARGA                    
            // if(in_array('error',$errors)){

            //     echo'<script>

            //         swal({
            //                 type: "error",
            //                 title: "¡No se pudo cargar la planilla!",
            //                 showConfirmButton: true,
            //                 confirmButtonText: "Cerrar"
            //                 }).then(function(result) {
            //                 if (result.value) {
                                
            //                     window.location = "index.php?ruta=agro/agro"

            //                 }
            //             })

            //         </script>';

            // }else{

            //     echo'<script>

            //     swal({
            //             type: "success",
            //             title: "La planilla ha sido cargada correctamente",
            //             showConfirmButton: true,
            //             confirmButtonText: "Cerrar"
            //             }).then(function(result) {
            //                     if (result.value) {

            //                         window.location = "index.php?ruta=agro/agro"

            //                     }
            //                 })

            //     </script>';

            // }

        }

	}

    /*=============================================
	CARGAR COSTOS
	=============================================*/

	static public function ctrCargarCostos($tabla,$dataSql){

        return $respuesta = ModeloAgro::mdlCargarCostos($tabla,$dataSql);
            
	}

    /*=============================================
	VER COSTOS
	=============================================*/

	static public function ctrMostrarCostos($tabla,$campania,$idPlanificacion){

        return ModeloAgro::mdlMostrarCostos($tabla,$campania,$idPlanificacion);

	}

    /*=============================================
	VER DATA
	=============================================*/
    
	static public function ctrMostrarDataPlanificacion($tabla, $item, $valor, $item2 = null, $valor2 = null, $item3 = null, $valor3 = null){

        return $respuesta = ModeloAgro::mdlMostrarData($tabla, $item, $valor, $item2, $valor2, $item3, $valor3);

	}

    /*=============================================
	ELIMINAR ARCHIVO
	=============================================*/
    
	static public function ctrEliminarArchivo(){
        
        if(isset($_GET['campo']) OR isset($_GET['seccion'])){

            if(isset($_GET['campo'])){
    
                $tabla = $_GET['tabla'];
                
                $item = 'campo';
                
                $value = strtoupper($_GET['campo']);
                
                $item2 = 'campania1';
                
                $value2 = $_GET['campania1'];
                
                $item3 = 'campania2';
                
                $value3 = $_GET['campania2'];
                
                $respuesta = ModeloAgro::mdlEliminarArchivo($tabla,$item,$value, $item2, $value2, $item3, $value3);
                
            }
            
            if(isset($_GET['seccion'])){
    
                $tabla = $_GET['seccion'];
                
                $item = null;
                
                $value = null;
                
                $item2 = 'campania1';
                
                $value2 = $_GET['campania1'];
                
                $item3 = 'campania2';
                
                $value3 = $_GET['campania2'];
                
                $respuesta = ModeloAgro::mdlEliminarArchivo($tabla,$item,$value, $item2, $value2, $item3, $value3);
                
            }

            if($respuesta == "ok"){

                echo'<script>

                swal({
                        type: "success",
                        title: "El archivo ha sido borrado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then(function(result) {
                                if (result.value) {

                                window.location = "index.php?ruta=agro/agro";

                                }
                            })

                </script>';

            }else{

                echo'<script>

                swal({
                        type: "error",
                        title: "El archivo no ha sido borrado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then(function(result) {
                                if (result.value) {

                                window.location = "index.php?ruta=agro/agro";

                                }
                            })

                </script>';
            
            }	

        }
    }

    
    /*=============================================
	ELIMINAR ARCHIVO
	=============================================*/
    
	static public function ctrCultivosUnicosPorPlanificacion($idPlanificacion){
        
        $tabla = 'cultivosplanificacion';

        $resultado = ModeloAgro::mdlCultivosUnicosPorPlanificacion($tabla,$idPlanificacion);

        $cultivos = array();

        foreach ($resultado as $key => $value) {
            $cultivos[] = $value['cultivo'];
        }

        return $cultivos;

    }

    static public function ctrMostrarCampanias($idPlanificacion = null, $campos = '*' ,$full = false){

        $tabla = 'planificaciones';

        return ModeloAgro::mdlMostrarCampanias($tabla,$idPlanificacion,$campos,$full);

    }

    static public function ctrMostrarCargasPorCampania($campania){

        $tabla = 'planificaciones';

        return ModeloAgro::mdlMostrarCargasPorCampania($tabla,$campania);

    }

    static public function ctrMostrarDataCultivosPlanificacion($idPlanificacion){

        $tabla = 'cultivosplanificacion';

        return ModeloAgro::mdlMostrarDataCultivosPlanificacion($tabla,$idPlanificacion['id']);

    }

    static public function ctrGetCampaignId($campania,$cargaPlanificacion){

        $tabla = 'planificaciones';

		$idPlanificacion = ModeloAgro::mdlGetCampaignId($tabla,$campania,$cargaPlanificacion);

        return $idPlanificacion['id'];

    }

    

}

	