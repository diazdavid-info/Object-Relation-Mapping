<?php
require_once '../Classes/Provincia.php';

$provincias = Provincia::all();
echo "<br />";
var_dump($provincias);
echo "<br />FIN<br />";