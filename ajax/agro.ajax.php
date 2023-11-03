<?php

require_once "../controladores/agro.controlador.php";
require_once "../modelos/agro.modelo.php";

class AjaxAgro{

	/*=============================================
	EDITAR USUARIO
	=============================================*/	

	public $campania;
	
	public $campania1;

	public $campania2;

    public $campo;

    public $etapa;

    public $seccion;

	public $data;

	public function ajaxMostrarData(){

		$item = "campania1";

		$valor = $this->campania1;
		
		$item2 = "campania2";

		$valor2 = $this->campania2;

		$tabla = $this->seccion;
		
		if($tabla == 'planificacion'){

			$item3 = "campo";
	
			$valor3= $this->campo;
			
		}else{

			$item3 = "etapa";
	
			$valor3= $this->etapa;

		}


		$respuesta = ControladorAgro::ctrMostrarData($tabla, $item, $valor, $item2, $valor2, $item3, $valor3);
		
		echo json_encode($respuesta);

	}

	public function ajaxMostrarInfo(){

		$item = "campania";

		$valor = $this->campania;

		$tabla = $this->seccion;

		$respuesta = ControladorAgro::ctrMostrarData($tabla, $item, $valor, $item2, $valor2, $item3, $valor3);
		
		echo json_encode($respuesta);

	}

	public function ajaxCerrarCampania(){

		$item = "campania";

		$valor = $this->campania;

		$respuesta = ControladorAgro::ctrCerrarCampania($item, $valor);

		echo json_encode($respuesta);

	}

	public function ajaxMostrarCostos(){

		$item = 'cultivo';

		$valor = '';

		$item2 = "campania1";
		
		$valor2 = $this->campania1;

		$item3 = "campania2";
		
		$valor3 = $this->campania2;

		$tabla = $this->seccion;

		$respuesta = ControladorAgro::ctrMostrarCostos($tabla,$item,$cultivo,$item2,$campania1,$item3,$campania2);

		echo json_encode($respuesta);

	}

	public function ajaxCargarCostos(){

		$tabla = 'costocultivos';

		$data = (array)$this->data;
		
		$cultivos = (array)$data['cultivos'];

		$cultivosSql = array();
		
		foreach ($cultivos as $cultivo => $costo) {

			$cultivosSql[] = '(' . $data['idPlanificacion'] . ',"' . $cultivo . '",' . $costo . ')';
			
		}


		echo ControladorAgro::ctrCargarCostos($tabla,implode(',',$cultivosSql));
		
	}
}

/*=============================================
EDITAR USUARIO
=============================================*/
if(isset($_POST["accion"])){

	$accion = $_POST['accion'];

	if($accion == 'mostrarData'){

		$mostrarData = new AjaxAgro();
        $mostrarData -> campania = $_POST["campania"];
        $mostrarData -> seccion = $_POST["seccion"];

		if($_POST['seccion'] == 'planificacion'){
			$mostrarData -> campo = $_POST["campo"];
		}else{
			$mostrarData -> etapa = $_POST["etapa"];
		}

        $mostrarData -> ajaxMostrarData();

    }

	if($accion == 'mostrarInfo'){

		$mostrarInfo = new AjaxAgro();
        $mostrarInfo -> campania = $_POST["campania"];
        $mostrarInfo -> seccion = $_POST["seccion"];
        $mostrarInfo -> ajaxMostrarInfo();

    }

	if($accion == 'mostrarCostos'){

		$mostrarData = new AjaxAgro();
        $mostrarData -> campania1 = $_POST["campania1"];
        $mostrarData -> campania2 = $_POST["campania2"];
        $mostrarData -> seccion = $_POST["seccion"];
        $mostrarData -> ajaxMostrarCostos();

    }

	if($accion == 'cerrarPlanifiacion'){

		$mostrarData = new AjaxAgro();
        $mostrarData -> campania = $_POST["campania"];
        $mostrarData -> ajaxCerrarCampania();

    }

	if($accion == 'cargarCostos'){
		$data = json_decode($_POST['data']);
		$cargarCostos = new AjaxAgro;
		$cargarCostos->data = $data;
		$cargarCostos-> ajaxCargarCostos();
	}

}

