<?php
class FC_CAT_RANGOS_CUOTA_FIJA
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["DESCRIPCION"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["CLIENTES_VALUACION"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["CLIENTES_CONTABILIDAD"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>