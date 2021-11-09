<?php
class FC_SYS_MENU
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["DESC_MENU"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["LOGO_MENU"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["ORDEN"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["LABEL"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>