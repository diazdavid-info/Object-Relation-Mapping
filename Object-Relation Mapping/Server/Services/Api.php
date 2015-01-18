<?php
require_once("Rest.php");
class Api extends Rest {
	private $_metodo;
	private $_argumentos;
	public function receiveRequest() {
		if (isset($_REQUEST['q'])) {
			$url = explode('/', trim($_REQUEST['q']));
			$url = array_filter($url);
			$this->_metodo = strtolower(array_shift($url));
			//echo $this->_metodo;
			$this->_argumentos = $url;
			//print_r($this->_argumentos);
			$func = $this->_metodo;
			if (method_exists($this, $func)) {
				call_user_func(array($this, $this->_metodo));
			}else{
				$this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
			}
		}
		$this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
	}
	
	private function getProvincia() {
		//echo "<br />";
		//print_r($this->_argumentos);
		$provincias = Provincia::all();
		//var_dump($provincias);
		$this->sendResponse(json_encode($provincias), 200);
		//echo $idUsuario . "<br/>";
// 		if (isset($this->datosPeticion['nombre'])) {
// 			$nombre = $this->datosPeticion['nombre'];
// 			$id = (int) $idUsuario;
// 			if (!empty($nombre) and $id > 0) {
// 				$query = $this->_conn->prepare("update usuario set nombre=:nombre WHERE id =:id");
// 				$query->bindValue(":nombre", $nombre);
// 				$query->bindValue(":id", $id);
// 				$query->execute();
// 				$filasActualizadas = $query->rowCount();
// 				if ($filasActualizadas == 1) {
// 					$resp = array('estado' => "correcto", "msg" => "nombre de usuario actualizado correctamente.");
// 					$this->mostrarRespuesta($this->convertirJson($resp), 200);
// 				} else {
// 					$this->mostrarRespuesta($this->convertirJson($this->devolverError(5)), 400);
// 				}
// 			}
// 		}
//		$this->mostrarRespuesta($this->convertirJson($this->devolverError(5)), 400);
	}
}
$api = new Api();
$api->receiveRequest();