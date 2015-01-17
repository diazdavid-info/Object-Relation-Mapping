<?php
require_once '../Provider/MySqlProvider.php';

$connect = new MySqlProvider();

echo "TEST CONNECT: " . $connect->connect("localhost", "root", "", "prueba") instanceof mysqli . "<br />";

echo "TEST ISCONNECT: " . $connect->isConnected() . "<br />";

echo "TEST QUERY: "; var_dump($connect->query("select * from provincias where id = 1")); echo "<br />";

echo "TEST QUERY: "; var_dump($connect->query("select * from provincias2 where id = 1")); echo "<br />";

echo "TEST GETERRORNO: " . $connect->getErrorNo() . "<br />";

echo "TEST GETERROR: " . $connect->getError() . "<br />";

$query = $connect->query("select * from provincias");
echo "TEST NUMROWS: " . $connect->numRows($query) . "<br />";

echo "TEST FETCHARRAY: "; print_r($connect->fetchArray($query)); echo "<br />";
echo "TEST FETCHARRAY: "; print_r($connect->fetchArray($query)); echo "<br />";

echo "TEST ESCAPE: " . $connect->escape("'s Hertogenbosch") . "<br />";

echo "TEST GETINSERTID: " . $connect->getInsertedID() . "<br />";
echo "TEST QUERY: "; var_dump($connect->query("insert into provincias(nombre) values('Murcia')")); echo "<br />";
echo "TEST GETINSERTID: " . $connect->getInsertedID() . "<br />";

echo "TEST SETCHARSET: " . $connect->setCharset("UTF8") . "<br />";

echo "TEST DISCONNECT: " . $connect->diconnect() . "<br />";

echo "TEST ISCONNECT: " . $connect->isConnected() . "<br />";

echo "<br />FIN<br />";