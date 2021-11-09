<?php
class SITE_COMPRAS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["ID_USUARIO"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["FECHA"] = array("key" =>0, "type" =>"datetime",  "null" =>"NO");		
		$tableElements["TOTAL"] = array("key" =>0, "type" =>"double",  "null" =>"NO");		
		$tableElements["DETALLE"] = array("key" =>0, "type" =>"text",  "null" =>"NO");		
		$tableElements["SITE_TOKEN"] = array("key" =>0, "type" =>"varchar(30)",  "null" =>"NO");		
		$tableElements["PAYMENT_ID"] = array("key" =>0, "type" =>"varchar(15)",  "null" =>"NO");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"varchar(15)",  "null" =>"NO");		
		$tableElements["TYPE"] = array("key" =>0, "type" =>"varchar(15)",  "null" =>"NO");		
		$tableElements["PREFERENCE_ID"] = array("key" =>0, "type" =>"varchar(50)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>