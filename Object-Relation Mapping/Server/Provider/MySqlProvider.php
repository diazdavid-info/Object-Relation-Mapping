<?php
/**
 * @version 0.1
 * @author David Díaz
 * @date 2015-01-17
 * Clase que extiende de la clase abstracta "Provider" que provee de los métodos y atributos necesarios para la iteración entre el proyecto y la base de datos.
 *
 */

require_once ('Provider.php');

class MySqlProvider extends Provider{
	
	/**
	 * Método para conectar a la BDD.
	 * @see Provider::connect()
	 * @param String $host Servidor de la BDD.
	 * @param String $user Usuario de conexiín a la BDD.
	 * @param String $pass Contraseña de la conexión a la BDD.
	 * @param String $dbname Nombre de la BDD.
	 * @return Object Devuelve un objeto de la conexión.
	 */
	public function connect ($host, $user, $pass, $dbname){
		$this->resource = new mysqli($host, $user, $pass, $dbname);
		if($this->resource->connect_errno){
			error_log($this->resource->connect_error);
		}
		return $this->resource;
	}

	/**
	 * Método para desconectar de la BDD.
	 * @see Provider::diconnect()
	 * @return Boolean Devuelve true si la conexión se ha cerrado y false en caso contrario.
	 */
	public function disconnect (){
		$result = $this->resource->close();
		$this->resource = null;
		return $result;
	}

	/**
	 * Método que devuelve el código de error del último método llamado.
	 * @see Provider::getErrorNo()
	 * @return Integer Devuelve un entero con el codigo de error MySql.
	 */
	public function getErrorNo (){
		return $this->resource->errno;
	}

	/**
	 * Método que devuelve el error del último método llamado.
	 * @see Provider::getError()
	 * @return String Devuelve un String con el error MySql.
	 */
	public function getError (){
		return $this->resource->error;
	}

	/**
	 * Método que envía una query a la BDD.
	 * @see Provider::query()
	 * @param String $q Query a enviar.
	 * @return mysqli_result Devulve el resultado de la Query.
	 */
	public function query ($q){
		return $this->resource->query($q);
	}

	/**
	 * Método que devuelve el número de filas afectadas en una SELECT.
	 * @see Provider::numRows()
	 * @param mysqli_result $resource Resultado de una query.
	 * @return Integer Devuelve un número entero con las filas de la SELECT.
	 */
	public function numRows ($resource){
		return ($resource && is_object($resource)) ? $resource->num_rows : 0;
	}

	/**
	 * Método que va devolviendo una a una las filas de una SELECT en un array.
	 * @see Provider::fetchArray()
	 * @param mysqli_result $resource Resultado de la query.
	 * @return Array Devuelve una array con la primera fila del resultado.
	 */
	public function fetchArray ($resource){
		return $resource->fetch_assoc();
	}

	/**
	 * Metodo que comprueba la conexión con la BDD.
	 * @see Provider::isConnected()
	 * @return Devuelve true si esta conectado y false en caso contrario.
	 */
	public function isConnected (){
		return !is_null($this->resource);
	}

	/**
	 * Método que escapa los caracteres especiales para una query.
	 * @see Provider::escape()
	 * @param String $var Cadena que se quiere escapar.
	 * @return String Cadena ya escapada.
	 */
	public function escape ($var){
		return $this->resource->real_escape_string($var);
	}

	/**
	 * Método que devuelve el ID autogenerado de la tabla si tiene una columna autoincremental en un insert o update.
	 * @see Provider::getInsertedID()
	 * @return Integer Devuelve el ID de la fila.
	 */
	public function getInsertedID (){
		return $this->resource->insert_id;
	}

	/**
	 * Método para cambiar de BDD.
	 * @see Provider::changeDB()
	 * @param mysqli $database Objeto de la conexion.
	 * @return Boolean Devuelve true o false en función si el cambio fué bien.
	 */
	public function changeDB ($database){
		return $this->resource->select_db($database);
	}

	/**
	 * Método que configura la codificación.
	 * @see Provider::setCharset()
	 * @param String $charset Cadena con el nombre del charset.
	 * @return Boolean Devuelve true o false en función si el cambio fué bien.
	 */
	public function setCharset ($charset){
		return $this->resource->set_charset($charset);
	}
}