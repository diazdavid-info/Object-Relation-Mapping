<?php
/**
 * @version 0.1
 * @author David Díaz
 * @date 2015-01-17
 * Clase con atributos estaticos con la configuración de la BDD.
 *
 */

class ConfigurationDatabase{
	const DB_PROVIDER = "MySqlProvider";
	const DB_HOST = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_DB = "prueba";
	const DB_CHARSET = "utf8";
	
	public static function getDbProvider(){
		return self::DB_PROVIDER;
	}
	
	public static function getDbHost(){
		return self::DB_HOST;
	}
	
	public static function getDbUser(){
		return self::DB_USER;
	}
	
	public static function getDbPassword(){
		return self::DB_PASSWORD;
	}
	
	public static function getDbDb(){
		return self::DB_DB;
	}
	
	public static function getDbCharset(){
		return self::DB_CHARSET;
	}
}