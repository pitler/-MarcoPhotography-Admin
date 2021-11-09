<?php
class FC_CUOTAS_CLIENTES
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_CLIENTE"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["CVE_RANGO"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["CVE_SERVICIO"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");
		
		return $tableElements;		
	}
}
?>