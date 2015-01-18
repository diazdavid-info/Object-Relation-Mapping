<?php
require_once '../Connector/ConnectorDatabase.php';

$connector = new ConnectorDatabase("MySqlProvider", "localhost", "root", "", "prueba", "utf8");
echo get_class($connector);

$connector1 = ConnectorDatabase::getConnection("MySqlProvider", "localhost", "root", "", "prueba", "utf8");
echo "TEST GETCONNECTION: " . get_class($connector1) . "<br />";

$result = $connector->executeScalar("SELECT * FROM provincias");

echo "<br />"; var_dump($result); echo "<br />";

$result = $connector->execute("select * from provincias where id = ?",null,array(1));

echo "<br />"; var_dump($result);

$result = $connector->execute("insert into provincias(nombre) values('benidor')",null,null);

echo "<br />"; var_dump($result); echo "<br />";

echo "TEST GETINSERTEDID: " . $connector->getInsertedID() . "<br />";

// $args = array(1,2,3,4,5,6,7,8,9);
// echo "TEST RENPLACEPARAMS: ";
// while ($connector->renplaceParams())

echo "<br />FIN<br />";