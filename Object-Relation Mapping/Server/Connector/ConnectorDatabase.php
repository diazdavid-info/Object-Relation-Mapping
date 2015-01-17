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
	private function renplaceParams(){
		$b = current($this->params);
		next($this->params);
		return $b;
	}
	
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
}





























