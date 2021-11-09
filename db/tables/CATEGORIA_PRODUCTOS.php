<?php
class CATEGORIA_PRODUCTOS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["NOMBRE"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["NOMBRE_EN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");
		
		return $tableElements;		
	}
}
?>