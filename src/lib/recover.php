<?php

/**
 * Clase que contiene todos los métodos para recuperar contraseña
 * al usuario
 */
include_once ("config.php");
include_once PWSYSLIB.'funciones.php';
include_once PWSYSLIB.'mainVars.php';
//error_reporting(E_ALL);
//ini_set('error_log','logs/sysLog.log');

class recover extends funciones
{

	/**
	 * Objeto con las variables principales
	 * @var mainVarsObject Objeto con las variables principales
	 */
	private $mainObj;
	
	/**
	 * Nombre de la clase
	 * @var String Nombre de la clase
	 */
	private $className;
	
	/**
	 * Nombre de la tabla a leer
	 * @var String  - Nombre de la tabla a leer
	 */
	
	public $tableName;
	
	function __construct()
	{
		$this->mainObj = new mainVars ( DBASE, "" );
		$this->className = "recover";
		$this->tableName = "SYS_USUARIOS";
	}


	public function getPageData()
	{	   
		
		
		
	  $data = $this->getTemplate("main");
	  $code = $this->getGVariable("code");
	  $chars = array("'", "#", "+" ,"-","*","<",">","'","\"", "\\", "/");
	  $code = str_replace( $chars , "" , $code);
	  
	  //$act = $this->getGVariable("act");
	  $mode = $this->getPVariable("mode");
	  
	  
	  
	  if(!$mode)
	  {
	    if(!$code)
	    $mode = 1;
	    else
	    $mode = 3;
	  }
	  
	  switch ($mode) 
	  {
	    case 1: 
	      $content = $this->getTemplate("envio");
	      $msj = $this->getGVariable("msj");
	      $aviso = "";
	      if($msj == 1)
	      {
	      	$aviso = "La contraseña para el usuario ha caducado, favor de cambiarla";
	      }
	      $content = preg_replace("/__MSJ__/", $aviso, $content);
	      
	      break;	      
	    case 2:
	      $content = $this->generaCodigo();
          break;	    
	    case 3:
	      $content = $this->getCambio();
	      break;
	    case 4:
	      $content = $this->doCambio();
	      break;
	  }
	  
	  $data = preg_replace ( "/__LANGR__/", $this->mainObj->label ["LANGR"], $data );
	  $data = preg_replace ( "/__LLANG__/", $this->mainObj->label["LLANG"], $data );
	  $data = preg_replace("/__CONTENT__/", $content, $data);
	  $data = $this->getLangLabels ( $data );
	  
	  return $data;
		
	}
	
	
	/**
	 * Función para cambiar las etiquetas de idiomas que se encuentran en el código
	 * busca este formato para reemplazar #_XXXX_#
	 * @param String $content Toda la cadena html que regresa la página
	 * @return String $content La misma variable pero ya reemplazado
	 */
	private function getLangLabels($content)
	{
		$matches = null;
		$ptn = "/#_[a-zA-Z0-9_]*_#?/";
		preg_match_all ( $ptn, $content, $matches, PREG_PATTERN_ORDER );
	
		if ($matches)
		{
			$matches = $matches [0];
			foreach ( $matches as $match )
			{
				$match = preg_replace ( array ("/#_/", "/_#/" ), "", $match );
				$label = $this->mainObj->label [$match];
				$content = preg_replace ( "/#_" . $match . "_#/", $label, $content );
				if (! $label || $label = "")
				{
					$this->setError ( 7, "$match :: Idioma: $_SESSION[lang]", __CLASS__ );
				}
			}
		}
		return $content;
	}
	
	/**
	 * Método que realiza el camvio de contraseña
	 * 
	 * @return mixed|string
	 */
	private function doCambio()
	{
	 $code = $this->getGVariable("code");
	 $chars = array("'", "#", "+" ,"-","*","<",">","'","\"", "\\", "/");
	 $code = str_replace( $chars , "" , $code);
	 $status = "";
	 
	 if(!$code)
      { 
       	$content = $this->getError(303, "");
      }
      else
      {
        $password = $this->getPVariable("password");
        $cpassword = $this->getPVariable("cpassword");
        
        if($password != $cpassword)
        {
			$content = $this->getError(304, $code);
          	return $content;
        }
        
      if(strlen($password) < 8)
      	{ 	
        	$content = $this->getError(305, $code);
          	return $content;
      	}
      	
        //Verifica que tenga al menos una letra      	
		$patron = "/[a-zA-Z]/";
		$strMatch = preg_match($patron, $password, $coincidencias);
		if($strMatch === 0 || $strMatch === false)
		{
			$content = $this->getError(306, $code);
			return $content;
		}
		
		
		//Verifica que tenga  una letra minuscula
		$patron = "/[a-z]/";
		$strMatch = preg_match($patron, $password, $coincidencias);
		if($strMatch === 0 || $strMatch === false)
		{
			$content = $this->getError(314, $code);
			return $content;
		}
		
		//Verifica que tenga  una letra mayuscula
      	$patron = "/[A-Z]/";
		$strMatch = preg_match($patron, $password, $coincidencias);
		if($strMatch === 0 || $strMatch === false)
		{
			$content = $this->getError(307, $code);
			return $content;
		}
		
		//Verifica que tenga un numero
        $patron = "/\d/";
		$strMatch = preg_match($patron, $password, $coincidencias);
		if($strMatch === 0 || $strMatch === false)
		{
			$content = $this->getError(308, $code);
			return $content;
		}
		
		//Verifica cualquier letra, numero y caracter especial en la lista
        $result = preg_match_all('/[^A-Za-z0-9(){}&$!¡¿?.:%_|°¬@\/=´¨+*~^,;]/', $password, $noCoincide);
		$results = "";
		$strError = "";
		$noCoincide = $noCoincide[0];
		foreach ($noCoincide as $strKey)
		{
			$results .= "<li><b>$strKey</b></li>";
		}		
		
		if($results != "")
		{

			  $strError = $this->getTemplate("pregError");
			  $strError = preg_replace("/__RESULTS__/", $results, $strError);
			  //"  <br>Los siguientes caracteres no son v&aacute;lidos :  <ul>   $results  </ul>";
			  $content = $this->getError(309, $code, $strError);
			  return $content;
		}
		
        $consulta = "SELECT *
                     FROM SYS_USUARIOS
                     WHERE CODE = ? ";
         
        $ps = $this->mainObj->sql->setSimpleQuery ( $this->mainObj->connection, $consulta );
        $params = array($code);
        $sqlResultsAux = $this->mainObj->sql->executeSimpleQuery ( $ps, $params, $consulta, null, false, true );

      
    	if($sqlResultsAux)
    	{
    	  foreach ($sqlResultsAux as $dataResultsItem)
    	  {    	    
    			$cveUser = $dataResultsItem["CVE_USUARIO"];
    			$status = $dataResultsItem["STATUS"];
    	  }

    	  //Revisa que el password no haya sido usado
    	  //$llave = md5($password);
    	  $llave = hash("sha256", $password);
    	  $condition = array("CVE_USUARIO" => $cveUser, "LLAVE" => $llave);
    	  $fields = array("LLAVE");
    	  $sqlData = $this->mainObj->sql->executeQuery($this->mainObj->connection, "SYS_HISTORICO_LLAVES", $fields, $condition);
    	  if(is_array($sqlData) && sizeof($sqlData) >= 1)
    	  {
    	    $msg =  $this->getErrorMessage(58);    	    
    	  	$content = $this->getError(58, $code);
    	  	$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUser, $this->className, "Recover", $msg);
    	  	return $content;
    	  }
    	
    	 
    	  $hoy = date("Ymd");
    	  //Se actualiza la información de la base de datos
    	  $keyFields = array("CVE_USUARIO" => $cveUser);
    	  
    	  $datos = array("LLAVE" => $llave, "CODE" => "", "FECHA_CAMBIO" => $hoy);
    	  if($status == 4)
    	  {
    	  	$datos["STATUS"] = 1; 
    	  }

    	  $consulta = $this->mainObj->sql->updateData($this->mainObj->connection, $this->tableName, $datos, $keyFields);
    	  
    	  
    	  $consulta = 1;
    	  //si es un password válido y se pudo actualizar, lo ponemos en el histórico
    	  if($consulta)
    	  {
    	  	$this->mainObj->system->addUserKey($this->mainObj,2,$cveUser,$llave);
    	  }
    	  
    	  
    	  if($consulta != 1)//Si existe error al realizar el cambio
    	  {
    	  	$msg = $this->getErrorMessage(312);
	  	    $this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUser, $this->className, "Recover", $msg);
    	    $content = $this->getError(312, '');
    	    return $content;
    	   }
    	   else
    	   {
    	   		$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUser, $this->className, $this->tableName,"Recover", "Cambio exitoso");
    	   	  $content = $this->getTemplate("success");
    	    	return $content;    	    
    	    }	      	  
    	}
    	else
    	{
    	  $content = $this->getError(303, $code);
		  return $content;   
    	}
     }
  
	} 
	
	/**
	 * Cambia el password de ldap
	 * @param string $login
	 * @param string $newPasswd
	 * @return number
	 */
/*private function changeLdapPasswd($login, $newPasswd)
	{
	  //Datos ldap
      $ldaphost = LDAPHOST;
      $ldapport = LDAPPORT;
      $username = LDAPSTRING;
      $username = preg_replace ( "/__LOGIN__/", $login, $username );
      
      //Datos ldap Admin
      $admUsername = 'cn=cvfldapadm,ou=Administradores,dc=covaf,dc=com';
      $admPassword = 'AdmPADL';

      ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
      //Se realiza la conexion por ldap
      $ad = ldap_connect($ldaphost, $ldapport);
	  
      if(!$ad)
      { 
      	$msg = $this->getErrorMessage(70);
      	$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $login, $this->className, "", "Cambio pass LDAP", "Error: $msg", "PII");
		$this->setError ( 70, null, $this->className, 2 );
      	return 0;  
      }

      //  Especifico la versión del protocolo LDAP
      $protocol = ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);	   
      if(!$protocol)
      {
      	$msg = $this->getErrorMessage(71);
      	$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $login, $this->className, "", "Cambio pass LDAP", "Error: $msg", "PII");
      	$this->setError ( 71, null, $this->className, 2 );
        return 0;
      }
	
      $bd = ldap_bind($ad, $admUsername, $admPassword);
      if($bd)
      {
      	
    	$newPassword = $newPasswd;
    	$userdata["userPassword"] = $newPassword;
    
   		$result = ldap_mod_replace($ad, $username , $userdata);
   	    
		if ($result)
		{
		  $this->mainObj->sql->insertaBitacora($this->mainObj->connection, $login, $this->className, "", "Cambio pass LDAP", "Exitoso", "PII");
		  return 1;
		} 
		else 
		{
		  $msg = $this->getErrorMessage(312);
		  $this->mainObj->sql->insertaBitacora($this->mainObj->connection, $login, $this->className, "", "Cambio pass LDAP", "Error: $msg", "PII");		 
		  $this->setError (312, null, $this->className, 2 );
		  return 0;
		}
	  }
	  else
	  {
	  	$msg = $this->getErrorMessage(72);
	  	$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $login, $this->className, "", "Cambio pass LDAP", "Error: $msg", "PII");
	  	$this->setError( 72, null, $this->className, 2 );
	  	return 0;
	  }
		 
	}*/
	
	/**
	 * Método que recibe el código generado por el sistema
	 * devuelve el formulario para el cambio de contraseña
	 * @return mixed
	 */
	private function getCambio()
	{
	   $code = $this->getGVariable("code");
	   $chars = array("'", "#", "+" ,"-","*","<",">","'","\"", "\\", "/");
	   $code = str_replace( $chars , "" , $code);
      	
	  if(!$code)
      { 
        $content = $this->getError(303, "");
		return $content;
      }
      else
      {
      	$consulta = "SELECT *
                     FROM SYS_USUARIOS
                     WHERE CODE = ? ";
      	
      	$ps = $this->mainObj->sql->setSimpleQuery ( $this->mainObj->connection, $consulta );
      	$params = array($code);
      	$sqlResultsAux = $this->mainObj->sql->executeSimpleQuery ( $ps, $params, $consulta, null, false, true );

    	if($sqlResultsAux)
    	{
    		foreach ($sqlResultsAux as $dataResultsItem)
    		{
    			$dataResultsItem = $this->getArrayObject($this->mainObj->conId, $dataResultsItem );
    			$user = $dataResultsItem["NOM_USUARIO"]; 
    			$cveUser = $dataResultsItem["CVE_USUARIO"];
    			$code = $dataResultsItem["CODE"];
    		}
    		
    	  
    	  $content = $this->getTemplate("form");
    	  $content = preg_replace("/__USUARIO__/", $user, $content);
    	  $content = preg_replace("/__CODE__/", $code, $content);
    	}
    	else
    	{   
    		$msg = $this->getErrorMessage(303);
    		//$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUser, $this->className, "", "Cambio pass", "Error: $msg", "PII");
    		$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUser, $this->className, "Recover", $msg);
			  $content = $this->getError(303, "");
    	}
     }
     
     return $content;
	  
	  
	} 
 
 /**
  * Se verifica que exista correo
  * Si sexiste se obtienen los datos y se manda correo
  * @return mixed|string
  */	
  private function generaCodigo()
  {
  	
  
    $correo = $this->getPVariable("correo");
  	$correoValido = filter_var($correo, FILTER_VALIDATE_EMAIL);
    
    if($correoValido == false)
    {
    	return $this->getError(301, "");
    }
    
    if($correo)
    {
      
      $data = "";
      $consulta = "SELECT *
                     FROM SYS_USUARIOS
                     WHERE CORREO = ? ";
      
      $ps = $this->mainObj->sql->setSimpleQuery ( $this->mainObj->connection, $consulta );
      $params = array($correo);
      $sqlResultsAux = $this->mainObj->sql->executeSimpleQuery ( $ps, $params, $consulta, null, false, true );
 
      if($sqlResultsAux)
      {
       if(count($sqlResultsAux) == 1){
      	foreach ($sqlResultsAux as $dataResultsItem)
      	{
         // $dataResultsItem = $this->getArrayObject($this->mainObj->conId, $dataResultsItem );
          $user = $dataResultsItem["NOM_USUARIO"];
          $cveUser = $dataResultsItem["CVE_USUARIO"];
          $cadena = $this->generaClaveMail(20).'';
          $keyFields = array("CVE_USUARIO" => $cveUser);
          $datos = array("CODE" => $cadena);
      	}
      	 
      	//error_log("Entro aqui :: xxx");
      
        $this->mainObj->sql->updateData($this->mainObj->connection, $this->tableName, $datos, $keyFields);
        //  $this->getVardumpLog($consulta);
        $consulta = true;
        
        if($consulta)
        {          
      	  $send = $this->mainObj->correo->enviaCorreo($this->mainObj,$correo, "Solicitud cambio de contraseña", 2, $user, $cadena);
      	
      	  if(!$send)
      	  {
      	  	$msg = $this->getErrorMessage(302);
	  	    $this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUser, $this->className, "Recover", $msg);
	  	    
      		return $this->getError(302, "");
      	  }
      	  else
      	  {
      	    $this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUser, $this->className, "Recover", "Envio de código para cambio de contraseña del usuario $cveUser");
      		  return $this->getTemplate("enviado");
      		
      	  }
        }else{//Error al generar codigo
        	$msg = $this->getErrorMessage(311);
        	$this->mainObj->sql->insertaBitacora($this->mainObj->connection, $cveUser, $this->className, "Recover", $msg);
        	
        	return $this->getError(311, "");
        }
       }else{//Si existen mas correos
       	  $msg = $this->getErrorMessage(313);      	 
       	  return $this->getError(313, "");
       }
      }
      else
      {        
        return $this->getError(310, "");
      }    
    }
  }
  
  /**
   * Genera un código para recuperar contraseña
   * @param integer $longitud
   * @return string
   */
  private function generaClaveMail($longitud){ 
       $cadena="[^A-Z0-9]"; 
       return substr(str_replace ($cadena, "", md5(rand())) . 
       str_replace ($cadena, "", md5(rand())) . 
       str_replace ($cadena, "", md5(rand())), 
       0, $longitud); 
} 







//Ejemplo de utilización para una clave de 10 caracteres:

/**
 * Coloca los mensajes de error encontrados
 * @param unknown_type $number
 * @param unknown_type $code
 * @param unknown_type $extra
 * @return mixed
 */
private function getError($number, $code, $extra = "")
	{
		$content = $this->getTemplate("error");
    	$mensaje = $this->getErrorMessage($number);
    	$content = preg_replace("/__MENSAJE__/", $mensaje.$extra, $content);    
    	if($code)
    	{
    		$code = "?code=$code";
    	}	  
    	$content = preg_replace("/__CODE__/", $code, $content);
    	
    	return $content;
	}


	function getTemplate($name)
	{
	  $template["envio"] = <<< TEMPLATE
     <form id="loginForm" method="POST" action = "">
	   <label class = "textLabel"  style="color:red; ">__MSJ__</label>
		 <label class = "textLabel"><strong>#_LRECUPERAPASS_#</strong></label>
	  	<fieldset id= "flogin">
	    	<label for="correo">#_LCORREO_#<br/> 
			<input type="text" name= "correo" id= "correo" size = "40" placeholder="#_LESCRIBECORREO_#"  autocomplete = "off" /><br/>
	  	</fieldset>						
			<fieldset id="logBut">
				<button type="submit" class = "logButton">#_LENVIAR_#</button>
			</fieldset>
			<input type="hidden" name="mode" value = "2">			
     </form>
TEMPLATE;

	     $template["success"] = <<< TEMP
	 <form id="loginForm" method="POST" action = "">
		<table  align = "center">
    		<tr>
    			<td class = "filterSpace">&nbsp;</td>
    			<td class = "filterItem"  align = "center">
    				#_LCAMBIOCORRECTO_# <a href = "/" class = "llink">#_LBACK_#</a>
    			</td>				
    		</tr>
		</table>
     </form>
TEMP;

	  //No se utiliza
	   $template["errorMsg"] = <<< TEMP
	  <div id = "rmsg">
		<p>
		<table  align = "center">
    		<tr>
    			<td class = "filterSpace">&nbsp;</td>
    			<td class = "filterItem"  align = "center">
    				Error al cambiar la contraseña.<br>
    				__MENSAJE__<br><a href= "recover.php" class = "llink">Regresar</a>
    			</td>				
    		</tr>
		</table>
		</p>
	</div>
TEMP;

	   $template["enviado"] = <<< TEMP
	 <form id="loginForm" method="POST" action = "">
		<table  align = "center">
    		<tr>
    			<td class = "filterSpace">&nbsp;</td>
    			<td class = "filterItem"  align = "center">
    				#_LENVIOCORREO_#
    			</td>				
    		</tr>
		</table>
    </form>
	   
TEMP;
 
	   $template["form"] = <<< TEMP

	<form id="loginForm" method="POST" action = "" onSubmit="return comprobarClave()">
		<label class = "textLabel"><strong> Usuario: __USUARIO__</strong></label>
	  	<fieldset id= "flogin">
	    	<label for="password">#_LNUEVOPASS_#<br/> 
				<input type="password" name= "password" id= "password" size = "40" placeholder="#_LESCRIBEPASS_#" autocomplete = "off" /><br/>
	    	<label for="password"> Confirma password <br/> 
				<input type="password" name= "cpassword" id= "cpassword" size = "40" placeholder="#_LCONFIRMAPASS_#" autocomplete = "off" /><br/>
				
	  	</fieldset>						
			<fieldset id="logBut">
				<button type="submit" class = "logButton" >#_LCAMBIAPASS_#</button>
			</fieldset>
	   		<div id="difPass" style="display:none; color:red; float:right"><b>#_LERRORPASS_#</b></div>
			<input type="hidden" name="mode" value = "4">
			<input type="hidden" name="code" value = "__CODE__">
					
     </form>
		<div id="pswd_info">
				<h4>#_LREQUERIMIENTOSPASS_#</h4>
				<ul>
					<!--<li id="letter" class="invalid">#_LALMENOS_# <strong>#_LLETRA_#</strong></li>-->
	   			<li id="small" class="invalid">#_LALMENOS_# <strong>#_LMINUSCULA_#</strong></li>
					<li id="capital" class="invalid">#_LALMENOS_# <strong>#_LMAYUSCULA_#</strong></li>
					<li id="number" class="invalid">#_LALMENOS_# <strong>#_LNUMERO_#</strong></li>
					<li id="caracter" class="invalid">#_LCARCATERESPECIAL_# <strong> (){}&$!¡¿?.:%_|°¬@/=´¨+*~^,; </strong></li>
					<li id="length" class="invalid">#_LALMENOS_# <strong>#_LCARACTERES_#</strong></li>
				</ul>
	   </div>
	   
	   <div id="pswd_infoAux">
				<h4>#_LPASSCORRECTO_#</h4>
	   </div>
	  
	   </div>
TEMP;

	   
$template ["main"] = <<< TEMPLATE
	  
<!DOCTYPE html>
<html lang="es">
 <head>
  <!-- Title -->
  <title> .:: ADMIN COVAF ::. </title>
	  
  <!-- Meta data -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="PRAGMA" content="NO-CACHE" />
  <meta http-equiv="Expires" content="-1" />
  <meta name="robots" content="noindex, nofollow,noarchive,noydir" />
  <meta name="Charset" content="UTF-8" />
  <meta name="creator" content="π-tLeR" />
  <meta name="language" content="es" />
  <meta name="identifier-url" content="" />
  <meta name="robots" content="index, nofollow" />
		
  <link href="css/system/mainStyle.css" rel="stylesheet" type="text/css" />
  <link href="css/system/pswStyle.css" rel="stylesheet" type="text/css" />
	  
  <!--Carga elementos nuevos para explorer < 8 -->
  <!--[if lte IE 8]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
	  
  <!-- Stylesheets -->
  <link href="css/system/loginStyle.css" rel="stylesheet" type="text/css" />
	  
  <!-- Javascripts -->
  <script src="pw/assets/plugins/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
  <script src="js/recover.js" type="text/javascript"></script>
	  
  <style type="text/css" media="screen">
    body { background-color: white; }
  </style>
   
  </head>
	  
  <body>
  <header></header>
  <section>
  <div id= "loginBox">
  <div id= "logLogo">
   <img src="repository/logo/logo.png" border="0" width="120"/>
  </div>
  <div id= "langIcon">
    <a href = "?&lang=__LANGR__" class = "langLink"> __LLANG__ <img src="pw/imagenes/icons/lang.png" class = "centerText"></a>
  </div>
		
  <!-- AQUI ES DONDE VA TODO EL CODIGO EXTRA-->
    __CONTENT__					
  <!-- FIN DEL CODIGO EXTRA-->
		
  </div>
  </section>
  <footer></footer>
  </body>
</html>
TEMPLATE;

$template["error"] = <<< TEMP
	  
	<form id="loginForm" method="POST" action = "">
		<table  align = "center">
    		<tr>
    			<td class = "filterSpace">&nbsp;</td>
    			<td class = "filterItem"  align = "center">
    				__MENSAJE__ <a href= "recover.php__CODE__" class = "llink"> #_LBACK_# </a>
    			</td>				
    		</tr>
		</table>
	</form>	
TEMP;
	  
	    $template["pregError"] = <<< TEMP
	    <br>Los siguientes caracteres no son v&aacute;lidos :  <ul>  __RESULTS__ </ul>	


TEMP;

		return $template[$name];

	}
}
?>