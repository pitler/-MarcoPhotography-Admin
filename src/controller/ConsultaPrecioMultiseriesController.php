<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;

class ConsultaPrecioMultiseriesController extends ListController
{


    //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "ConsultaPrecioMultiseries";        
      parent::__construct();
  }


	public function getData()
	{

        $this->tableProp["fixed"]["left"] = 5;         
        $this->tableProp["fixed"]["right"] = 0;         
	
		$data = "";

		$encrypt  = PwFunciones::getPVariable("encrypt");

   	    $mode = rawurldecode (PwFunciones::getPVariable("mode"));
        if($encrypt == 2)
        {                       	
           	$mode = PwSecurity::decryptVariable(1, $mode);           
        }

		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "";

        $this->tableProp['listQuery'] = "select decode(f.cve_tipo_cliente,5,'FIDEICOMISO',decode(f.cve_catalogo_contable,110,'FONDO',800,'FONDO',162,'SIEFORES','FONDO')) as tipo, 
        op.pizarra_operadora as operadora, f.desc_fondo as fondo,
        psc.cve_fondo, psc.cve_serie_fondo, psc.fecha_saldo, nvl(psc.precio_contable,0) as precio_contable, 
        nvl(psca.precio_contable,0)-nvl(psc.precio_contable,0) as diferencia, 
        case when psca2.total > 2 then
           decode(psc.cve_serie_fondo,'A','-',decode(nvl(psc.precio_contable,0),0,'-',decode(nvl(psc.ACTIVOS_NETOS,0),0,'-',decode(trunc(abs(nvl(psca.precio_contable,0)-nvl(psc.precio_contable,0))/0.000005000000001),0,'-','MULTI')))) 
        else '-'
        end as es_multi,       
        psc.ACTIVOS_NETOS AS ACTIVOS, case when psc.ACTIVOS_NETOS > 0 then 'SERIE' else '-' end as SERIE_ACTIVA, ts.DESC_TIPO_SOCIEDAD as TIPO_SOCIEDAD
        ,psca2.total
        
 from precio_serie_contable psc, (select psc.cve_fondo, psc.fecha_saldo, psc.precio_contable
                                   from precio_serie_contable psc
                                   where psc.CVE_FONDO >= 0 and psc.fecha_saldo = to_date(?,'DD/MM/YYYY')
                                         and psc.cve_serie_fondo = 'A') psca, fondos f left outer join TIPOS_SOCIEDADES ts on f.CVE_TIPO_SOCIEDAD = ts.CVE_TIPO_SOCIEDAD
                               , (select psc.cve_fondo, psc.fecha_saldo, count(*) as total
                                   from precio_serie_contable psc
                                   where psc.CVE_FONDO >= 0 and psc.fecha_saldo = to_date(?,'DD/MM/YYYY')
                                   group by psc.cve_fondo, psc.fecha_saldo) psca2
       , operadoras op
 where psc.fecha_saldo = to_date(?,'DD/MM/YYYY')
       and psc.cve_fondo = psca.cve_fondo
       and psc.fecha_saldo = psca.fecha_saldo      
       and psc.cve_fondo = psca2.cve_fondo
       and psc.fecha_saldo = psca2.fecha_saldo      
       and psc.cve_fondo = f.cve_fondo
       and f.cve_catalogo_contable in (110,800,38) and f.cve_tipo_cliente <> 5 
       and f.cve_operadora = op.cve_operadora
 order by op.pizarra_operadora, psc.cve_fondo, psc.cve_serie_fondo, psc.fecha_saldo";
        $this->tableProp['labelParams'] = array('f_FechaInicio','f_FechaInicio','f_FechaInicio');

        switch ($mode)
        {
            case "list" :        	

        		$data = parent::getList();
        	break;
        	default :
				$data = parent::getList();
        	break;
        }

      
		return $data;	
    }
    
   private function getModel()
   {
   		$model = array(        
            array(
                "id" => "TIPO",          
                "key"  => true,
                "type" => "text",
                "label" => "Tipo",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "OPERADORA",
                "type" => "text",   
                "label" => "Operadora",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "FONDO",  
                "type" => "text", 
                "label" => "Fondo",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "CVE_FONDO",  
                "type" => "text", 
                "label" => "Cve. Fondo",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "CVE_SERIE_FONDO",  
                "type" => "text", 
                "label" => "Cve. Serie fondo",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "FECHA_SALDO", 
                "label" => "FECHA",
                "type" => "datepicker",           
                "required" => false,          
                "value" => "",
                "jsformat" => "dd-mm-yy",
                "format" => "d-m-Y" 
            ),
            array(
                "id" => "PRECIO_CONTABLE", 
                "type" => "text",
                "label" => "Precio contable",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "DIFERENCIA", 
                "type" => "text",
                "label" => "Diferencia",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "ES_MULTI",
                "type" => "text",   
                "label" => "Multi",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "ACTIVOS",
                "type" => "text",   
                "label" => "Activos",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "SERIE_ACTIVA",
                "type" => "text",   
                "label" => "Serie Activa",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "TIPO_SOCIEDAD",
                "type" => "text",   
                "label" => "Tipo de Sociedad",           
                "required" => false,          
                "value" => ""           
            )
   		);

		return $model;

   }

}
