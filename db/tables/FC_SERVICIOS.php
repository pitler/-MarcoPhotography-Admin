<?php
class FC_SERVICIOS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["DESC_SERVICIO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");		
		$tableElements["RANGO"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["TIPO"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["CVE_SERVICIO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>