<?php
class FC_CAT_TIPOS_CALCULO_VAL
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["TIPO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["DESCRIPCION"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["CLIENTES_PASIVOS"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["CLIENTES_ACTIVOS"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["CUOTA_MINIMA"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>