<?php
class FC_CAT_TIPOS_RANGO
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["DESCRIPCION"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");
		
		return $tableElements;		
	}
}
?>