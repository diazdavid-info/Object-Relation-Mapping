<?php
/**
 * @version 0.1
 * @author David Díaz
 * @date 2015-01-17
 * Clase que se encarga de interactuar directamente con el provider que le indiquemos.
 *
 */
include_once ('../Provider/LinkProvider.php');

class ConnectorDatabase{
	// Atributo con la instancia del proveedor.
	private $provider;
	
	private $params;
	
	private static $_con;
	
	/**
	 * Constructor que instancia un objeto del proveedor indicado.
	 * @param Object $provider Proveedor que el trabajar.
	 * @param String $host Servidor de la BDD.
	 * @param String $user Usuario de la BDD.
	 * @param String $pass Contraseña de la BDD.
	 * @param String $db Nombre de la BDD.
	 * @param String $charset Charset con la que trabajaremos.
	 * @throws Exception Puede levantar una excepción si el proveedor no existe, si no se puede conectar a la BDD o si el charset no es válido.
	 */
	public function __construct($provider, $host, $user, $pass, $db, $charset){
		if(!class_exists($provider)){
			throw new Exception("El proveedor no existe o no fue implementado");
		}
		
		$this->provider = new $provider;
		$this->provider->connect($host, $user, $pass, $db);
		
		if(!$this->provider->isConnected()){
			throw new Exception("No se puede conectar a la base de datos >> {$db}");
		}
		
		$this->provider->setCharset($charset);
		
		if($this->provider->getErrorNo()){
			throw new Exception("No se puede cambiar el chatset: " . $this->provider->getError());
		}
		
	}
	
	/**
	 * Método estático que se encarga de obtener una conexión a la BDD.
	 * @param Object $provider Proveedor que el trabajar.
	 * @param String $host Servidor de la BDD.
	 * @param String $user Usuario de la BDD.
	 * @param String $pass Contraseña de la BDD.
	 * @param String $db Nombre de la BDD.
	 * @param String $charset Charset con la que trabajaremos.
	 * @return ConnectorDatabase Retorna un objeto de esta clase.
	 */
	public static function getConnection($provider, $host, $user, $pass, $db, $charset){
		if(self::$_con){
			$connection = self::$_con;
		}else{
			$class = __CLASS__;
			self::$_con = new $class($provider, $host, $user, $pass, $db, $charset);
			$connection = self::$_con;
		}
		return $connection;
	}
	
	/**POSIBLE CAMBIO DE UBICACIÓN.
	 * Método que en cada llamada devuelve la posición de la array en la que esta el punto. En cada llamada mueve el puntero una posición.
	 * @return Mixed Devuelve el valor de la posición del puntero o falso si no hay más elementos.
	 */
	private function replaceParams(){
		$b = current($this->params);
		next($this->params);
		return $b;
	}
	
	/**
	 * Método que prepara la query.
	 * @param String $sql Query que se va a preparar.
	 * @param Array $params Clasula WHERE que se quiere introducir en la query.
	 * @return String Devuelve la query preparada para enviarla.
	 */
	private function prepare($sql, $params){
		if($params){
			foreach ($params as $key => $value){
				if(is_bool($value)){
					$value = $value ? 1 : 0;
				}elseif (is_double($value)){
					$value = str_replace(",", ".", $value);
				}elseif (is_numeric($value)){
					if(is_string($value)){
						$value = "'" . $this->provider->escape($value) . "'";
					}else{
						$value = $this->provider->escape($value);
					}
				}elseif (is_null($value)){
					$value = null;
				}else{
					$value = "'" . $this->provider->escape($value);
				}
				$escaped[] = $value;
			}
		}
		$this->params = $escaped;
		$q = preg_replace_callback("/(\?)/i", array($this, "replaceParams"), $sql);
		
		return $q;
	}
	
	/**
	 * Método que envía la query a la BDD.
	 * @param String $q Query a enviar.
	 * @param Array $params Parametros a introducir en la clausula WHERE.
	 * @throws Exception Lanza una excepción en el caso de que la query de error.
	 * @return mysqli_result Devuelve lo que devuelva la query.
	 */
	private function sendQuery($q, $params){
		$query = (is_null($params)) ? $q : $this->prepare($q, $params);
		$result = $this->provider->query($query);
		
		if($this->provider->getErrorNo()){
			error_log($this->provider->getError());
			throw new Exception($this->provider->getError());
		}
		
		return $result;
	}
	
	/**
	 * Método que prepara el resultado de la query y devuelve solo la primera fila.
	 * @param String $q Query a enviar.
	 * @param Array $params Parametros a introducir en la clausula WHERE.
	 * @return Devuelve un array en el caso de la SELECT y boolean en los otros casos.
	 */
	public function executeScalar($q, $params = null){
		$result = $this->sendQuery($q, $params);
		$response = null;
		if(!is_null($result)){
			if(!is_object($result)){
				$response = $result;
			}else{
				$row = $this->provider->fetchArray($result);
				//$response = $row[0]; //REVISAR
				$response = $row;
			}
		}
		return $response;
	}
	
	/**
	 * Método que prepara el resultado de la query y devuelve un array con todas las filas.
	 * @param String $q Query a enviar.
	 * @param String $array_index
	 * @param Array $params Parametros a introducir en la clausula WHERE.
	 * @return Devuelve un array en el caso de la SELECT y boolean en los otros casos.
	 */
	public function execute($q, $array_index = null, $params = null){
		$result = $this->sendQuery($q, $params);
		$response = ($this->provider->getErrorNo()) ? false : true;
		if((is_object($result) || $this->provider->numRows($result) || $result) && ($result !== true && $result !== false)){
			while($row = $this->provider->fetchArray($result)){
				if($array_index){
					$arr[$row[$array_index]] = row;
				}else{
					$arr[] = $row;
				}
			}
			$response = $arr;
		}
		return $response;
	}
	
	/**
	 * Método para cambiar de BDD.
	 * @param mysqli $database Objeto de la conexion.
	 * @return Boolean Devuelve true o false en función si el cambio fué bien.
	 */
	public function changeDB($database){
		return $this->provider->changeDB($database);
	}
	
	/**
	 * Método que devuelve el ID autogenerado de la tabla si tiene una columna autoincremental en un insert o update.
	 * @return Integer Devuelve el ID de la fila.
	 */
	public function getInsertedID(){
		return $this->provider->getInsertedID();
	}
	
	/**
	 * Método que devuelve el error del último método llamado.
	 * @return String Devuelve un String con el error MySql.
	 */
	public function getError(){
		return $this->provider->getError();
	}
	
	/**
	 * Método que destruye la conexión a la BDD.
	 */
	public function __destruct(){
		$this->provider->disconnect();
		$this->provider = null;
	}
}