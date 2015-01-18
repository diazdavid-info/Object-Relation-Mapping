<?php
require_once ('../Classes/LinkClasses.php');
class Rest{
	//public $tipo = "application/json";
	public $typeResponse = "application/json";
	//public $datosPeticion = array();
	public $dataRequest = array();
	//private $_codEstado = 200;
	private $codState = 200;
	
// 	public function __construct() {
// 		$this->tratarEntrada();
// 	}
	
	public function sendResponse($data, $state) {
		$this->codState = ($state) ? $state : 200;//si no se envía $estado por defecto será 200
		$this->setHeader();
		echo $data;
		exit;
	}
	
	private function getCodState() {
		$state = array(
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				204 => 'No Content',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				500 => 'Internal Server Error');
		$response = ($state[$this->codState]) ? $state[$this->codState] : $state[500];
		return $response;
	}
	
	private function setHeader() {
		header("HTTP/1.1 " . $this->codState . " " . $this->getCodState());
		header("Content-Type:" . $this->typeResponse . ';charset=utf-8');
	}
	
	private function limpiarEntrada($data) {
		$entrada = array();
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$entrada[$key] = $this->limpiarEntrada($value);
			}
		} else {
			if (get_magic_quotes_gpc()) {
				//Quitamos las barras de un string con comillas escapadas
				//Aunque actualmente se desaconseja su uso, muchos servidores tienen activada la extensión magic_quotes_gpc.
				//Cuando esta extensión está activada, PHP añade automáticamente caracteres de escape (\) delante de las comillas que se escriban en un campo de formulario.
				$data = trim(stripslashes($data));
			}
			//eliminamos etiquetas html y php
			$data = strip_tags($data);
			//Conviertimos todos los caracteres aplicables a entidades HTML
			$data = htmlentities($data);
			$entrada = trim($data);
		}
		return $entrada;
	}
	private function tratarEntrada() {
		$metodo = $_SERVER['REQUEST_METHOD'];
		switch ($metodo) {
			case "GET":
				$this->datosPeticion = $this->limpiarEntrada($_GET);
				break;
			case "POST":
				$this->datosPeticion = $this->limpiarEntrada($_POST);
				break;
			case "DELETE"://"falling though". Se ejecutará el case siguiente
			case "PUT":
				//php no tiene un método propiamente dicho para leer una petición PUT o DELETE por lo que se usa un "truco":
				//leer el stream de entrada file_get_contents("php://input") que transfiere un fichero a una cadena.
				//Con ello obtenemos una cadena de pares clave valor de variables (variable1=dato1&variable2=data2...)
				//que evidentemente tendremos que transformarla a un array asociativo.
				//Con parse_str meteremos la cadena en un array donde cada par de elementos es un componente del array.
				parse_str(file_get_contents("php://input"), $this->datosPeticion);
				$this->datosPeticion = $this->limpiarEntrada($this->datosPeticion);
				break;
			default:
				$this->response('', 404);
				break;
		}
	}
	
	
}