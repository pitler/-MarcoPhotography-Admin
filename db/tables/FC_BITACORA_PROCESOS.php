<?php
class FC_BITACORA_PROCESOS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_USUARIO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["FECHA_BITACORA"] = array("key" =>0, "type" =>"DATE",  "null" =>"Y");		
		$tableElements["CVE_TIPO_EVENTO"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["DESC_BITACORA"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["TIPO_RESULTADO"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["FIRMA_ELECTRONICA"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["CVE_REFERENCIA"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["FECHA_REFERENCIA"] = array("key" =>0, "type" =>"DATE",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>