<?php
class ControladorTrazabilidad{

	static public function ctrMostrarFaenas($item = null, $valor = null){

		$tabla = "faenas";

		$respuesta = ModeloTrazabilidad::MdlMostrarFaenas($tabla, $item, $valor);

		return $respuesta;
	}

	static public function ctrNuevaFaena(){

		if(isset($_POST['btnCargarFaena'])){
	
			require_once('extensiones/excel/php-excel-reader/excel_reader2.php');
			require_once('extensiones/excel/SpreadsheetReader.php');

			$nombre = $_POST['nombreFaena'];

			if($_POST['nombreFaena'] =! '' && $_POST['fechaFaena'] != ''){

				$datosFaena = array('nombre'=>$nombre,'fecha'=>$_POST['fechaFaena']);
				$tabla = 'faenas';
				$idFaena = ModeloTrazabilidad::mdlNuevaFaena($tabla,$datosFaena);

				if(!is_array($idFaena)){

					if(isset($_FILES['excelTD']) && isset($_FILES['excelWC']) && isset($_FILES['excelTrazabilidad'])){
		
						$allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
			
						$dataTD = array();
						$dataWC = array();
						$dataTrazabilidad = array();

						// TOMA DE DATOS CARGA EXCEL TD
						if(in_array($_FILES["excelTD"]["type"],$allowedFileType)){
			
							$ruta = "carga/" . $_FILES['excelTD']['name'];
							
							move_uploaded_file($_FILES['excelTD']['tmp_name'], $ruta);
																		
							$rowNumber = 0;
							
							$rowValida = false;
								
							$Reader = new SpreadsheetReader($ruta);	
							
							$sheetCount = count($Reader->sheets());
															
							for ($i=0;$i<$sheetCount;$i++){
			
								$Reader->ChangeSheet($i);
			
								foreach ($Reader as $Row){

									if($Row[0] == 'KG TOTAL') $rowValida = false;

									if ($rowValida){			
										
										$dataTD[] = array(
											'rfid'	 =>$Row[1],
											'mmGrasa'=>$Row[2],
											'peso'=>$Row[3],
											'sexo'=>$Row[4],
											'clasificacion'=>$Row[5],
											'aob'	=>$Row[6],
											'refEco'=>$Row[7]);
									}
									
									if ($rowNumber == 6){

										if($Row[0] == 'Fecha'){
											$rowValida = true;
										} else {

											// TODO ELIMINAR FAENA CREADA Y SUS ENLAZADOS

											echo '<script>

												swal({
														type: "error",
														title: "La planilla seleccionada no corresponde a una planilla de Toma de Decisión",
														showConfirmButton: true,
														confirmButtonText: "Cerrar"
														}).then(function(result) {
																if (result.value) {

																	window.location = "index.php?ruta=trazabilidad/index"

																}
															})

												</script>';
                            					die();

										}

									}
										
									$rowNumber++;
			
								}
									
							}
										
						} else {

							ModeloTrazabilidad::mdlEliminarFaena($idFaena);

							echo'<script>

							swal({
									type: "error",
									title: "El formato de la planilla Toma de Decisión no es compatible con Excel",
									showConfirmButton: true,
									confirmButtonText: "Cerrar"
									}).then(function(result) {
											if (result.value) {

												window.location = "index.php?ruta=trazabilidad/index"

											}
										})

							</script>';

							die();							

						}

						// TOMA DE DATOS CARGA EXCEL WINCAMPO
						if(in_array($_FILES["excelWC"]["type"],$allowedFileType)){
			
							$ruta = "carga/" . $_FILES['excelWC']['name'];
							
							move_uploaded_file($_FILES['excelWC']['tmp_name'], $ruta);
																		
							$rowNumber = 0;
							
							$rowValida = false;
								
							$Reader = new SpreadsheetReader($ruta);	
							
							$sheetCount = count($Reader->sheets());
															
							for ($i=0;$i<$sheetCount;$i++){
			
								$Reader->ChangeSheet($i);
			
								foreach ($Reader as $Row){

									if ($rowValida){			
										
										$dataWC[] = array('tropa' 		=> $Row[2],
														  'rfid'		=> str_replace('9990540000','',$Row[3]),
														  'caravana'	=> $Row[4],
														  'categoria'	=> $Row[5],
														  'raza'		=> $Row[6],
														  'estado'		=> $Row[7],
														  'ingreso'		=> date('Y-m-d', strtotime(str_replace('-', '/', $Row[8]))),
														  'salida'		=> date('Y-m-d', strtotime(str_replace('-', '/', $Row[9]))),
														  'kgIngreso'	=> $Row[12],
														  'kgEgreso'	=> $Row[13],
														  'kgProducido' => $Row[14],
														  'dias'	    => $Row[17],
														  'adpv'	    => $Row[18],
														  'convTC'      => $Row[19],
														  'convMS'      => $Row[20],
														  'kilosTC'     => $Row[21],
														  'kilosMS'     => $Row[22],
														  'costo'       => $Row[23],
														  'proveedor'   => $Row[24],
														  'provincia'   => $Row[25],
														  'localidad'   => $Row[26],
														  'transaccionWC'=> $Row[29],
														  'clienteDestinoVenta'=> $Row[30],
														  'corral'=> $Row[32]
										);
										
									}
									
									if ($rowNumber == 7){

										if($Row[0] == 'Hotelero'){
											$rowValida = true;
										} else {

											echo'<script>

											swal({
													type: "error",
													title: "La planilla seleccionada no corresponde a una planilla de WinCampo",
													showConfirmButton: true,
													confirmButtonText: "Cerrar"
													}).then(function(result) {
															if (result.value) {

																window.location = "index.php?ruta=trazabilidad/index"

															}
														})

											</script>';
											die();
										}

									}
										
									$rowNumber++;
			
								}
									
							}
										
						} else {

							ModeloTrazabilidad::mdlEliminarFaena($idFaena);

							echo'<script>

							swal({
									type: "error",
									title: "El formato de la planilla WinCampo no es compatible con Excel",
									showConfirmButton: true,
									confirmButtonText: "Cerrar"
									}).then(function(result) {
											if (result.value) {

												window.location = "index.php?ruta=trazabilidad/index"

											}
										})

							</script>';
							die();	

						}

						// TOMA DE DATOS EXCEL TRAZABILIDAD

						if(in_array($_FILES["excelTrazabilidad"]["type"],$allowedFileType)){
			
							$ruta = "carga/" . $_FILES['excelTrazabilidad']['name'];
							
							move_uploaded_file($_FILES['excelTrazabilidad']['tmp_name'], $ruta);
																		
							$rowNumber = 0;
							
							$rowValida = false;
								
							$Reader = new SpreadsheetReader($ruta);	
							
							$sheetCount = count($Reader->sheets());
															
							for ($i=0;$i<$sheetCount;$i++){
			
								$Reader->ChangeSheet($i);
			
								foreach ($Reader as $Row){

									if ($rowValida){			
										
										$rfid = ($Row[0] != '') ? $Row[0] : $rfid;

										$dataTrazabilidad[] = array('rfid'		   => $rfid,
																	'correlacion'  => $Row[1],
																	'garron'	   => $Row[2],
																	'kilos'		   => $Row[3],
																	'denominacion' => $Row[4],
																	'tipificacion' => $Row[5],
																	'gordo'		   => $Row[6],
																	'den'		   => $Row[7]);
										
									}
									
									if ($rowNumber == 0){

										if($Row[0] == 'Caravana'){
											$rowValida = true;
										} else {

											// TODO ELIMINAR FAENA CREADA Y SUS ENLAZADOS

											echo'<script>

											swal({
													type: "error",
													title: "La planilla seleccionada no corresponde a una planilla de Trazabilidad",
													showConfirmButton: true,
													confirmButtonText: "Cerrar"
													}).then(function(result) {
															if (result.value) {

																window.location = "index.php?ruta=trazabilidad/index"

															}
														})

											</script>';
											die();
										}

									}
										
									$rowNumber++;
			
								}
									
							}
										
						} else {

							ModeloTrazabilidad::mdlEliminarFaena($idFaena);

							echo'<script>

							swal({
									type: "error",
									title: "El formato de la planilla Trazabilidad no es compatible con Excel",
									showConfirmButton: true,
									confirmButtonText: "Cerrar"
									}).then(function(result) {
											if (result.value) {

												window.location = "index.php?ruta=trazabilidad/index"

											}
										})

							</script>';
							die();				
						}
						
						// CARGA DE EXCELS
						$tabla = 'tdanimales';
						
						$cargaTD = ControladorTrazabilidad::ctrCargarExcel($tabla,$idFaena,$dataTD);
					
						if($cargaTD != 'ok'){

							ModeloTrazabilidad::mdlEliminarFaena($idFaena);
							
							echo'<script>

									swal({
											type: "error",
											title: "Error al cargar Excel Toma de Decisión a la base de datos. Informar",
											showConfirmButton: true,
											confirmButtonText: "Cerrar"
											}).then(function(result) {
													if (result.value) {

														window.location = "index.php?ruta=trazabilidad/index"

													}
												})

							</script>';

							die();	

						}

						$tabla = 'wcanimales';
						
						$cargaWC = ControladorTrazabilidad::ctrCargarExcel($tabla,$idFaena,$dataWC);
						
						if($cargaWC != 'ok'){

							ModeloTrazabilidad::mdlEliminarFaena($idFaena);

							echo'<script>

									swal({
											type: "error",
											title: "Error al cargar Excel WinCampo a la base de datos. Informar",
											showConfirmButton: true,
											confirmButtonText: "Cerrar"
											}).then(function(result) {
													if (result.value) {

														window.location = "index.php?ruta=trazabilidad/index"

													}
												})

							</script>';

							die();	
						}
						
						$tabla = 'trazanimales';

						$cargaTrazabilidad = ControladorTrazabilidad::ctrCargarExcel($tabla,$idFaena,$dataTrazabilidad);
			
						
						if($cargaTrazabilidad != 'ok'){

							ModeloTrazabilidad::mdlEliminarFaena($idFaena);

							echo'<script>

								swal({
										type: "error",
										title: "Error al cargar Excel Trazabilidad a la base de datos. Informar",
										showConfirmButton: true,
										confirmButtonText: "Cerrar"
										}).then(function(result) {
												if (result.value) {

													window.location = "index.php?ruta=trazabilidad/index"

												}
											})

							</script>';						

						}


						echo'<script>

							swal({
								type: "success",
								title: "La carga se ha realizado correctamente",
								}).then(function(result) {
										window.location = "index.php?ruta=trazabilidad/index";
								})

						</script>';

						die;

					} else {

						ModeloTrazabilidad::mdlEliminarFaena($idFaena);

						echo'<script>

						swal({
								type: "error",
								title: "No se cargaron los tres archivos de Excel",
								showConfirmButton: true,
								confirmButtonText: "Cerrar"
								}).then(function(result) {
										if (result.value) {

											window.location = "index.php?ruta=trazabilidad/index"

										}
									})

						</script>';
						die();						

					}

				} else {

					echo'<script>

						swal({
								type: "error",
								title: "No se pudo crear la faena. Informar",
								showConfirmButton: true,
								confirmButtonText: "Cerrar"
								}).then(function(result) {
										if (result.value) {

											window.location = "index.php?ruta=trazabilidad/index"

										}
									})

						</script>';
						die();		
				}

			} else {

				echo'<script>

				swal({
						type: "error",
						title: "El nombre y/o Fecha no puede ir vacio",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result) {
								if (result.value) {

									window.location = "index.php?ruta=trazabilidad/index"

								}
							})

				</script>';
				die();	

			}
    
		} 

	}


	static public function ctrCargarExcel($tabla,$idFaena,$datos){


		$campos = implode(',', array_keys($datos[0]));

		$dataSql = array();

		foreach ($datos as $value) {

			$tmp = array();

			foreach ($value as $val) {
				$tmp[] = (is_numeric($val)) ? $val : "'" . $val . "'";
			}

			$dataSql[] = "(" . $idFaena . "," . implode(',',$tmp) . ")";
		}

		$dataSql = implode(',',$dataSql);

		return ModeloTrazabilidad::mdlCargarExcel($tabla,$campos,$dataSql);

	}

	static public function ctrEliminarFaena($idFaena){

		return ModeloTrazabilidad::mdlEliminarFaena($idFaena);
		
	}
	
}
	


