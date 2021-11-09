<?php

use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Form as PwForm;


class CatRangosPrecios extends BaseViewTemp
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
        $this->tableProp["filter"] = true;

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
          
                        
        if($this->tableProp["filter"] == true)
        {
          $this->filter = $this->getFilter();
        }
        $data = preg_replace("/__FILTER__/", $this->filter, $data);   

        

        return  $data;
    }
    
    private function getFilter()
    {
        $data = $this->getTemplate("filter");
        $condition = array ();
        $order = array("DESC_SERVICIO");
        $select = PwForm::getSelect($this->connection, "FC_SERVICIOS", "CVE_SERVICIO", "CVE_SERVICIO", "DESC_SERVICIO", " ", false, $order, $condition, false, "form-control");
        $data = preg_replace("/__SELECT__/", $select, $data);

        return $data;
    }

    private function getTemplate($name)
    {           

        $template["filter"] = <<< TEMP
        
        <div class="row" id = "filterDiv">
            
            <div class="col-lg-12">
            <form class="g-brd-around g-brd-gray-light-v4 " role="form" id="filterForm" name="filterForm">
            <label for="clienteAlterno">Servicio</label>
                __SELECT__
                <br>
                <button class="btn btn-primary" style="margin-right: 5px;" id="btnFilter" name="btnFilter"><i class="fa fa-search"> </i> Filtrar</button>
            </form>
            </div>
        
            </div>
        
TEMP;

        return $template[$name];

    } 

}