<?php

/**
 * COVACKWEB 1.0
 * Mayo 2012
 * AUTOR : PITLER
 * CLASE: index.php
 * -------------------------------------------------------------------------------------------------
 * Clase para recuperar contraseña
 * Lo que hace es:
 * Verifica que tenga un código válido
 * Verifica las politicas de password
 * Cambia el password 
 */

$tiempoInicio =  microtime(true);
include_once 'src/lib/recover.php';

$pageData = null;
$index = new recover();
$pageData = $index->getPageData();

echo $pageData;


?>