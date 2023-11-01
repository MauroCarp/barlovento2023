<?php

require_once "conexion.php";

class ModeloTrazabilidad{

	static public function mdlMostrarFaenas($tabla, $item, $valor){
		
		if($item != null){

			
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY created_at DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();
			
		}else{
			
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY created_at DESC");
			$stmt -> execute();
			return $stmt -> fetchAll();

		}
		
		$stmt = null;

	}

	static public function mdlNuevaFaena($tabla,$datos){

		$conexion = Conexion::conectar(); 
		$stmt = $conexion->prepare("INSERT INTO $tabla(nombre, fecha) VALUES (:nombre, :fecha)");
		
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
		
		if($stmt->execute()){ 
			
			return $conexion->lastInsertId();
			
		}else{
			
			return $stmt->errorInfo();
			
		}
		
	}

	static public function mdlCargarExcel($tabla,$campos,$datos){

		$conexion = Conexion::conectar(); 
		$stmt = $conexion->prepare("INSERT INTO " . $tabla . "(idFaena, " . $campos . ") VALUES " . $datos );
		
		if($stmt->execute()){ 

			return 'ok';
			
		}else{
			
			return $stmt->errorInfo();
			
		}
		
	}

	static public function mdlEliminarFaena($idFaena){

		$conexion = Conexion::conectar(); 
		$stmt = $conexion->prepare("DELETE FROM faenas WHERE id = :id");

		$stmt->bindParam(":id", $idFaena, PDO::PARAM_STR);
		
		if($stmt->execute()){ 

			return 'ok';
			
		}else{
			
			return $stmt->errorInfo();
			
		}
		
	}


}