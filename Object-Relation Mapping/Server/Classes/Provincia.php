<?php
/**
 * @version 0.1
 * @author David Díaz
 * @date 2015-01-17
 * Clase de provincias.
 *
 */

require_once ('../ORM/ORM.php');

class Provincia extends ORM{
	public $id, $nombre;
	protected static $table = "provincias";
	
	public function __construct($data){
		parent::__construct();
		if($data && sizeof($data)){
			$this->populateFromRow($data);
		}
	}
	
	public function populateFromRow($data){
		$this->id = isset($data['id']) ? intval($data['id']) : null;
		$this->nombre = isset($data['nombre']) ? $data['nombre'] : null;
	}
	
	
}