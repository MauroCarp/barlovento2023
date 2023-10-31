<?php

require_once "conexion.php";

class ModeloTrazabilidad{

	static public function mdlMostrarFaenas($tabla, $item, $valor){
		
		if($item != null){

			
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY fecha ASC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();
			
		}else{
			
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY fecha ASC");
			$stmt -> execute();
			return $stmt -> fetchAll();

		}
		
		$stmt = null;

	}


}