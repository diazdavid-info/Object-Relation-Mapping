<?php
/**
 * @version 0.1
 * @author David Díaz
 * @date 2015-01-17
 * Clase que traduce las consultas de la BDD en objetos y viceversa.
 *
 */

class ORM{
	// Atributo con una instancia de la conexxión.
	private static $database;
	
	// 
	protected static $table;
	
	function __construct(){
		self::getConnection();
	}
	
	/**
	 * Método que instancia una conexión a la BDD.
	 */
	private static function getConnection(){
		require_once ('../Connector/ConnectorDatabase.php');
		require_once ('ConfigurationDatabase.php');
		$provider = ConfigurationDatabase::getDbProvider();
		$host = ConfigurationDatabase::getDbHost();
		$user = ConfigurationDatabase::getDbUser();
		$pass = ConfigurationDatabase::getDbPassword();
		$db = ConfigurationDatabase::getDbDb();
		$charset = ConfigurationDatabase::getDbCharset();
		self::$database = ConnectorDatabase::getConnection($provider, $host, $user, $pass, $db, $charset);
	}
	
	public static function find($id){
		$results = self::where('id',$id);
		return $results[0];
	}
	
	public static function where($field, $value){
		$obj = null;
		self::getConnection();
		$query = "SELECT * FROM " . static::$table . " WHERE " . $field . " = ?";
		$results = self::$database->execute($query, null, array($value));
		if($results){
			$class = get_called_class();
			for ($i = 0; $i < sizeof($results); $i++){
				$obj[] = new $class($results[$i]);
			}
		}
		return $obj;
	}
}