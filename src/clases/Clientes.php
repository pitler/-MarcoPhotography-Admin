<?php

use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;


class Clientes extends BaseViewTemp
{
    
    
  //Constructor de la clase
  function __construct()
  {

    $this->className = "Clientes";        
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
                $this->firstAction = "";
            break;

        }

        //Campos a validar al hacer el insert
        $this->validateFields = rawurlencode(json_encode(array("CVE_CLIENTE")));

        $data = file_get_contents('template/crud.html', true);
        $data = preg_replace("/__ADD__/", $this->getAddBtn(), $data);     
        $data = preg_replace("/__FILTERBTN__/", $this->getFilterBtn(), $data);                
        $data = preg_replace("/__NAME__/", $this->moduleName, $data);     
        $data = preg_replace("/__LISTACTION__/", $this->listAction, $data);     
        $data = preg_replace("/__FORMACTION__/", $this->formAction, $data);     
        $data = preg_replace("/__FIRSTACTION__/", $this->firstAction, $data);            
        $data = preg_replace("/__INSERTACTION__/", $this->insertAction, $data);            
        $data = preg_replace("/__UPDATEACTION__/", $this->updateAction, $data);            
        $data = preg_replace("/__CONTROLLER__/", $this->controller, $data);     
        $data = preg_replace("/__VALIDATEFIELDS__/", $this->validateFields, $data);     
        $data = preg_replace("/__DELETEACTION__/", $this->deleteAction, $data);    
        

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
    $condition = array ("NIVEL_COVAF" => 1);
    $order = array("PIZARRA");
    $select = PwForm::getSelect($this->connection, "FC_CLIENTES", "CVE_CLIENTE_ALTERNO", "CVE_CLIENTE", "PIZARRA", " ", false, $order, $condition, false, "form-control");
    $data = preg_replace("/__SELECT__/", $select, $data);

    return $data;
  }
    
  private function getTemplate($name)
  {           

    $template["filter"] = <<< TEMP
    
      <div class="row" id = "filterDiv">
        
          <div class="col-lg-12">
          <form class="g-brd-around g-brd-gray-light-v4 " role="form" id="filterForm" name="filterForm">
          <label for="clienteAlterno">Cliente alterno</label>
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