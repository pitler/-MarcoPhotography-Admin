<?php
class SITE_MENU
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["CLASE"] = array("key" =>0, "type" =>"varchar(50)",  "null" =>"NO");		
		$tableElements["NOMBRE"] = array("key" =>0, "type" =>"varchar(50)",  "null" =>"NO");		
		$tableElements["NOMBRE_EN"] = array("key" =>0, "type" =>"varchar(50)",  "null" =>"YES");		
		$tableElements["PADRE"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["POSICION"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["NO_LINK"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["REQUIERE_LOGIN"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["APARECE_MENU"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["ES_SECCION"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["ORDEN"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>