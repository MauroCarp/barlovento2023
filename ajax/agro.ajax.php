<?php

require_once "../controladores/agro.controlador.php";
require_once "../modelos/agro.modelo.php";

class AjaxAgro{

	/*=============================================
	EDITAR USUARIO
	=============================================*/	

	public $campania;
	
	public $idPlanificacion;

	public $carga;

    public $campo;

    public $etapa;

    public $seccion;

	public $data;

	public function ajaxMostrarDataPlanificacion(){

		$cargaPlanificacion = $this->carga;
		$campania = $this->campania;

		$idPlanificacion = ControladorAgro::ctrGetCampaignId($campania,$cargaPlanificacion);

		$cultivos = ControladorAgro::ctrMostrarDataCultivosPlanificacion($idPlanificacion);

		$costos = ControladorAgro::ctrMostrarCostos('planificaciones',$campania,$idPlanificacion);

		$lotes = ControladorAgro::ctrGetLotes($campania);

		$dataCostos = array();

		foreach ($costos as $costo) {
			$dataCostos[$costo['cultivo']] = $costo['costo'];
 		}

		$data = array('cultivos'=>$cultivos,'costos'=>$dataCostos,'lotes'=>$lotes);

		echo json_encode($data);

	}

	public function ajaxMostrarCostos(){

		$tabla = 'planificaciones';

		$idCampania = ControladorAgro::ctrGetCampaignId($this->campania,$this->carga);
		// echo json_encode($idCampania);
		$respuesta = ControladorAgro::ctrMostrarCostos($tabla,$this->campania,$idCampania);

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

	public function ajaxEjecucionValido(){

	$tabla = 'ejecuciones';

	$campania = $this->campania;
	
	echo json_encode(ControladorAgro::ctrMostrarEjecucion($tabla,$campania));
	
	}

}


/*=============================================
EDITAR USUARIO
=============================================*/
if(isset($_POST["accion"])){

	$accion = $_POST['accion'];

	if($accion == 'mostrarDataPlanificacion'){
		$mostrarData = new AjaxAgro();
        $mostrarData -> carga = $_POST["carga"];
        $mostrarData -> campania = $_POST["campania"];
        $mostrarData -> ajaxMostrarDataPlanificacion();

    }

	// if($accion == 'mostrarInfo'){

	// 	$mostrarInfo = new AjaxAgro();
    //     $mostrarInfo -> campania = $_POST["campania"];
    //     $mostrarInfo -> seccion = $_POST["seccion"];
    //     $mostrarInfo -> ajaxMostrarInfo();

    // }

	if($accion == 'mostrarCostos'){

		$mostrarData = new AjaxAgro();
        $mostrarData -> campania = $_POST["campania"];
        $mostrarData -> carga = $_POST["cargaCampania"];
        $mostrarData -> ajaxMostrarCostos();

    }

	if($accion == 'cargarCostos'){
		$data = json_decode($_POST['data']);
		$cargarCostos = new AjaxAgro;
		$cargarCostos->data = $data;
		$cargarCostos-> ajaxCargarCostos();
	}

	if($accion == 'ejecucion'){
		$ejecucionValido = new AjaxAgro;
		$ejecucionValido->campania = $_POST['campania'];
		$ejecucionValido-> ajaxEjecucionValido();
	}

}

