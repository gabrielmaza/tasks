<?php
require_once('datosconexion.php');


try
{
    $primeraSentencia = 'mysql:host = ' . HOST . ';dbname=' . DATABASE;
    $db = new PDO($primeraSentencia, USUARIO, CLAVE );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "No se pudo conectar a la Base de Datos";
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    $db = null;
}

?>