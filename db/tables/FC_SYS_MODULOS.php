<?php
class FC_SYS_MODULOS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["CLASE"] = array("key" =>0, "type" =>"varchar(80)",  "null" =>"NO");		
		$tableElements["NOMBRE_CLASE"] = array("key" =>0, "type" =>"varchar(80)",  "null" =>"NO");		
		$tableElements["DESC_CLASE"] = array("key" =>0, "type" =>"varchar(100)",  "null" =>"YES");		
		$tableElements["CVE_MENU"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["PADRE"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["ORDEN"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["ICON"] = array("key" =>0, "type" =>"varchar(50)",  "null" =>"YES");		
		$tableElements["CONTROLADOR"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");
		
		return $tableElements;		
	}
}
?>