<?php

require_once "conexion.php";

class ModeloAgro{
	
	/*=============================================
	CARGAR ARCHIVO AGRO
	=============================================*/
	static public function mdlCargarArchivo($tabla,$campos,$data){
		
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla($campos) VALUES $data");

		if($stmt->execute()){
			
			return "ok";	
			
		}else{
			
			return $stmt->errorInfo();
			
		}
	}

	/*=============================================
	MOSTRAR COSTO
	=============================================*/
	static public function mdlMostrarCostos($tabla,$item,$value,$item2,$value2,$item3,$value3){

		$tabla = 'costo'.$tabla;

		if($value2 != ''){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND $item2 = :$item2 AND $item3 = :$item3");
			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
			$stmt -> bindParam(":".$item2, $value2, PDO::PARAM_STR);
			$stmt -> bindParam(":".$item3, $value3, PDO::PARAM_STR);
			
		}else{
			
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item3 = (SELECT MAX($item3) FROM $tabla)");

		}

		$stmt -> execute();
		
		return $stmt -> fetchAll();


		$stmt = null;

	}

	/*=============================================
	CARGAR COSTO
	=============================================*/

	static public function mdlCargarCostos($tabla,$dataSql){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(idPlanificacion,cultivo,costo) VALUES $dataSql");

		if($stmt->execute()){
			
			return "ok";	
			
		}else{
			return json_encode($stmt->errorInfo());			
		}
		

	}

	/*=============================================
	EDITAR COSTO
	=============================================*/

	static public function mdlEditarCosto($tabla,$item,$value,$item2,$value2,$item3,$value3,$costo){

		$tabla = 'costo'.$tabla;

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
		costo = :costo		
		WHERE $item = :$item AND $item2 = :$item2 AND $item3 = :$item3");
	
		$stmt->bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt->bindParam(":".$item2, $value2, PDO::PARAM_STR);
		$stmt->bindParam(":".$item3, $value3, PDO::PARAM_STR);
		$stmt->bindParam(":costo", $costo, PDO::PARAM_STR);

		if($stmt->execute()){
			
			return "ok";	
			
		}else{

			return $stmt->errorInfo();
			return 'error';
			
		}
		
		
		$stmt = null;
	

	}


	/*=============================================
	MOSTRAR DATA
	=============================================*/
	static public function mdlMostrarData($tabla, $item, $value, $item2, $value2, $item3, $value3){

		if($value != ''){

			if($tabla == 'info_planificacion'){
				
				if($item2 != null){
					
					$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item OR $item2 = :$item2");
					$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
					$stmt -> bindParam(":".$item2, $value2, PDO::PARAM_STR);
					
				}else{

					$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ");
					$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
					
				}
						

			}else{

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND $item2 = :$item2 AND $item3 = :$item3 ");
				
				$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
				$stmt -> bindParam(":".$item2, $value2, PDO::PARAM_STR);
				$stmt -> bindParam(":".$item3, $value3, PDO::PARAM_STR);

			}

			
			$stmt -> execute();

			return $stmt -> fetchAll();
			
		}else{
			
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item2 = (SELECT MAX($item2) FROM $tabla) AND $item3 = :$item3");
			
			$stmt -> bindParam(":".$item3, $value3, PDO::PARAM_STR);

			$stmt -> execute();
			
			return $stmt -> fetchAll();

		}


		$stmt = null;

	}

	/*=============================================
	CERRAR CAMPAÑA
	=============================================*/

	static public function mdlCerrarCampania($tabla,$item,$valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET cerrada = 1 WHERE $item = :$item");
	
		$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

		if($stmt->execute()){
			
			return "ok";	
			
		}else{

			return $stmt->errorInfo();
			return 'error';
			
		}
		
		
		$stmt = null;
	

	}

	/*=============================================
	ELIMINAR ARCHIVO
	=============================================*/
	static public function mdlEliminarArchivo($tabla,$item,$value, $item2, $value2, $item3, $value3){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE $item = :$item AND $item2 = :$item2 AND $item3 = :$item3");
			
			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
			
		}else{
			
			$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE $item2 = :$item2 AND $item3 = :$item3");

		}
		
		$stmt -> bindParam(":".$item2, $value2, PDO::PARAM_INT);
		$stmt -> bindParam(":".$item3, $value3, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{
			return $stmt->	errorInfo();
			return "error";	

		}


		$stmt = null;

	}

	/*=============================================
	ULTIMA PLANIFICACION CARGADA POR CAMPAÑA
	=============================================*/
	static public function mdlUltimaCarga($tabla,$campania){

		$stmt = Conexion::conectar()->prepare("SELECT MAX(tipo) as lastUpload FROM $tabla WHERE campania = :campania");
		$stmt -> bindParam(":campania", $campania, PDO::PARAM_STR);
		$stmt -> execute();
		
		return $stmt -> fetch();
	}

	/*=============================================
	CARGAR PLANIFICACION
	=============================================*/
	static public function mdlCargarPlanificacion($tabla,$data){
		$conexion = Conexion::conectar();
		$stmt = $conexion->prepare("INSERT INTO $tabla(campania,tipo) VALUES(:campania,:tipo)");
		$stmt -> bindParam(":campania", $data['campania'], PDO::PARAM_STR);
		$stmt -> bindParam(":tipo", $data['tipo'], PDO::PARAM_STR);
		
		if($stmt->execute()){ 
			
			return $conexion->lastInsertId();
			
		}else{
			
			return $stmt->errorInfo();
			
		}
	
	}


	/*=============================================
	CULTIVOS
	=============================================*/
	static public function mdlCultivosPorPlanificacion($tabla,$idPlanificacion){

		$stmt = Conexion::conectar()->prepare("SELECT DISTINCT(cultivo) FROM $tabla WHERE idPlanificacion = :idPlanificacion");
		$stmt -> bindParam(":idPlanificacion", $idPlanificacion, PDO::PARAM_STR);
		$stmt -> execute();
		
		return $stmt -> fetchAll();
	}


}
