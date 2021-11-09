<?php
class FC_CLIENTES
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_CLIENTE"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["CVE_COVAF"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["NIVEL_COVAF"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["RAZON_SOCIAL"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["RFC"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["CALLE"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["COLONIA"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["DELEGACION"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["CIUDAD"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["ESTADO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["CP"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["PIZARRA"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["CVE_TIPO_CLIENTE"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["ACTUALIZACION_AUT"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["FACTURACION_ALTERNA"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["CVE_CLIENTE_ALTERNO"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>