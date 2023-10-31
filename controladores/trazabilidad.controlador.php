<?php
class ControladorTrazabilidad{



	/*=============================================
	MOSTRAR DATOS
	=============================================*/

	static public function ctrMostrarFaenas($item = null, $valor = null){

		$tabla = "faenas";

		$respuesta = ModeloTrazabilidad::MdlMostrarFaenas($tabla, $item, $valor);

		return $respuesta;
	}

	
}
	


