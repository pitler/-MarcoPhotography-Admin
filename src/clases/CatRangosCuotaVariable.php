<?php

use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;


class CatRangosCuotaVariable extends BaseViewTemp
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

         //Para el detalle del nuevo form
         $data .= $this->getLocalTemplate("detail");
         $detailList = rawurlencode( PwSecurity::encryptVariable ( 1, "",  "detailList") );
         $detailSave = rawurlencode( PwSecurity::encryptVariable ( 1, "",  "detailSave") );
         $detailDelete = rawurlencode( PwSecurity::encryptVariable ( 1, "",  "detailDelete") );
         $detailUpdate = rawurlencode( PwSecurity::encryptVariable ( 1, "",  "detailUpdate") );
         $data = preg_replace("/__DETAILLIST__/", $detailList, $data);   
         $data = preg_replace("/__DETAILSAVE__/", $detailSave, $data);   
         $data = preg_replace("/__DETAILDELETE__/", $detailDelete, $data);   
         $data = preg_replace("/__DETAILUPDATE__/", $detailUpdate, $data);   
 
         //Pintamos el nuevo form
         $data .= file_get_contents('template/detailModal.html', true);        

        return  $data;
  }
  
  private function getLocalTemplate($name)
  {

    
    $template["detail"] = <<< TEMP
    <script>
      var detailList = '__DETAILLIST__';
      var detailSave = '__DETAILSAVE__';
      var detailDelete = '__DETAILDELETE__';
      var detailUpdate = '__DETAILUPDATE__';
    </script>
    
TEMP;

return $template[$name];

  }

}