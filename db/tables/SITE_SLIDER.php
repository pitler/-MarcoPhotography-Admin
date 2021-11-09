<?php
class SITE_SLIDER
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["TITULO"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["ENCABEZADO"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["TITULO_EN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["ENCABEZADO_EN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO_EN"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["CATEGORIA"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>