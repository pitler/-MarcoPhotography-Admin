<?php

use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;


class ConsultaNetosFijos extends BaseViewTemp
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

            default :
                $this->firstAction = $this->listAction;
            break;

        }

	      $data = file_get_contents('template/listView.html', true);
        $data = preg_replace("/__NAME__/", $this->moduleName, $data);     
        $data = preg_replace("/__LISTACTION__/", $this->listAction, $data);     
        $data = preg_replace("/__FORMACTION__/", $this->formAction, $data);     
        $data = preg_replace("/__FIRSTACTION__/", $this->firstAction, $data);            
        $data = preg_replace("/__CONTROLLER__/", $this->controller, $data);     
        $data = preg_replace("/__VALIDATEFIELDS__/", $this->validateFields, $data);     
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
    $data = $this->getLocalTemplate("filter");
    return $data;
  }
  
  private function getLocalTemplate($name)
  {

    $template["filter"] = <<< TEMP
    
      <div class="row" id = "filterDiv">
        
          <div class="col-lg-12">
          <form class="g-brd-around g-brd-gray-light-v4 " role="form" id="filterForm" name="filterForm">
          <div class = "row">
            <div class = "col-lg-6">
                <label for="f_FechaInicio">Fecha Inicio</label>
                <input type="text" class="form-control" id="f_FechaInicio" name="f_FechaInicio" placeholder="">
            </div>
            <div class = "col-lg-6">
                <label for="f_FechaFin">Fecha Fin</label>
                <input type="text" class="form-control" id="f_FechaFin" name="f_FechaFin" placeholder="">
            </div>
          </div>          
            <br>
            <button class="btn btn-primary" style="margin-right: 5px;" id="btnFilter" name="btnFilter"><i class="fa fa-search"> </i> Filtrar</button>
          </form>
          </div>
       
        </div>
    
TEMP;

return $template[$name];

  }
}