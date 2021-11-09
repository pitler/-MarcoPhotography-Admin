<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Connection as PwConnection;

class BaseTemp 
{

	public  $cvePerfil = "";
	public $connection;
	public $moduleName = "";

	//Constructor de la clase
	function __construct()
	{

		$this->cvePerfil = PwSecurity::decryptVariable ( 2, "cvePerfil" );
		$this->connection = PwConnection::getInstance()->connection;    
		$this->moduleName = $this->getModuleName();
		
	}


	private function getModuleName()
	{

		$name = PwSecurity::decryptVariable(1,PwFunciones::getGVariable("mod"));
		return $name;
	}
}