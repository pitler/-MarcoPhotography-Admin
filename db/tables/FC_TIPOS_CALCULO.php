<?php
class FC_TIPOS_CALCULO
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_TIPO_CALCULO"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["DESC_CALCULO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");
		
		return $tableElements;		
	}
}
?>