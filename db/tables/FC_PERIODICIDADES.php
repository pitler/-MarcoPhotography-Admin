<?php
class FC_PERIODICIDADES
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_PERIODICIDAD"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["DESC_PERIODICIDAD"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");
		
		return $tableElements;		
	}
}
?>