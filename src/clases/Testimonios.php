<?php

use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;


class Testimonios extends BaseViewTemp
{
    //Constructor de la clase
    function __construct()
    {
        $this->className = __CLASS__;
        parent::__construct();
    }
 

    /**
	 * Funcion principal de la clase, decide qu se ejecuta dependiendo de los parametros
	 */
	
	public  function getData()
	{

       // $encrypt  = 2;

        $modeAux = PwFunciones::getPVariable("mode");
        $mode = rawurldecode ($modeAux);
        if($this->encrypt == 2)
        {                           
            $mode = PwSecurity::decryptVariable(1, $mode);           
        }

        switch ($mode)
        {
            case "doInsert" :
                $this->firstAction = $modeAux;
            break;

            default :
                $this->firstAction = $this->listAction;
            break;
        }

        //Campos a validar al hacer el insert
        $this->validateFields = rawurlencode(json_encode(array("ID")));

	    $data = file_get_contents('template/crud.html', true);
        $data = preg_replace("/__ADD__/", $this->getAddBtn(), $data);     
        $data = preg_replace("/__NAME__/", $this->moduleName, $data);     
        $data = preg_replace("/__LISTACTION__/", $this->listAction, $data);     
        $data = preg_replace("/__FORMACTION__/", $this->formAction, $data);     
        $data = preg_replace("/__FIRSTACTION__/", $this->firstAction, $data);            
        $data = preg_replace("/__INSERTACTION__/", $this->insertAction, $data);            
        $data = preg_replace("/__UPDATEACTION__/", $this->updateAction, $data);           
        
        $data = preg_replace("/__CONTROLLER__/", $this->controller, $data);     
        $data = preg_replace("/__VALIDATEFIELDS__/", $this->validateFields, $data);     
        $data = preg_replace("/__DELETEACTION__/", $this->deleteAction, $data);   
        $data = preg_replace("/__FILTERBTN__/", $this->getFilterBtn(), $data);                
        $data = preg_replace("/__FILTER__/", "", $data);   
        
        $data = preg_replace("/__LOADFIRST__/", "", $data);  
        $data = preg_replace("/__VINSERTACTION__/", "", $data);   
        $data = preg_replace("/__SHOWFORMODAL__/", "", $data);   

         //Cargamos el controlador de archivos
         $fileController = rawurlencode( PwSecurity::encryptVariable ( 1, "", "FilesController") );
         $data = preg_replace("/__FILECONTROLLER__/", $fileController, $data);   

         //Cargamos parámetros para las imágenes
         $data .= $this->getImageUploadVars("testimonios");
        
         //Traemos las variables génericas para un modal
        $data .= $this->getDefaultModalVars();
        
        
         //Pintamos el modal para los archivos
         $data .= $this->getDetailModal(true);
     
        return  $data;
	}
}