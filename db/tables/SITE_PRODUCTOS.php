<?php
class SITE_PRODUCTOS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["NOMBRE"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"NO");		
		$tableElements["NOMBRE_EN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"NO");		
		$tableElements["DESCRIPCION"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["DESCRIPCION_EN"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["CATEGORIA"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["MEDIDAS"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["PRECIO"] = array("key" =>0, "type" =>"decimal(11,2)",  "null" =>"NO");		
		$tableElements["CANTIDAD"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["TIENE_INVENTARIO"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>