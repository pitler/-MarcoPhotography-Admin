<?php
class SITE_CONTENT
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["NOMBRE"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["SECCION"] = array("key" =>0, "type" =>"varchar(100)",  "null" =>"NO");		
		$tableElements["ID_CLASE"] = array("key" =>0, "type" =>"int(3)",  "null" =>"NO");		
		$tableElements["TITULO"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["EDITOR"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["TITULO_EN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO_EN"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["EDITOR_EN"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["ORDEN"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>