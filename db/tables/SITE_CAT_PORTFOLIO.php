<?php
class SITE_CAT_PORTFOLIO
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["NOMBRE"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"NO");		
		$tableElements["NOMBRE_EN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["ORDEN"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>