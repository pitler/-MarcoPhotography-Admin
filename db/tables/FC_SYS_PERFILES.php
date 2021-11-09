<?php
class FC_SYS_PERFILES
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["CVE_PERFIL"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");		
		$tableElements["DESC_PERFIL"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"N");
		
		return $tableElements;		
	}
}
?>