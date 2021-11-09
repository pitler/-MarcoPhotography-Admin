<?php
class FC_FACTURAS_PREVIAS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_FACTURA"] = array("key" =>1, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["CVE_CLIENTE"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["FECHA_FACTURA"] = array("key" =>0, "type" =>"DATE",  "null" =>"N");		
		$tableElements["FECHA_FACTURA_T"] = array("key" =>0, "type" =>"DATE",  "null" =>"N");		
		$tableElements["NOMBRE_EMISOR"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["NOMBRE_RECEPTOR"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["MONTO_TOTAL"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["CFD"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["FORMA_PAGO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["METODO_PAGO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["UUID"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["RFC"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["FECHA_ASOCIACION"] = array("key" =>0, "type" =>"DATE",  "null" =>"N");		
		$tableElements["PIZARRA"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["RFC_RECEPTOR"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["TIPO_CAMBIO"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["CVE_MONEDA"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["MONTO_TOTAL_ORIGEN"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");		
		$tableElements["FOLIO_GENERACION"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>