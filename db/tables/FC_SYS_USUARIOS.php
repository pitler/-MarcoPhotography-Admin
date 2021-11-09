<?php
class FC_SYS_USUARIOS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_USUARIO"] = array("key" =>1, "type" =>"VARCHAR2",  "null" =>"N");		
		$tableElements["NOM_USUARIO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");		
		$tableElements["CVE_PERFIL"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");		
		$tableElements["LLAVE"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");		
		$tableElements["CORREO"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["LAST_LOGIN"] = array("key" =>0, "type" =>"DATE",  "null" =>"Y");		
		$tableElements["LAST_ACTIVITY"] = array("key" =>0, "type" =>"DATE",  "null" =>"Y");		
		$tableElements["CODE"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"Y");		
		$tableElements["LANG"] = array("key" =>0, "type" =>"VARCHAR2",  "null" =>"N");		
		$tableElements["FECHA_APLICACION"] = array("key" =>0, "type" =>"DATE",  "null" =>"Y");		
		$tableElements["FECHA_EXPIRACION"] = array("key" =>0, "type" =>"DATE",  "null" =>"Y");		
		$tableElements["VIGENCIA"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["FECHA_CAMBIO"] = array("key" =>0, "type" =>"DATE",  "null" =>"Y");		
		$tableElements["INACTIVIDAD"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"N");		
		$tableElements["INTENTOS"] = array("key" =>0, "type" =>"NUMBER",  "null" =>"Y");
		
		return $tableElements;		
	}
}
?>