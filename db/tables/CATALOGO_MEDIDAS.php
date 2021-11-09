<?php
class CATALOGO_MEDIDAS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["DESCRIPCION"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>