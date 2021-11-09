<?php
class SITE_PERFILES
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["ID"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["TIPO"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["CVE_PERFIL"] = array("key" =>0, "type" =>"varchar(45)",  "null" =>"NO");		
		$tableElements["DESC_PERFIL"] = array("key" =>0, "type" =>"varchar(45)",  "null" =>"NO");		
		$tableElements["OPERADORA_DEFAULT"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>