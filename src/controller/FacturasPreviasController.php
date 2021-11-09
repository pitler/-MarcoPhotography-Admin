<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;
use Pitweb\Connection as PwConnection;



class FacturasPreviasController extends CrudController
{

	//Constructor de la clase
    function __construct()
    {
        //Validamos permisos de la clase con los perfiles
        $this->className = "FacturasPrevias";        
        parent::__construct();
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


    		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "FC_FACTURAS_PREVIAS";


        switch ($mode)
        {

        	case "list" :        	
        		$data = parent::getList();
        	break;

        	case "getForm" :
        		//$data = $this->getForm();
        		$data = parent::getForm();
        	break;

        	case "doInsert" :
        		$data = parent::doInsert();
        	break;

        	case "doUpdate" :
        		$data = parent::doUpdate();        		
        	break;

        	case "doDelete" :
        		$data = parent::doDelete();        		
        	break;

        	default :
				$data = parent::getList();
        	break;
        }

      
		return $data;
	}


	/**
   *Modelo donde se definen las propiedades de los elementos de las formas a usar 
   * @return [type]
   */
   private function getModel()
   {

  


   		$model = array(

          array(
            "id" => "FOLIO_GENERACION",          
            "key"  => true,
            "type" => "text",
            "label" => "Folio generación",          
            "required" => true,
            "editable" => false,
              "value" => "",  
              "order" => "asc"
        ),


        array(
            "id" => "FECHA_FACTURA",
            "type" => "datepicker",
            "label" => "Fecha factura",
            "required" => true,   
            "value" => "", 
            "format" => "d-m-Y H:i:s"            
        ),

        array(
            "id" => "FECHA_FACTURA_T",
            "type" => "datepicker",
            "label" => "Fecha factura T",
            "required" => true,   
            "value" => "", 
            "format" => "d-m-Y H:i:s"            
        ),

        array(
            "id" => "NOMBRE_EMISOR", 
            "type" => "text",
            "label" => "Nombre emisor",
            "value" => "",
            "required" =>true,
        ),

        array(
            "id" => "NOMBRE_RECEPTOR", 
            "type" => "text",
            "label" => "Nombre receptor",
            "value" => "",
            "required" =>true,
        ),

        array(
            "id" => "MONTO_TOTAL", 
            "type" => "text",
            "label" => "Monto total",
            "value" => "",
            "required" =>true,
        ),

        array(
            "id" => "CFD", 
            "type" => "text",
            "label" => "CFD",
            "value" => "",
            "required" =>true,
        ),

       array(
            "id" => "FORMA_PAGO", 
            "type" => "text",
            "label" => "Forma pago",
            "value" => "",
            "required" =>true,
        ),

       array(
            "id" => "METODO_PAGO", 
            "type" => "text",
            "label" => "Metodo pago",
            "value" => "",
            "required" =>true,
        ),

       array(
            "id" => "UUID", 
            "type" => "text",
            "label" => "UUID",
            "value" => "",
            "required" =>true,
        ),

       array(
            "id" => "RFC", 
            "type" => "text",
            "label" => "RFC",
            "value" => "",
            "required" =>true,
        ),

         array(
            "id" => "FECHA_ASOCIACION",
            "type" => "datepicker",
            "label" => "Fecha asociación",
            "required" => true,   
            "value" => "", 
            "format" => "d-m-Y H:i:s"            
        ),

        array(
            "id" => "CVE_CLIENTE", 
            "type" => "text",
            "label" => "Cve. Operadora",
            "value" => "",
            "required" =>true,
        ),

        array(
            "id" => "PIZARRA", 
            "type" => "text",
            "label" => "Pizarra operadora",
            "value" => "",
            "required" =>true,
        ),

        array(
            "id" => "RFC_RECEPTOR", 
            "type" => "text",
            "label" => "RFC Receptor",
            "value" => "",
            "required" =>true,
        ),

         array(
            "id" => "TIPO_CAMBIO", 
            "type" => "text",
            "label" => "Tipo cambio",
            "value" => "",
            "required" =>true,
        ),

        array(
            "id" => "CVE_MONEDA", 
            "type" => "text",
            "label" => "Cve.Moneda",
            "value" => "",
            "required" =>true,
        ),

        array(
            "id" => "MONTO_TOTAL_ORIGEN", 
            "type" => "text",
            "label" => "Monto total origen",
            "value" => "",
            "required" =>true,
        ),

      

    
  
   	);

		return $model;
   }
}