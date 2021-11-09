<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Connection as PwConnection;
use Pitweb\Sql as PwSql;

class BaseViewTemp 
{

	public $cvePerfil = "";
	public $connection;
	public $moduleName = "";
	public $controller = "";
	public $clase = "";
	public $listAction = "";
	public $formAction = "";
	public $firstAction = "";
	public $insertAction = "";
	public $updateAction = "";
	public $validateInsertAction = "";
	public $validateFields = "";
	public $deleteAction = "";
	public $showFormModal = "0";
	public $encrypt = 2;
	public $filter = "";
	public $tableProp =  array(				
		 	"add" => false,
			"update" => false,
			"delete" => false,	
			"filter" => false,
		);
	

	//Constructor de la clase
	function __construct()
	{


		$this->connection = PwConnection::getInstance()->connection;    

		$this->clase = PwSecurity::decryptVariable(1,PwFunciones::getGVariable("mod"));

		//$this->moduleName = $this->getModuleName();

		$this->cvePerfil = PwSecurity::decryptVariable ( 2, "cvePerfil" );		
		
		$this->controller = rawurlencode( PwSecurity::encryptVariable ( 1, "",  ucfirst($this->clase)."Controller") );
		
        $this->listAction = rawurlencode( PwSecurity::encryptVariable ( 1, "", "list" ) );
		$this->formAction = rawurlencode( PwSecurity::encryptVariable ( 1, "", "getForm" ) );
		$this->validateInsertAction = rawurlencode( PwSecurity::encryptVariable ( 1, "", "validateInsert" ) );
		$this->insertAction = rawurlencode( PwSecurity::encryptVariable ( 1, "", "doInsert" ) );
		$this->updateAction = rawurlencode( PwSecurity::encryptVariable ( 1, "", "doUpdate" ) );
		$this->deleteAction = rawurlencode( PwSecurity::encryptVariable ( 1, "", "doDelete" ) );

		//Validacion de permisos
		$this->permisos = PwSecurity::validateAccess($this->className, $this->cvePerfil, $this->connection);
		$this->tableProp["add"] = $this->permisos["INSERTAR"];



	}


	/*private function getModuleName()
	{

		$name = "";
		
		$fields = array("NOMBRE_CLASE");
		$condition = array("CLASE" => $this->clase);
		$sqlResult = PwSql::executeQuery($this->connection, "FC_SYS_MODULOS", $fields, $condition);

		if($sqlResult)
		{
			$sqlItem = $sqlResult[0];
			$name = $sqlItem["NOMBRE_CLASE"] ;
		}		
		return $name;
	}*/


	public  function getAddBtn()
	{
		$addBtn = "";

		if($this->tableProp["add"] == true)
		{
			$addBtn = $this->getTemplate("btnAdd");	
		}
        return $addBtn;
	}

	public  function getFilterBtn()
	{
		$filterBtn = "";

		if($this->tableProp["filter"] == true)
		{
			$filterBtn = $this->getTemplate("filterBtn");	
		}
        return $filterBtn;
	}


	public function getDefaultModalVars()
	{

		$data = file_get_contents('template/core/defaultModalVars.html', true);    

        $data = preg_replace("/__DETAILLIST__/", rawurlencode( PwSecurity::encryptVariable ( 1, "",  "detailList")), $data);   
        $data = preg_replace("/__DETAILSAVE__/", rawurlencode( PwSecurity::encryptVariable ( 1, "",  "detailSave")), $data);   
        $data = preg_replace("/__DETAILDELETE__/", rawurlencode( PwSecurity::encryptVariable ( 1, "",  "detailDelete")), $data);   
		$data = preg_replace("/__DETAILUPDATE__/",rawurlencode( PwSecurity::encryptVariable ( 1, "",  "detailUpdate")), $data); 
		

		return $data;



	}
	

	public function getImageUploadVars($name)
	{
		$data = file_get_contents('template/core/fileImagesVars.html', true);    
		
		$data = preg_replace("/__IMAGELIST__/",    rawurlencode( PwSecurity::encryptVariable ( 1, "",  "imageList")), $data);   
		$data = preg_replace("/__IMAGESAVE__/",    rawurlencode( PwSecurity::encryptVariable ( 1, "",  "imageSave")), $data);   
		$data = preg_replace("/__IMAGEDELETE__/",  rawurlencode( PwSecurity::encryptVariable ( 1, "",  "imageDelete")), $data);   
		$data = preg_replace("/__IMAGEORDER__/",  rawurlencode( PwSecurity::encryptVariable ( 1, "",  "imageOrder")), $data);   
		$data = preg_replace("/__IMAGEPATH__/",    rawurlencode( PwSecurity::encryptVariable ( 1, "",  "imagenes/$name/") ), $data); 		

		return $data;
	}

	/**
	*	Trae el tipo de modal a usar en la pantalla
	*	1.- Normal sin nada raro
	*	2.- Para imagenes y archivos
	*
	*/
	public function getDetailModal($btnSave = true, $type = 1, $custom = false )
	{

		switch($type)
		{
			case 1 : 
			$file = "detailModal";
			break;
			case 2 : 
				$file = "detailFileModal";
				break;

			//Si queremos mandar otro que no sea el de default	
			case 10 : 
				$file = $custom;
				break;


			default : 
			$file = "detailModal";
			break;
		}

		$data = file_get_contents('template/core/'.$file.'.html', true);    
		$btnSaveTemp = $this->getTemplate("modalSaveBtn");
		if($btnSave == false)
		{
			$btnSaveTemp = "";
		}
		$data = preg_replace("/__BTNSAVE__/", $btnSaveTemp, $data);

		return $data;

	}





 	private function getTemplate($name)
  	{      	
  

	$template["btnAdd"] = <<< TEMP
	<button class="btn btn-info" style="margin-right: 5px;" name = "btnAdd"  id = "btnAdd"><i class="fa fa-plus"> </i>&ensp;  Nueva entrada</button>
TEMP;

$template["filterBtn"] = <<< TEMP
	<button class="btn btn-info" style="margin-right: 5px;" name = "filterBtn"  id = "filterBtn"><i class="fa fa-bars"> </i>  Filtros</button>
TEMP;

$template["filterBtn"] = <<< TEMP
	<button class="btn btn-info" style="margin-right: 5px;" name = "filterBtn"  id = "filterBtn"><i class="fa fa-bars"> </i>  Filtros</button>
TEMP;


$template["modalSaveBtn"] = <<< TEMP
<button class="btn btn-info" style="margin-right: 5px;" id="btnDetailSave" name="btnDetailSave"><i class="fa  fa fa-save"> </i> Guardar</button>
TEMP;

	return $template[$name];

  	} 
}
?>