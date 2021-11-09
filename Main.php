<?php
namespace Pitweb;

use Pitweb\Funciones as PwFunciones;
use Pitweb\Security as PwSecurity;
use Pitweb\Login as PwLogin;
use Pitweb\Connection as PwConnection;
use Pitweb\Sql as PwSql;
use Pitweb\Files as PwFiles;
use Pitweb\Recover as PwRecover;

/**
 * Para manejar el main
 * Desde aqui lo tengo
 * 
 */
/**
 * Clase principal del sistema 
 * 
 * @author pitler
 */
// Cargamos las librerias de funciones y configuración
class Main 
{

    /**
     * Nombre de la clase
     * @var String Nombre de la clase
     */
    private $className;

    /**
     * Nombre de la clse default
     * @var String Nombre de la clase default
     */
    private $defaultClass;

    private  $nombreUsuario;
    private  $cvePerfil;


    
    /**
     * Clase constructora, aqui se crea el objeto principal y sus hijos
     */
    public function __construct()
    {

        $this->className = "Main";
        $this->defaultClass = "Inicio";

    

        
    }

    public function getPageData()
    {
        
        $rFlag = PwFunciones::getGVariable("rFlag");
        PwFunciones::validaRepositorios($rFlag);



        //Variable que regresa el contenido final
        $content = "";

        $connection = PwConnection::getInstance()->connection;        
       
        // Vamos por el modulo a instanciar si no existe ponemos inicio de default        
        $modulo = rawurldecode(PwFunciones::getGVariable("mod"));
       
        
        //Si está logueado, intentamos desencriptar
        //TODO Checar si el encriptar o desencriptar por get sea desde el config        
        if(isset($_SESSION["autentified"]))
        {
            $modulo = PwSecurity::decryptVariable(1, $modulo);
        }        
        
       

        // Valida que solo sean enviados números y letras para la variable modulo
        $regValidate = PwFunciones::validateData($modulo);
        
       

        //Si no tiene nada o falla la validacion, llamamos el inicio    
        $modulo == null;     
        if (! $modulo || $modulo == $this->defaultClass || $regValidate == false) 
        {
            $modulo = $this->defaultClass;
        }


        //error_log("Modulo :: $modulo");
        //Si queremos hacer el recover
        //Checamos que no este autentificado
        if($modulo == "recover" &&  ! isset($_SESSION["autentified"]))
        {

            $mode = PwFunciones::getPVariable("mode");
            if (!isset($mode)) 
            {
                $mode = 1;
            }

            $content = PwRecover::getData($mode);
            return $content;
        }

        
        //Definimos el nombre de la clase a usar
       // define('MODULENAME', $modulo);
        $titleName = "";
        
        /*****************************************************************/
        /*** AQUI SE VERIFICA SI ESTÁ O NO AUTENTIFICADO EN EL SISTEMA ***/
        /*****************************************************************/         
        
        // Si requiere login y no está autentificado, vamos por la pantalla

      //  error_log("Login:: ".REQLOGIN . " sESSION :: ".$_SESSION["autentified"]);
        if (REQLOGIN == 1 && ! isset($_SESSION["autentified"])) 
        {
            $mode = PwFunciones::getPVariable("mode");
            if (!isset($mode)) 
            {
                $mode = 1;
            }

            $content = PwLogin::getData($mode);
            return $content;
        }
        
        PwSecurity::verifySession();
        
        $this->nombreUsuario = PwSecurity::decryptVariable(2, "nombre");
        
        $this->cvePerfil = PwSecurity::decryptVariable ( 2, "cvePerfil" );      

        // Se activa si queremos que se verifique la sesión cada que se recarga o cambia de página
        // $this->mainObj->security->verifySession();
        
        // Mandamos cvePerfil y cveUsuario para hacerla visible en todos lados
        // Se manda siempre y cuando está encriptada en la sesión
       // $this->mainObj->cvePerfil = $this->mainObj->security->decryptVariable(2, "cvePerfil");
       // $this->mainObj->cveUsuario = $this->mainObj->security->decryptVariable(2, "cveUsuario");
        
        // Vamos por el template principal de la página
        $content = file_get_contents('template/index.html', true);
        $content = preg_replace("/__PAGETITLE__/", PAGETITLE, $content);
        $content = preg_replace("/__ANIO__/", date("Y"), $content);
        $content = preg_replace("/__YEAR__/", date('Y'), $content);


        /**Para el header */
        $logout = rawurlencode(PwSecurity::encryptVariable(1, "", "Logout"));        
        $content = preg_replace("/__LOGOUT__/", $logout, $content);
        $content = preg_replace("/__USUARIO__/", $this->nombreUsuario, $content);

        $avatar = $this->getAvatar();
        $content = preg_replace("/__AVATAR__/", $avatar, $content);

        /**Para los breadcrumbs,hay que hacer funcion */

        $modName = 	PwFunciones::getIdValue($connection,"FC_SYS_MODULOS", "CLASE", $modulo, "NOMBRE_CLASE");

        $content = preg_replace("/__MODNAME__/", $modName, $content);



        /*$header = $this->getHeader();       
        $content = preg_replace("/__HEADER__/", $header, $content);*/

        $menu = "";
        $menu = $this->getSideBarMenu($connection, $modulo);
        
        $content = preg_replace("/__SIDEBAR__/", $menu, $content);
        $content = preg_replace("/__MCLASS__/", $modulo, $content);
      
        /**
         * Se cargan las hojas de estilo (css) para la clase que se manda a llamar
         * Si no existe, no pinta nada y se queda con el principal **
         */
        $moduleStyle = "";
        $cssFile =  $modulo;

        //error_log(SITECSSPATH . $cssFile . "Style.css");
        if (file_exists(SITECSSPATH . $cssFile . "Style.css")) {
            $moduleStyle = $this->getTemplate("moduleStyle");
            $moduleStyle = preg_replace("/__STYLENAME__/", $cssFile, $moduleStyle);
        }
        $content = preg_replace("/__STYLE__/", $moduleStyle, $content);
        
        // Vamos por las librerias js del módulo, si no existe fisicamente , no traemos nada
        $moduleJs = "";
        
        $jsFile = "js" . ucfirst( $modulo);
        
        if (file_exists(SITEJSPATH . $jsFile . ".js")) 
        {
            $moduleJs = $this->getTemplate("jsModule");
            $moduleJs = preg_replace("/__JSFILE__/", $jsFile . ".js", $moduleJs);
        
        }
        
        $content = preg_replace("/__JS__/", $moduleJs, $content);
        
        //Traemos el id de la clase que usamos
        $idModule = PwFunciones::getIdValue($connection,"FC_SYS_MODULOS", "CLASE", $modulo, "ID");
        // Traemos código extra que se incluye por lo general en el <head></head> del index
        $extras = $this->getExtras($connection,  $idModule);        
        
        $content = preg_replace("/__CSSGLOBAL__/", $extras["CSSGLOBAL"], $content);
        $content = preg_replace("/__CSSIMPLEMENTING__/", $extras["CSSIMPLEMENTING"], $content);
        $content = preg_replace("/__CSSCUSTOM__/", $extras["CSSCUSTOM"], $content);
        $content = preg_replace("/__JSGLOBAL__/", $extras["JSGLOBAL"], $content);
        $content = preg_replace("/__JSIMPLEMENTING__/",nl2br($extras["JSIMPLEMENTING"]), $content);
        $content = preg_replace("/__JSCUSTOM__/", $extras["JSCUSTOM"], $content);
        $content = preg_replace("/__INIT__/", $extras["INIT"], $content);
        
        // Verificamos si existe físicamente la clase del modulo

        $classPath = SITECLASESPATH.ucfirst( $modulo).".php";
        if (file_exists($classPath)) 
        {
            $tiempoInicio = microtime(true);
            $content = preg_replace("/__CLASE__/",  $modulo, $content);
            
            // Checamos si tiene permiso para ver el módulo solicitado
            $permiso = PwSecurity::validateAccess( $modulo, $this->cvePerfil, $connection);

            error_log("Permiso $modulo");
            PwFunciones::getVardumpLog($permiso);

            if ($permiso["VISUALIZAR"] == 1) 
            {                                   

                //Ponemos en CamelCase el nombre
                $modName = ucfirst( $modulo);
                
                //Instanciamos
                $class = new $modName();

                //Ejecutamos
                $mainData = $class->getData();

                // Reemplazamos el resultado en el __CONTENT__
                $content = preg_replace("/__CONTENT__/", $mainData, $content);
            } 
            // Si no se tiene permiso, se envía un error
            else 
            {
                    PwFunciones::setLogError(4,  $modulo);

                    $errorTemp = $this->getTemplate("noView");
                    $msg = PwFunciones::getErrorMessage(4);
                    //. " <a href= 'javascript:history.go(-1)' class = 'itemLink'>#_LBACK_#</a>"

                    $errorTemp = preg_replace("/__MSG__/", $msg, $errorTemp);
                    $errorTemp = preg_replace("/__NAME__/",  $modulo, $errorTemp);
                    $content = preg_replace("/__CONTENT__/", $errorTemp, $content);
            }
        } // Si no existe fisicamente, mandamos error
        else {
            PwFunciones::setLogError(2,  $modulo . ".php");
            $content = preg_replace("/__CONTENT__/", PwFunciones::getErrorMessage(6) . " <a href= 'javascript:history.go(-1)' class = 'itemLink'>#_LBACK_#</a>", $content);
            $content = preg_replace("/__CLASE__/",  $modulo, $content);
        }
        
        $var = PwSecurity::encryptVariable(1, "", "Inicio");
        $content = preg_replace("/__INICIO__/", rawurlencode($var), $content);
        $content = preg_replace("/__SITENAME__/", SITENAME, $content);

        //$var = PwSecurity::encryptVariable(1, "", "Logout");
        //$content = preg_replace("/__LOGOUT__/", rawurlencode($var), $content);
        
        
        //$content = preg_replace("/__HEADER__/", $titleName, $content);
        
        
        
        $footer = $this->getFooter();
        $content = preg_replace("/__FOOTER__/", $footer, $content);
        
        // Ya que tenemos todo el código, reemplazamos las etiquetas de idioma
        //$content = $this->getLangLabels($content);
        
        return $content;
    }



    private function getAvatar()
    {

      
        $avatar = $this->getTemplate("defaultAvatar");
        
        // Vamos por la imagen del avatar del sistema
        $fileName = "defaultAvatar.jpg";
        $avatarAux = $this->getTemplate("userAvatar");
        if (isset($_SESSION["imagen"]) && $_SESSION["imagen"]) {
            
            $fileName = PwSecurity::decryptVariable(2, "imagen");
        }
        $cveUsuario = PwSecurity::decryptVariable(2, "cveUsuario");
        
        $avatarAux = preg_replace("/__FILENAME__/", $fileName, $avatarAux);
        $avatarAux = preg_replace("/__CVEUSUARIO__/",  $cveUsuario, $avatarAux);
        
        if (is_file(trim($avatarAux))) 
        {
            $avatar = $avatarAux;
        }
      
        return $avatar;
    }


    
    private function getFooter()
    { 
      
      $data =  file_get_contents('template/footer.html', true);
      $year = date("Y");
      $data = preg_replace("/__YEAR__/", $year, $data);
      
      return $data;
      
    }
    
    /**
     * Función para cambiar las etiquetas de idiomas que se encuentran en el código
     * busca este formato para reemplazar #_XXXX_#
     *
     * @param String $content  Toda la cadena html que regresa la página
     * @return String $content La misma variable pero ya reemplazado
     */
    private function getLangLabels($content)
    {

        //return "$content";
        $matches = null;
        $ptn = "/#_[a-zA-Z0-9_]*_#?/";
        preg_match_all($ptn, $content, $matches, PREG_PATTERN_ORDER);
        
        if ($matches) {
            $matches = $matches[0];
            foreach ($matches as $match) {
                $match = preg_replace(array(
                    "/#_/",
                    "/_#/"
                ), "", $match);
                $label = $this->mainObj->label[$match];
                $content = preg_replace("/#_" . $match . "_#/", $label, $content);
                if (! $label || $label = "") {
                    PwFunciones::setLogError(7, "$match :: Idioma: $_SESSION[lang]");
                }
            }
        }
        return $content;
    }

    /**
     * Función para cargar códigos extra de cada módulo
     * Ejemplo codigos de JQuery o Js, pueden cargarse como elemento de la función getTemplate
     * o como un archivo js o html en src/extras , tendrá el nombre asignado por módulo
     *
     * @param $module String
     *            Nombre del módulo al que se le carga la información extra
     * @return String $data Regresa el código extra que se necesita en el módulo
     *        
     */
    private function getExtras($connection, $module)
    {
        $data = "";
        $data = array(
            "CSSGLOBAL" => "",
            "CSSIMPLEMENTING" => "",
            "CSSCUSTOM" => "", 
            "JSGLOBAL" => "",
            "JSIMPLEMENTING" => "",
            "JSCUSTOM" => "",
            "INIT" => ""
          
        );
        $consulta = "SELECT UT.CSS_GLOBAL, UT.CSS_IMPLEMENTING, UT.CSS_CUSTOM,
                    UT.JS_GLOBAL, UT.JS_IMPLEMENTING, UT.JS_CUSTOM, UT.INIT
                     FROM FC_SYS_LIB AS UT, FC_SYS_DETALLE_MODULOS AS DE
                     WHERE UT.ID = DE.ID_LIB AND DE.ID_CLASE =  ? AND UT.SITIO = ? order by UT.ORDEN ";

        if(DBASE == 2)
        {
            $consulta = "SELECT UT.CSS_GLOBAL, UT.CSS_IMPLEMENTING, UT.CSS_CUSTOM,
                    UT.JS_GLOBAL, UT.JS_IMPLEMENTING, UT.JS_CUSTOM, UT.INIT
                     FROM FC_SYS_LIB  UT, FC_SYS_DETALLE_MODULOS  DE
                     WHERE UT.ID = DE.ID_LIB AND DE.ID_CLASE =  ? AND UT.SITIO = ?  order by UT.ORDEN";
        }
        
        $ps = PwSql::setSimpleQuery($connection, $consulta);
        $params = array($module,SITETYPE);
      //  PwFunciones::getVardumpLog($params);
        $sqlResults = PwSql::executeSimpleQuery($ps, $params, $consulta, null, false, false, false);
        
        
        if ($sqlResults) {
            foreach ($sqlResults as $sqlItem) {

                $data["CSSGLOBAL"] .= rawurldecode($sqlItem["CSS_GLOBAL"]);
                $data["CSSIMPLEMENTING"] .= rawurldecode($sqlItem["CSS_IMPLEMENTING"]);
                $data["CSSCUSTOM"] .= rawurldecode($sqlItem["CSS_CUSTOM"]);
                $data["JSGLOBAL"] .= rawurldecode($sqlItem["JS_GLOBAL"]);
                $data["JSIMPLEMENTING"] .= rawurldecode($sqlItem["JS_IMPLEMENTING"]);
                $data["JSCUSTOM"] .= rawurldecode($sqlItem["JS_CUSTOM"]);
                $data["INIT"] .= rawurldecode($sqlItem["INIT"]);

            }
        }        
        return $data;
    }

    /**
     * Función que carga librerias en JS o JQuery que necesiten los módulos
     * Para que las pueda cargar deben de darse de alta en el la pantalla de Sistemas->Scripts
     * Y deben de estar asignadas en la pantalla de Módulos->AsignaScripts
     *
     * @param String $module
     *            Nombre del módulo en ejecución
     * @return string
     */
    /*
     * private function getExtras($module)
     * {
     *
     * $data = "";
     * $consulta = "SELECT UT.DATOS, UT.NOMBRE
     * FROM SYS_UTILS AS UT, SYS_DETALLE_MODULOS AS DE
     * WHERE UT.CVE_UTIL = DE.CVE_UTIL AND DE.CLASE = ? ";
     *
     * $ps = $this->mainObj->sql->setSimpleQuery ( $this->mainObj->connection, $consulta );
     * $params = array($module);
     * $sqlResultsAux = $this->mainObj->sql->executeSimpleQuery ( $ps, $params, $consulta, null, false, true );
     * if($sqlResultsAux)
     * {
     * foreach ($sqlResultsAux as $dataResultsItem)
     * {
     * $dataResultsItem = $this->getArrayObject($this->mainObj->conId, $dataResultsItem );
     *
     * $data .= trim($dataResultsItem["DATOS"]);
     *
     * }
     * }
     *
     * return $data;
     * }
     */
    
    /**
     * Función que regresa un array con los nombres de los módulos o clases
     * que requieren que se cargue la función onLoad() en el <body> del main
     * El indice es el nombre del módulo, el parámetro es el template a cargar
     */
    /*
     * private function getOnLoadModules()
     * {
     * $modules = array ();
     * return $modules;
     * }
     */
    private function getSideBarMenu($connection, $modulo)
    {           

        $data =   file_get_contents('template/sideBar.html', true);

        // Traemos los menus a desplegar
        $condition = array(
            "STATUS" => 1
        );
        $fields = array(
            "ID",
            "DESC_MENU",
            "LOGO_MENU"
        );
        $order = array(
            "DESC_MENU"
        );
        $tabla = "FC_SYS_MENU";
        
        $subMenuNum = 1;
        
        $sqlResults = PwSql::executeQuery($connection, $tabla, $fields, $condition, $order);
      
        $menuBox = file_get_contents('template/menuBox.html', true);
        $menuItemsContent = file_get_contents('template/menuItemsContent.html', true);
        $menuItem = file_get_contents('template/menuItem.html', true);
        $menuData = "";        
        if ($sqlResults) {
            
            $consulta = "  SELECT MC.CVE_MENU, MC.ID, MC.NOMBRE_CLASE , MC.CLASE, MC.ICON
      			FROM FC_SYS_MODULOS MC, FC_SYS_DETALLE_PERFIL DP
      			WHERE
      			MC.CVE_MENU = ?
      			AND MC.STATUS = ?
      			AND MC.PADRE = ?
      			AND MC.CLASE = DP.CLASE
      			AND DP.CVE_PERFIL = ?  			
      			AND DP.VISUALIZAR = ?  			
      			ORDER BY MC.ORDEN";
            
            $ps = PwSql::setSimpleQuery($connection, $consulta);
            
            $menuItemsContentAux = "";
            foreach ($sqlResults as $sqlItem) 
            {
                
                // Traemos los elementos del menu
                $activeMenuGlobal = $sqlItem["ID"];
                $params = array($sqlItem["ID"],1,0,$this->cvePerfil,1);
                
                $sqlResultsAux = PwSql::executeSimpleQuery($ps, $params, $consulta, null, false, false, false);
                
                $menuItems = "";
                if (! $sqlResultsAux) {
                    
                    continue;
                } else {
                    
                    $menuItemsContentTemp = $menuItemsContent;
                    $activeMenuItem = "";
                    $open = "";
                    foreach ($sqlResultsAux as $sqlItemAux) {
                        
                        //$sqlItemAux = $this->getArrayObject($this->mainObj->conId, $sqlItemAux);
                        $menuItemAux = $menuItem;
                        $strClass = $sqlItemAux["CLASE"];
                        $strClass = PwSecurity::encryptVariable(1, "", "$strClass");                        
                        $menuItemAux = preg_replace("/__CLASSNAME__/", rawurlencode($strClass), $menuItemAux);
                        $menuItemAux = preg_replace("/__NOMBRE__/", $sqlItemAux["NOMBRE_CLASE"], $menuItemAux);
                        //$menuItemAux = preg_replace("/__ICON__/", $sqlItemAux["ICON"] ? $sqlItemAux["ICON"] : "-angle-double-right", $menuItemAux);
                        $active = "";

                       
                        if ($modulo == $sqlItemAux["CLASE"]) {
                            $active = " active ";
                            $activeMenuItem = $sqlItemAux["CVE_MENU"];                           
                        }
                        $menuItemAux = preg_replace("/__ACTIVE__/", $active, $menuItemAux);
                        
                        $menuItems .= $menuItemAux;
                    }
                    $menuItemsContentAux = $menuItemsContent;
                    $menuItemsContentAux = preg_replace("/__ITEMS__/", $menuItems, $menuItemsContentAux);
                }
                $menuBoxAux = $menuBox;
                $menuBoxAux = preg_replace("/__NOMBRE__/", $sqlItem["DESC_MENU"], $menuBoxAux);
                $menuBoxAux = preg_replace("/__ICON__/", $sqlItem["LOGO_MENU"] != "" ? $sqlItem["LOGO_MENU"] : "fa-copy", $menuBoxAux);
                $menuBoxAux = preg_replace("/__ITEMS__/", $menuItemsContentAux, $menuBoxAux);
                $active = "";

                $open = "";
                if ($activeMenuGlobal == $activeMenuItem) {
                    $active = "active";
                    $open = "menu-open";
                }
                
                $menuBoxAux = preg_replace("/__ACTIVE__/", $active, $menuBoxAux);
                $menuBoxAux = preg_replace("/__OPEN__/", $open, $menuBoxAux);
                
                $menuData .= $menuBoxAux;
                $subMenuNum++;
            }
        }
        $data = preg_replace("/__ITEMS__/", $menuData, $data);
        return $data;
    }

    /**
     * Función encargada de contener el código html usado en la clase
     *
     * @param String $name
     *            Nombre del elemento html a buscar
     */
    private function getTemplate($name)
    {        
        $template["header"] = <<< TEMP
<section class="content-header">
	<h1>
		__TITLE__
	</h1>    
</section>
  	
TEMP;

$template["noView"] = <<< TEMP
<div class="row">
  <div class="col-md-12" style = "padding:60px;">

<!-- Border Alert -->
    <div class="alert fade show g-brd-around g-brd-gray-light-v3 rounded-0" role="alert">
      <button type="button" class="close u-alert-close--light g-ml-10 g-mt-1" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <div class="media">
        <div class="d-flex g-mr-10">
          <span class="u-icon-v3 u-icon-size--sm g-bg-lightred g-color-white g-rounded-50x">
            <i class="fa fa-exclamation-circle"></i>
          </span>
        </div>
        <div class="media-body">
          <div class="d-flex justify-content-between">
            <p class="m-0"><strong>__NAME__</strong>
            </p>            
          </div>
          <p class="m-0 g-font-size-14">__MSG__</p>
        </div>
      </div>
    </div>
    <!-- End Border Alert -->


  </div>
</div>

    
TEMP;


     
        
        $template["defaultAvatar"] = <<< TEMP
pw/imagenes/defaultAvatar.jpg
TEMP;
        
        $template["userAvatar"] = <<< TEMP
repository/usuarios/__CVEUSUARIO__/thumbs40x40/__FILENAME__
TEMP;
    
        
        $template["moduleStyle"] = <<< TEMP
<link href="css/system/__STYLENAME__Style.css" rel="stylesheet" type="text/css" />
TEMP;
     
        
        $template["jsModule"] = <<< TEMP
<script src="src/js/__JSFILE__" type="text/javascript"></script>
TEMP;
        
    
        
        return $template[$name];
    }
}
?>