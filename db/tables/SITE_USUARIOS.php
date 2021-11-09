<?php
class SITE_USUARIOS
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["CVE_USUARIO"] = array("key" =>1, "type" =>"varchar(20)",  "null" =>"NO");		
		$tableElements["NOM_USUARIO"] = array("key" =>0, "type" =>"varchar(100)",  "null" =>"NO");		
		$tableElements["CVE_PERFIL"] = array("key" =>0, "type" =>"varchar(50)",  "null" =>"NO");		
		$tableElements["LLAVE"] = array("key" =>0, "type" =>"varchar(100)",  "null" =>"NO");		
		$tableElements["CORREO"] = array("key" =>0, "type" =>"varchar(100)",  "null" =>"NO");		
		$tableElements["STATUS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["LAST_LOGIN"] = array("key" =>0, "type" =>"datetime",  "null" =>"YES");		
		$tableElements["CODE"] = array("key" =>0, "type" =>"varchar(50)",  "null" =>"YES");		
		$tableElements["LANG"] = array("key" =>0, "type" =>"varchar(3)",  "null" =>"NO");		
		$tableElements["IMAGEN"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"YES");		
		$tableElements["FECHA_APLICACION"] = array("key" =>0, "type" =>"date",  "null" =>"YES");		
		$tableElements["FECHA_EXPIRACION"] = array("key" =>0, "type" =>"date",  "null" =>"YES");		
		$tableElements["VIGENCIA"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["FECHA_CAMBIO"] = array("key" =>0, "type" =>"date",  "null" =>"YES");		
		$tableElements["INACTIVIDAD"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["INTENTOS"] = array("key" =>0, "type" =>"int(11)",  "null" =>"YES");		
		$tableElements["LAST_ACTIVITY"] = array("key" =>0, "type" =>"datetime",  "null" =>"YES");
		
		return $tableElements;		
	}
}
?>