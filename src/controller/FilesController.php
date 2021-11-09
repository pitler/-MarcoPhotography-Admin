<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Connection as PwConnection;
use Pitweb\Sql as PwSql;


//use Pitweb\Sql as PwSql;
//use Pitweb\Form as PwForm;
use Pitweb\Files as PwFiles;


class FilesController //extends CrudController
{

   public $className = "";
   public $connection = "";
   public $cvePerfil = "";

  //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "FilesController";        
      $this->connection = PwConnection::getInstance()->connection;    
	  //$this->cvePerfil = PwSecurity::decryptVariable ( 2, "cvePerfil" );		
      //parent::__construct();
  }


	public function getData()
	{
	
		$data = "";

		$encrypt  = PwFunciones::getPVariable("encrypt");

   	    $mode = rawurldecode (PwFunciones::getPVariable("mode"));
        if($encrypt == 2)
        {                       	
           	$mode = PwSecurity::decryptVariable(1, $mode);           
        }

	//error_log("Files controller :: $mode");
        switch ($mode)
        {

			//Acciones del modal par aimagenes
			case "imageList" :
				$data = self::getImageList();
			break;

			case "imageSave" :
			$data = self::saveImage();
			break;	

			case "imageDelete" :
			$data = self::deleteImage();
			break;	

			case "imageOrder" :
				$data = self::orderImages();
				break;	

        }
      
		return $data;	
	}



	
   /**
   * Función que se encarga de preparar los datos para traer las imagenes contenidas en una carpeta   
   * Llama a una funcion generica dentro de Files para hacer el trabajo
   */
   private function getImageList($imagePath = null, $id = null)	
   {
	
	
	$modTitle = "";
	   //Si no tiene el path, busca en los parametros para armarlo
	   if($imagePath == null)
	   {
			$keyParams = rawurldecode(PwFunciones::getPVariable("keyParams"));    
	   		//Dejo en su forma original
	   		$keyParamsAux = $keyParams;
	   		//Convertimos el json en array
	   		$keyParams = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$keyParams)));
	   
	   		//Traemos el path de las imagenes
			$imagePath =  PwSecurity::decryptVariable(1,rawurldecode(PwFunciones::getPVariable("filePath")));
			   

	   
	   		//Si no existe path o es inválido
	   		if(!$imagePath || $imagePath == "null")
	   		{
					return  json_encode(array("status" => "false", "value" => "Ruta inválida para cargar las imágenes"));
	   		}	   
	   
	   		$id = $keyParams["ID"];
	   		$imagePath = $imagePath.$id."/";
	   }

	   $modTitle = "";
	   $copyBtn = "";
	   //Traemos los parametros
	   $params = rawurldecode(PwFunciones::getPVariable("params"));    
	   $params = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$params)));
	   if($params)
	   {
			$modTitle = isset($params["modTitle"]) ? $params["modTitle"] :"";

			$copyBtn = isset($params["copyBtn"]) && $params["copyBtn"] == "true" ? $params["copyBtn"] :null;
	   }
  

	   //Traemos la lista
	   if(isset($params["sortedList"]) && $params["sortedList"] == "true")
	   {
			$images = PwFiles::getImageListSorted($imagePath);
	   }
	   else
	   {
		   $images = PwFiles::getImageList($imagePath, $copyBtn);
	   }
	   
     
       //Pintamos el contenido y lo regresamos
	   $data = file_get_contents('template/core/imagePanel.html', true);
	   $data = preg_replace("/__ID__/", $id, $data);	
	   $data = preg_replace("/__CONTENT__/", $images, $data);	
	   $data = preg_replace("/__MODULO__ /", $modTitle, $data);	

	   $result = json_encode(array("status" => "true", "content" =>$data));

	   return $result;
	  
   }
		
		
	/**	
	* Función que salva una imagen en el servidor en la ruta predefinida	
	*/
	private function saveImage()
	{
	
		$keyParams = rawurldecode(PwFunciones::getPVariable("keyParams"));    
	   	//Dejo en su forma original
	   	$keyParamsAux = $keyParams;
	   	//Convertimos el json en array
	   	if(isset($keyParams) && $keyParams != "")
	   	{
			$keyParams = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$keyParams)));
	   	}

		
		//Path de la imagen    
	    $id = $keyParams["ID"];
		//$imagePath =  PWSREPOSITORY.PwSecurity::decryptVariable(1,rawurldecode(PwFunciones::getPVariable("filePath"))).$id;
		$imagePath =  "repository/".PwSecurity::decryptVariable(1,rawurldecode(PwFunciones::getPVariable("filePath"))).$id;

	    $result = PwFiles::uploadFile($imagePath);


		//Traemos los parametros
		$params = rawurldecode(PwFunciones::getPVariable("params"));    
		$params = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$params)));

		//Para ordenar imagenes, si orderImages == true
		//Traemos el consecutivo
		//Guardamos en la base
		if(isset($params["sortedList"]) && $params["sortedList"] == "true")
		{
			$imagePath = $imagePath."/";
			//Funcion para eliminar acentos y espacios
			$fileName = PwFunciones::eliminaAcentos($_FILES ["archivos"] ["name"]);
			$consecutivo = PwFunciones::getImageConsecutivo($this->connection, "SITE_IMAGES", "SORT", $imagePath, $id);
			$params = array(0, $fileName, $imagePath, $consecutivo, $id);
	  
			$fields = "ID, NOMBRE, RUTA, SORT, ID_SECCION";
			$datos = "?,?,?,?,?";
			PwSql::insertData($this->connection, "SITE_IMAGES", $fields, $datos, $params);

		}




	   return 	$result = json_encode(array("status" => "update", "value" =>"Datos guardados" , "content" => ""));
		

	}

    /**
    * Función que elimina un archivo dado
    */

	private function deleteImage()
	{
		
		
		//Traemos el path ormal como se envia
		$path =  PwSecurity::decryptVariable(1,rawurldecode(PwFunciones::getPVariable("filePath")));

		//Traemos el path que debe venir encriptado
		$imagePath =  PWSREPOSITORY.$path;


		$params = rawurldecode(PwFunciones::getPVariable("params"));    
		$params = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$params)));

		//Si es una lista acomodada, borramos de la tabla
		if(isset($params["sortedList"]) && $params["sortedList"] == "true")
		{
			$imageName = basename($path);
			$sectionId = explode("/",$path);
			$sectionId = $sectionId[2];
		
			$condition = array("NOMBRE" => $imageName, "ID_SECCION" => $sectionId);
			PwSql::deleteData($this->connection, "SITE_IMAGES", $condition);
		

		}

		
        //Elimina el archivo
		PwFiles::deleteFile($imagePath);
		


		//Para borrar los thumbs cuando se cambia la imagen principal
		$thumbsPath = pathinfo($path);
		$thumbsPath = PWSREPOSITORY.$thumbsPath['dirname']."/thumbs/";

		if(is_dir($thumbsPath))
		{			
			PwFiles::deleteFolder($thumbsPath);
		}


        
        //Saca del path la información del directorio
		$folderPath = pathinfo($imagePath);
		$folderPath = $folderPath['dirname'];		
        
        //Quita la ruta fisica del directorio
        $folderPath  = str_replace(PWSREPOSITORY, "", $folderPath)."/";
        //Saca el id del directorio
		$id = basename($folderPath);
		
		
		return self::getImageList($folderPath, $id);
	}

	/**
	* Función que ordena las imagenes de una galería
	* Manda solo el id y el orden en el que va, no regresa nada
	*/
	private function orderImages()
    {
		
		$arrVars = PwFunciones::getPVariable("params");
		

        $arrItems = explode(",", $arrVars);

        $cont = 1;
        foreach($arrItems as $id)
        {			
            $datos = array("SORT" => $cont);
            $keyFields = array("ID" => $id);
           	PwSql::updateData($this->connection, "SITE_IMAGES", $datos, $keyFields);
            $cont++;
        }
        return true;

    }
}