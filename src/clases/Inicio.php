<?php
use Pitweb\Funciones as PwFunciones;
use Pitweb\Connection as PwConnection;
use Pitweb\Sql as PwSql;
use Pitweb\Security as PwSecurity;





class Inicio
{

	/**
	 * Objeto con los objetos principales del sistema
	 * @var Object - Objeto con los objetos principales del sistema
	 */
	/*private $mainObj;
	
	/**
 	 * Nombre de la clase
 	 * @var String  - Nombre de la clase 
 	 */
 	//private $className;
 	
 	//private $cvePerfil;
 	
 	/**
 	 * Permisos de la clase
 	 * @var Array  - Contiene los permisos de la clase [0]::INSERTAR, [1]::ACTUALIZAR, [2]::BORRAR 
 	 */
 	//private $permissions;
	
	/*function __construct()
	{			
		/*$this->mainObj= $mainObj;
		$this->className = "inicio";
		$this->permissions = $this->mainObj->security->verifyAction($this->className, $this->mainObj);
		$this->cvePerfil = $this->mainObj->security->decryptVariable(2, "cvePerfil");
				
	}*/
	
	public static function getData()
	{	
				
		$connection = PwConnection::getInstance()->connection;   
	  	$cvePerfil = PwSecurity::decryptVariable ( 2, "cvePerfil" );

	  
	  	$data =  file_get_contents('template/inicio.html', true);
	  	$row = self::getTemplate("row");
	  	$item =  file_get_contents('template/inicioBox.html', true);
	  	$liItem = self::getTemplate("liItem");

	  	//Traemos los menus a desplegar
		$condition = array ("STATUS" => 1);
		$fields = array ("ID", "DESC_MENU", "LOGO_MENU", "LABEL" );
		$order = array("ORDEN");
		$tabla = "FC_SYS_MENU";		
		$sqlResults = PwSql::executeQuery($connection, $tabla, $fields, $condition, $order);


		$consulta = "  SELECT MC.ID, MC.NOMBRE_CLASE , MC.CLASE
  			FROM FC_SYS_MODULOS MC, FC_SYS_DETALLE_PERFIL DP
  			WHERE
  			MC.CVE_MENU = ?
  			AND MC.STATUS = ?
  			AND MC.PADRE = ?
  			AND MC.CLASE = DP.CLASE
  			AND DP.CVE_PERFIL = ?  			
  			AND DP.VISUALIZAR = ?
  			ORDER BY MC.NOMBRE_CLASE";
		$ps = PwSql::setSimpleQuery($connection, $consulta);

	
		$itemsData = $row;
	    $rowData = "";


		if ($sqlResults)
		{					

			$cont = 0;
			foreach ($sqlResults as $sqlItem) 
			{

				$params = array($sqlItem["ID"], 1, 0, $cvePerfil, 1);			  
				$sqlResultsAux = PwSql::executeSimpleQuery($ps, $params, $consulta,null, false, false, false);
 				if (!$sqlResultsAux)
			  	{			    	
			    	continue;
			  	}


				$itemAux = $item;
				$liItems = "";
				foreach($sqlResultsAux as $sqlItemAux)
				{
					$liItems .= $liItem;
					$liItems = preg_replace("/__NOMBRE__/", $sqlItemAux["NOMBRE_CLASE"], $liItems);
					$strClass =  PwSecurity::encryptVariable(1, "", $sqlItemAux["CLASE"]);                        
					$strClass2 =  PwSecurity::encryptVariable(1, "", $sqlItemAux["CLASE"]);                        
					$liItems = preg_replace("/__CLASE__/",rawurlencode($strClass), $liItems);
				}

				$itemAux = preg_replace("/__ITEMS__/", $liItems, $itemAux);
				$itemAux = preg_replace("/__TITULO__/", $sqlItem["DESC_MENU"], $itemAux);
				
				if($cont == 3)
	            {
	                $itemsData = preg_replace("/__RDATA__/", $rowData, $itemsData);
	                $itemsData .= $row;
	                $rowData = "";
	                $cont = 0;
	            }
	             $rowData.= $itemAux;
	            $cont ++;
				
			}
			
			$itemsData = preg_replace("/__RDATA__/", $rowData, $itemsData);

			/*$inicioBox =  file_get_contents('template/inicioBox.html', true);

			//Lo ponemos aqui ya que como viene en un for, es mejor hacer el prepared stament desde antes y 
			//después solo mandamos parámetos
			
			
			foreach ( $sqlResults as $sqlItem )
			{			
			  
			 
			  
			  //Si no tiene elementos lo saltamos
			  if (!$sqlResultsAux)
			  {
			    //echo "No trae continuo $sqlItem[CVE_MENU]<br>";
			    continue;
			  }
			  
			  $boxAux = $inicioBox;
			  
			  $dataAux = "";
			  $menuItems = "";
			  foreach ( $sqlResultsAux as $sqlItemAux )
			  {
			    
			    $menuItemAux = self::getTemplate("liItem");
			    $icon = $sqlItemAux ["CLASE"];
			    $strClass = $sqlItemAux ["CLASE"];
			    //Si no existe el ícono, ponemos el de default
			    if (! file_exists (PITWEB."/imagenes/menuIcons/$icon.png" ))
			    {
			      $icon = "default";
			    }
			    
			    
			    $menuItemAux = preg_replace ( "/__ICON__/", $icon, $menuItemAux );
			    $strClass = PwSecurity::encryptVariable ( 1, "", "$strClass" );
			    $menuItemAux = preg_replace ( "/__CLASSNAME__/", rawurlencode ( $strClass ), $menuItemAux );
			    $menuItemAux = preg_replace ( "/__NOMBRE__/", $sqlItemAux ["NOMBRE_CLASE"], $menuItemAux );
			    $menuItems .= $menuItemAux;
			  }
			  
			  $boxAux = preg_replace ("/__LIITEMS__/", $menuItems, $boxAux );
			   
			  $menuLabel = $sqlItem ["LABEL"] ? "#".$sqlItem ["LABEL"]."#" : $sqlItem ["DESC_MENU"];			  
			  $boxAux = preg_replace ( "/__TITULO__/", $menuLabel, $boxAux );
			  
			  $boxData .=$boxAux;

			}*/
		}		


		  $data = preg_replace("/__ROWDATA__/",  $itemsData, $data);
		return $data;
	}
	
	public static function getTemplate($name)
	{
	  
	  


  	 $template ["row"] = <<< TEMP
  	 <div class="row">           
  	 	__RDATA__
     </div>  	
TEMP;

     $template ["liItem"] = <<< TEMP
     <li> <a href="?mod=__CLASE__" class="nav-link">__NOMBRE__</a></li>
  	
TEMP;
	  



		return $template [$name];
	}
}

?>