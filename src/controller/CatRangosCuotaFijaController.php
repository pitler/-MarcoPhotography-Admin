<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Sql as PwSql;


class CatRangosCuotaFijaController extends CrudController 
{

	 //Constructor de la clase
  function __construct()
  {
      //Validamos permisos de la clase con los perfiles
      $this->className = "CatRangosCuotaFija";        
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



		//Propiedades para un boton extra
		$this->tableProp["extraActions"] = array(
			array("button" => "u-btn-teal", "name" => "btnGrid", "function" => "rangosData", "icon" => "hs-admin-window", "title" => "DESCRIPCION", "extras" => array("ID", "CLIENTES_CONTABILIDAD", "DESCRIPCION")),
		);



		//Traemos el modelo
        $this->model = $this->getModel();
        $this->tableName = "FC_CAT_RANGOS_CUOTA_FIJA";

		error_log("Mode :: $mode");
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
			
			case "detailList" :
				$data = self::getDetail();
				break;

			case "detailSave" :
				$data = self::detailSave();
				break;
			case "detailDelete" :
				$data = self::detailDelete();
				break;

			case "detailUpdate" :
				$data = self::detailUpdate();
			break;
        	default :
				$data = parent::getList();
        	break;
        }
		return $data;
	}




	private function detailDelete()
	{
		
		
		$formParams = json_decode(rawurldecode(PwFunciones::getPVariable("params")));   
		

		if($formParams->id && $formParams->rango)
		{
		
			$condition = array("ID" => $formParams->id, "ID_RANGO" => $formParams->rango);
			PwSql::deleteData($this->connection, "FC_DETALLE_RANGOS_CF", $condition);
		}

		$result = json_encode(array("status" => "delete", "content" =>""));
		return $result;


	}

	private function detailSave()
	{
		
		$keyParams = rawurldecode(PwFunciones::getPVariable("keyParams"));    		
		//Convertimos el json en array
		$keyParams = json_decode( PwSecurity::decryptVariable(1,$keyParams));


		$formParams = json_decode(rawurldecode(PwFunciones::getPVariable("params")));   
		$fields = "ID, ID_RANGO, MINIMO, MONTO";
		$datos = "?,?,?,?";

		$id = PwFunciones::getConsecutivo ( $this->connection, "FC_DETALLE_RANGOS_CF", "ID");	

		$params = array($id, $keyParams->ID, $formParams->minimo, $formParams->monto);

		if($formParams)
		{
			PwSql::insertData($this->connection, "FC_DETALLE_RANGOS_CF", $fields, $datos, $params);
		}

		return self::getDetail(1);
	}

	private function detailUpdate()
	{

		$formParams = json_decode(rawurldecode(PwFunciones::getPVariable("params")));   
		
		if($formParams)
		{
			$datos = array("MINIMO" => str_replace(",", "", $formParams->minimo), "MONTO" => str_replace(",", "", $formParams->monto));
			$keyFields = array("ID"=>$formParams->id, "ID_RANGO"=> $formParams->rango);
			PwSql::updateData($this->connection, "FC_DETALLE_RANGOS_CF", $datos, $keyFields);
		}

		$result = json_encode(array("status" => "update", "content" =>""));
		return $result;
	}

	//Pintamos la tabla
	private function getDetail($saveAction = null)
	{
		//PwFunciones::getVardumpLog($_POST);

		$keyParams = rawurldecode(PwFunciones::getPVariable("keyParams"));    
		//Dejo en su forma original
		$keyParamsAux = $keyParams;
		//Convertimos el json en array
		$keyParams = get_object_vars(json_decode( PwSecurity::decryptVariable(1,$keyParams)));

		$extraParams = rawurldecode(PwFunciones::getPVariable("extraParams"));    
		$extraParams =  get_object_vars(json_decode( PwSecurity::decryptVariable(1,$extraParams)));
		PwFunciones::getVardumpLog($extraParams);

		$condition["ID_RANGO"] = $keyParams["ID"];
		$data = self::getLocalTemplate("table");
		$data = preg_replace("/__HKEYPARAMS__/", $keyParamsAux, $data);		


		$fields = null;
		
		$order = array("MINIMO");
		$sqlResults = PwSql::executeQuery($this->connection, "FC_DETALLE_RANGOS_CF", $fields, $condition, $order, false, false, false, false);

		$trDetail = self::getLocalTemplate("trDetail");
		$tdDetail = self::getLocalTemplate("tdDetail");

		$tableData = "";
		if($sqlResults)
		{			
			foreach($sqlResults as $sqlItem)
			{				
				$tdDetailAux = $tdDetail;
				$tdDetailAux = preg_replace("/__VALMINIMO__/", number_format($sqlItem["MINIMO"], 2, ".", ","), $tdDetailAux);
				$tdDetailAux = preg_replace("/__VALMONTO__/",  number_format($sqlItem["MONTO"], 2, ".", ","), $tdDetailAux);
				$tdDetailAux = preg_replace("/__ROWID__/", $sqlItem["ID"]."_".$sqlItem["ID_RANGO"], $tdDetailAux);
				$trDetailAux = $trDetail;
				$trDetailAux = preg_replace("/__ITEMS__/", $tdDetailAux, $trDetailAux);
				$trDetailAux = preg_replace("/__ROWID__/", $sqlItem["ID"]."_".$sqlItem["ID_RANGO"], $trDetailAux);
				$tableData .=$trDetailAux;
			}
		}

		$data = preg_replace("/__ITEMS__/", $tableData, $data);
		$result = json_encode(array("status" => "true", "content" =>$data));

		return $result;
	}

   private function getModel()
   {
   		$model = array(

   			array(
   				"id" => "ID",   				
   				"key"  => true,
   				"type" => "text",
   				"label" => "Id.",   				
   				"required" => true,
   				"disabled" => true,
				"value" => "0",	
				"order"	=> "asc",
                "consecutivo" => true,
			),

			array(
   				"id" => "DESCRIPCION",   				   				
   				"type" => "text",
   				"label" => "Rango de Cuota Fija",   
   				"disabled" => false,				
				"required" => true,
				//"editable" => false,
			),

			array(
   				"id" => "CLIENTES_VALUACION",   				   				
   				"type" => "text",
   				"label" => "Clientes Valuación",   				   				
   				"disabled" => false,
   				"required" => true		

			),

			array(
                "id" => "CLIENTES_CONTABILIDAD",   				   				
                "type" => "text",
                "label" => "Clientes Contabilidad",   				   				
                "disabled" => false,
                "required" => true
            ),
   		);

		return $model;
   }

   private function getLocalTemplate($name)
   {


	$template ["table"] = <<< TEMP
	
	<div class="row align-middle" >			
			<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
            	<label for="minimo">Mínimo (fijo)</label>
				<input type="text" class="form-control" id="minimo" name="minimo" placeholder="" value="">      
				<small class="form-control-feedback" id = "minimoError"></small>                
			</div>			
			<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg4">
				<label for="monto">Monto</label>
				<input type="text" class="form-control " id="monto" name="monto" placeholder="" value=""> 
				<small class="form-control-feedback" id = "montoError"></small>                                
			</div>
			<input type="hidden" class="form-control" id="hKeyParams" name="hKeyParams" placeholder="" value="__HKEYPARAMS__"> 
			<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4 align-self-end">
			<button class="btn btn-default " type="button" id = "btnFAdd" name = "btnFAdd">Agregar</button>			
			</div>			
	</div>
	<hr>
	
	
	<div class="tableFixHead text-center">
	<table class = "detailTable" align = "center" id = "detailTable">
		<thead>
		<tr>
			<th width = "30%">Mínimo (fijo)</th>
			<th width = "30%">Monto</th>
			<th></th></tr>
		
		</thead>
		<tbody>
		__ITEMS__
	
		</tbody>
	</table>
	

</div>
<script>
$(document).ready(function() {
	$('.btn_save').hide();
	$('.btn_cancel').hide();  
	//Agregar desde el boton para nuevo detalle
	$("#btnFAdd").on('click', function(e) {
	   
		e.preventDefault();
		errorFlag = false;
		let minimo = $('#minimo').val();
		let monto = $('#monto').val();

		validaCampo('minimo', 'numeric');
		validaCampo('monto', 'numeric');
		
		if(errorFlag == true)
		{
			return false;
		}

		//Parámetros predefinidos
		let keyParams = $('#hKeyParams').val();
		let params = new Object();
		params.minimo = minimo;
		params.monto = monto;
		params = JSON.stringify(params);
		getDetailData(detailSave, keyParams, params);
    });



});
</script>
TEMP;

$template ["trDetail"] = <<< TEMP
<tr row_id="__ROWID__">
	__ITEMS__
</tr>
TEMP;


$template ["tdDetail"] = <<< TEMP
<td class = ""><div class="row_data" edit_type="click" col_name="fminimo">__VALMINIMO__</div></td>
<td class = ""><div class="row_data" edit_type="click" col_name="fmonto">__VALMONTO__</div></td>
<td class = "">
	<span class="btn_edit"> <a href="#" class="btn btn-sm u-btn-blue " row_id="__ROWID__" > <i class="hs-admin-pencil"></i></a></span>
	<span class="btn_save"> <a href="#" class="btn btn-sm u-btn-teal"  row_id="__ROWID__"> <i class="fa  fa fa-save"> </i> </a></span>
	<span class="btn_cancel"> <a href="#" class="btn btn-sm u-btn-deeporange " row_id="__ROWID__"> <i class="fa  fa fa-minus-circle"> </i> </a></span>
	<span class="btn_delete"> <a href="#" class="btn btn-sm u-btn-primary" row_id="__ROWID__"> <i class="hs-admin-trash"></i></a></span>
</td>


TEMP;

	   return $template[$name];
   }
}