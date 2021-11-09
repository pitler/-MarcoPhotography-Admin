<?php
class SITE_PARAMS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["NOMBRE"] = array("key" =>0, "type" =>"varchar(100)",  "null" =>"NO");		
		$tableElements["TITULO"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["TITULO2"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO2"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["TITULO3"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO3"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["TITULO_EN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO_EN"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["TITULO2_EN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO2_EN"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["TITULO3_EN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["TEXTO3_EN"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>