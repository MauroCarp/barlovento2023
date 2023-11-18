<?php
class ControladorPastoreo{

	/*=============================================
	CARGAR PLANILLA 
	=============================================*/

	static public function ctrCargarArchivo(){

        if( isset($_POST["btnCargar"]) ){
            
            require_once('extensiones/excel/php-excel-reader/excel_reader2.php');
            require_once('extensiones/excel/SpreadsheetReader.php');
            
            $error = false;

            
            if (isset($_FILES['nuevosDatosPastoreo'])){

                $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

                if(in_array($_FILES["nuevosDatosPastoreo"]["type"],$allowedFileType)){

                    $ruta = "carga/" . $_FILES['nuevosDatosPastoreo']['name'];
                    move_uploaded_file($_FILES['nuevosDatosPastoreo']['tmp_name'], $ruta);

                    $Reader = new SpreadsheetReader($ruta);	

                    $sheetCount = count($Reader->sheets());
                    
                    $rowNumber = 0;

                    $rowValida = false;

                    $data = array();

                    $fechaRegistro = '';

                    for($i=0;$i<$sheetCount;$i++){

                        $Reader->ChangeSheet($i);

                        foreach ($Reader as $Row){

                            if($rowNumber == 0){
                            
                                $fechaRegistro = DateTime::createFromFormat('m-d-y', $Row[3])->format('Y-m-d');

                            }

                            if($rowNumber == 3){
                                $rowValida = true;
                            }

                            if($rowValida){

                                if($Row[0] == '') break;

                                $data[] = array('celula'=>"'" . ControladorPastoreo::ctrGetCelula($Row[0]) . "'",
                                                'lote'=>$Row[0],
                                                'parcela'=>$Row[1],
                                                'ingresoPlanificado'=> "'" . DateTime::createFromFormat('m-d-y', $Row[2])->format('Y-m-d') . "'",
                                                'salidaPlanificado'=> "'" . DateTime::createFromFormat('m-d-y', $Row[3])->format('Y-m-d') . "'",
                                                'recuperacion'=>$Row[8],
                                                'fechaRegistro'=> "'" . $fechaRegistro . "'"  
                                );


                            }

                            $rowNumber++;
                            
                        }		

                    }

                    $tabla = 'pastoreos';

                    for ($i=0; $i < sizeof($data); $i++) { 
                        $data[$i] = "(" . implode(',',$data[$i]) . ")";
                    }

                    $resultado = ModeloPastoreo::mdlCargarRegistros($tabla,implode(',',$data));


                    if($resultado == "ok"){
                        echo'<script>

                                swal({
                                    type: "success",
                                    title: "Los datos han sido cargados correctamente",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar"
                                    }).then(function(result) {
                                                if (result.value) {

                                                window.location = "pastoreo";

                                                }
                                            })

                                </script>';
                    }else{
                        echo'<script>

                                swal({
                                        type: "error",
                                        title: "Hubo un error, los datos no fueron cargados.",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar"
                                        }).then(function(result) {
                                        if (result.value) {

                                        window.location = "pastoreo";

                                        }
                                    })

                            </script>';
                    }

                }
            }

            die();
        }

	}

    /*=============================================
	MOSTRAR DATOS 
	=============================================*/

	static public function ctrMostrarRegistros($campo, $item, $valor,$item2 = null,$valor2 = null){

		$tabla = "pastoreo";

		$respuesta = ModeloPastoreo::mdlMostrarRegistros($tabla,$campo, $item, $valor);

        if($item == NULL){

            $data = array();

            foreach ($respuesta as $key => $value) {
                $data[$key]['id'] = $value['id'];
                $data[$key]['tropa'] = $value['tropa'];
                $data[$key]['fechaEntrada'] = $value['fechaEntrada'];
                $data[$key]['fechaSalida'] = $value['fechaSalida'];
    
                if($value['fechaSalida'] != ''){
    
                    $fechaEntrada = new DateTime($value['fechaEntrada']);
                    $fechaSalida = new DateTime($value['fechaSalida']);
    
                    // Obtener la diferencia entre las fechas
                    $intervalo = $fechaEntrada->diff($fechaSalida);
    
                    // Obtener la diferencia en días
                    $diferenciaEnDias = $intervalo->days;
                    $data[$key]['diasDesdeUDP'] = $diferenciaEnDias;
                    $data[$key]['diasProxPastorear'] = $diferenciaEnDias + 1;
                                    
                } else {
    
                    $data[$key]['diasDesdeUDP'] = '-';
    
                    $data[$key]['diasProxPastorear'] = '-';
                    
                }
                
    
            }

            return $data;

        } else {
            return $respuesta;
        }

	}
	

	/*=============================================
	CARGAR REGISTRO 
	=============================================*/

	static public function ctrCargarRegistro(){

		$tabla = "pastoreo";

        if(isset($_POST['cargarPastoreo'])){
            
            $data = array('fechaEntrada'=>$_POST['entradaReal'],'fechaSalida'=>$_POST['salidaReal']);

            $respuesta = ModeloPastoreo::mdlEditarRegistro($tabla,$data,'id',$_POST['idRegistro']);

            if($respuesta == 'ok'){
                
                echo'<script>
                
                swal({
                    type: "success",
                    title: "Registro cargado correctamente",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result) {
                    if (result.value) {
                        
                        window.location.href = `index.php?ruta=diasPastoreo`;
                    }
                })
                
                </script>';
            }else{
                
                echo'<script>
    
                    swal({
                            type: "error",
                            title: "¡Hubo un error al modificar el registro!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                            }).then(function(result) {
                            if (result.value) {
                                
                                window.location.href = `index.php?ruta=diasPastoreo`;
                                
    
                            }
                        })
    
                    </script>';
            }

        }

	}
	
	/*=============================================
	CARGAR REGISTRO 
	=============================================*/

	static public function ctrEliminarRegistro(){

		$tabla = "pastoreo";

        if(isset($_GET['accion']) && $_GET['accion'] == 'eliminarRegistro'){
            
            $respuesta = ModeloPastoreo::mdlEliminarRegistro($tabla,'id',$_GET['id']);
            
            if($respuesta == 'ok'){
                
                echo'<script>
                
                swal({
                    type: "success",
                    title: "Registro eliminado correctamente",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result) {
                    if (result.value) {
                        
                        window.location.href = `index.php?ruta=diasPastoreo`;
                    }
                })
                
                </script>';
            }else{
                
                echo'<script>
    
                    swal({
                            type: "error",
                            title: "¡Hubo un error al eliminar el registro!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                            }).then(function(result) {
                            if (result.value) {
                                
                                window.location.href = `index.php?ruta=diasPastoreo`;
                                
    
                            }
                        })
    
                    </script>';
            }

        }

	}

	static public function ctrFechaExcel($fecha){

        list($day,$month,$year) = explode('-',$fecha);

        return '20' . $year . '-' . $month . '-' . $day;
    }

	static public function ctrGetCelula($lote){
        
        switch ($lote) {
            case '15':
            case '11':
                $celula = 'roja';        
                break;

            case '1':
            case '2':
            case '14':
                $celula = 'amarilla';        
                break;

            case '3':
            case '4':
                $celula = 'naranja';        
                break;
            
        }

        return $celula;
    }
	
	
}

