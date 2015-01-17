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
	private $provider;
	private $params;
	private static $_con;
	
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
}