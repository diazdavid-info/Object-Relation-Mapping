<?php
require_once '../Connector/ConnectorDatabase.php';

$connector = new ConnectorDatabase("MySqlProvider", "localhost", "root", "", "prueba", "utf8");

$connector = ConnectorDatabase::getConnection("MySqlProvider", "localhost", "root", "", "prueba", "utf8");
echo "TEST GETCONNECTION: " . get_class($connector) . "<br />";

// $args = array(1,2,3,4,5,6,7,8,9);
// echo "TEST RENPLACEPARAMS: ";
// while ($connector->renplaceParams())

echo "<br />FIN<br />";