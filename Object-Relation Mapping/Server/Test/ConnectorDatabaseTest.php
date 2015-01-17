<?php
require_once '../Connector/ConnectorDatabase.php';

$connector = new ConnectorDatabase("MySqlProvider", "localhost", "root", "", "prueba", "utf8");

echo "<br />FIN<br />";