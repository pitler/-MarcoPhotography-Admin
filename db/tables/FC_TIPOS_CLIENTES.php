<?php
class FC_TIPOS_CLIENTES
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_TIPO_CLIENTE"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["DESC_TIPO_CLIENTE"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>