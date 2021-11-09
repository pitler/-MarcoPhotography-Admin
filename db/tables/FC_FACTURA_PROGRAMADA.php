<?php
class FC_FACTURA_PROGRAMADA
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_CLIENTE"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["CVE_TIPO_CALCULO"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["CVE_SERVICIO"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["PORCENTAJE"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["PERIODICIDAD"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["CVE_BASE_CALCULO"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>