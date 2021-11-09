<?php
class FC_SYS_LIB
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["NOMBRE"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["SITIO"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["CSS_GLOBAL"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["CSS_IMPLEMENTING"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["CSS_CUSTOM"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["JS_GLOBAL"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["JS_IMPLEMENTING"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["JS_CUSTOM"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["INIT"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["ORDEN"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>