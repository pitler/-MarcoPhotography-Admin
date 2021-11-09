<?php
class FC_BASES_CALCULO
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_BASE_CALCULO"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["DESC_BASE_CALCULO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");
		
		return $tableElements;		
	}
}
?>