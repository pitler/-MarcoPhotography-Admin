<?php
class SYS_OPERADORAS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["NOMBRE"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"NO");		
		$tableElements["RAIZ"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["TIPO"] = array("key" =>0, "type" =>"int(2)",  "null" =>"NO");		
		$tableElements["VECTORES"] = array("key" =>0, "type" =>"text",  "null" =>"YES");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>