<?php
class FC_SYS_DETALLE_PERFIL
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["CVE_PERFIL"] = array("key" =>0, "type" =>"varchar(20)",  "null" =>"NO");		
		$tableElements["CLASE"] = array("key" =>0, "type" =>"varchar(80)",  "null" =>"NO");		
		$tableElements["CONTROLADOR"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["VISUALIZAR"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["INSERTAR"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["ACTUALIZAR"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["BORRAR"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>