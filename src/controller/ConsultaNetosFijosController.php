<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;
use Pitweb\Form as PwForm;
use Pitweb\Connection as PwConnection;

class ConsultaNetosFijosController extends ListController
{


    //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "ConsultaNetosFijos";        
      parent::__construct();
  }


	public function getData()
	{

        $this->tableProp["fixed"]["left"] = 0;         
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

        $this->tableProp['listQuery'] = "select decode(f.cve_tipo_cliente,5,'Fideicomiso',decode(f.cve_catalogo_contable,110,'Fondo',800,'Fondo',162,'Siefores','Fondo')) as tipo, 
        an.fecha_saldo as fecha, op.pizarra_operadora as operadora, f.desc_fondo as fondo, round(an.activos_netos,1) as Activos,        
        series_tot as series, series_ac as SERIES_ACTIVAS, ts.DESC_TIPO_SOCIEDAD as TIPO_SOCIEDAD
from (select psc.cve_fondo, psc.fecha_saldo, sum(psc.activos_netos) as activos_netos, count(*) as series_tot, sum(decode(psc.activos_netos, 0, 0, 1)) as series_ac
      from precio_serie_contable psc, fondos f
      where psc.fecha_saldo>=to_date(?,'DD/MM/YYYY') and psc.fecha_saldo <=to_date(?,'DD/MM/YYYY')
            and psc.cve_fondo = f.cve_fondo and f.cve_catalogo_contable in (110,800,38)
            and f.cve_tipo_cliente <> 5 
      group by psc.cve_fondo, psc.fecha_saldo      
      union all
      select po.cve_fondo, po.fecha_precio, sum(po.activos_netos_cont) , 0,0
      from precios_operativos po, fondos f
      where po.fecha_precio>=to_date(?,'DD/MM/YYYY') and po.fecha_precio <=to_date(?,'DD/MM/YYYY')
            and po.cve_fondo = f.cve_fondo and (f.cve_catalogo_contable in (162,500)
                                                or f.cve_tipo_cliente = 5)
      group by po.cve_fondo, po.fecha_precio) an, fondos f left outer join TIPOS_SOCIEDADES ts on f.CVE_TIPO_SOCIEDAD = ts.CVE_TIPO_SOCIEDAD
      , operadoras op, tipos_clientes tc
where an.cve_fondo = f.cve_fondo and f.cve_operadora = op.cve_operadora
      and tc.CVE_TIPO_CLIENTE = f.CVE_TIPO_CLIENTE 
order by op.pizarra_operadora, f.desc_fondo, an.fecha_saldo";
        $this->tableProp['labelParams'] = array('f_FechaInicio','f_FechaFin','f_FechaInicio','f_FechaFin');





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
                "label" => "TIPO",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "FECHA", 
                "label" => "FECHA",
                "type" => "datepicker",           
                "required" => false,          
                "value" => "",
                "jsformat" => "dd-mm-yy",
                "format" => "d-m-Y" 
            ),
            array(
                "id" => "OPERADORA",
                "type" => "text",   
                "label" => "OPERADORA",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "FONDO",  
                "type" => "text", 
                "label" => "FONDO",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "ACTIVOS", 
                "type" => "text",
                "label" => "ACTIVOS",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "SERIES", 
                "type" => "text",
                "label" => "SERIES",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "SERIES_ACTIVAS",
                "type" => "text",   
                "label" => "SERIES ACTIVAS",           
                "required" => false,          
                "value" => ""           
            ),
            array(
                "id" => "TIPO_SOCIEDAD",
                "type" => "text",   
                "label" => "TIPO DE SOCIEDAD",           
                "required" => false,          
                "value" => ""           
            ),
   		);

		return $model;

   }

}
