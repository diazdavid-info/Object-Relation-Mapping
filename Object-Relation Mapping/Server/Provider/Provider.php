<?php
/**
 * @version 0.1
 * @author David Díaz
 * @date 2015-01-17
 * Clase abstracta que provee de los métodos y atributos necesarios para la iteración entre el proyecto y la base de datos.
 *
 */
abstract class Provider{
	// Atributo con la conexión
	protected $resource;
	
	// Método para conectar a la BDD
	public abstract function connect ($host, $user, $pass, $dbname);
	
	// Método para desconectar de la BDD
	public abstract function disconnect ();
	
	// Método que devuelve el código de error
	public abstract function getErrorNo ();
	
	// Método que devuelve el error
	public abstract function getError ();
	
	// Método que envía una query
	public abstract function query ($q);
	
	// Método que devuelve el número de filas afectadas
	public abstract function numRows ($resource);
	
	// Método que devuelve el resultado en una array
	public abstract function fetchArray ($resource);
	
	// Metodo que comprueba la conexión
	public abstract function isConnected ();
	
	// Método que escapa los caracteres especiales para una query
	public abstract function escape ($var);
	
	// Método que devuelve 
	public abstract function getInsertedID ();
	
	// Método para cambiar de BDD
	public abstract function changeDB ($database);
	
	// Método que configura la codificación
	public abstract function setCharset ($charset);
}