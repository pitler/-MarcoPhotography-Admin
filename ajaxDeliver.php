<?php
namespace Pitweb;

date_default_timezone_set('America/Mexico_City');

include_once ("config.php");
include_once  PWSYSLIB . 'Funciones.php';
include_once  PWSYSLIB . 'Security.php';
include_once  PWSYSLIB . 'Login.php';
session_start();



use Pitweb\Funciones\Funciones as PwFunciones;

$debug = PwFunciones::getGVariable("debug");

//Mandamos los errores al log
error_reporting(E_ALL);
ini_set('error_log','logs/sysLog.log');

$tiempoInicio =  microtime(true);

use Pitweb\MainController as Mc;
$mainData = Mc::getData();


$tiempoFin = microtime(true);
$tEjecucion = $tiempoFin - $tiempoInicio;


if($debug == 1)
{
    error_log("Ejecucion controller:: $tEjecucion");
}

$mainData = preg_replace("/__EJECUCION__/", $tEjecucion, $mainData);
echo $mainData;


/**
 *
 * Clase encargada de administrar las llamadas a clases por medio de jquery
 * Se cargan todos los objetos nuevamente
 * @author pitler
 *
 */

class MainController
{
    
    /**
     * Objeto para las funciones generales
     * @var ObjFunciones Objeto para las funciones generales
     */
    private $mainObj;
    
    /**
     * Nombre de la clase
     * @var String  - Nombre de la clase
     */
    private $className;
    
    
    //Constructor de la clase
    function __construct ()
    {
        $this->mainObj = new mainVars(DBASE, "");
        $this->className = "ajaxManager";
    }
    
    public function getData()
    {
        
        //Variable para ver si necesitamos desencriptar informacion
        $encrypt = $this->getPVariable("encrypt");
        
        //La clase a la que nos dirigimos
        $clase = rawurldecode($this->getPVariable("class"));
        
        
        if($clase == "login")
        {
            $loginObj = $this->getClass("login", $this->mainObj);
            $mode = $this->getPVariable("mode");
            $mainLogin = $loginObj->getData($mode);
            return $mainLogin;
            
        }
        
        
        //Si necesitamos estar logueados, por lo general es para traer variables encriptadas
        if($encrypt == 2)
        {
            
            $clase = $this->mainObj->security->decryptVariable(1, $clase);
            //!isset($_SESSION["autentified"])
            //  error_log("No paso por logueado");
            
            //$result = json_encode(array("status"=>"false","value"=>"Error"));
            //return $result;
            
            //     return "No paso por sesion";
        }
        
        //Verificamos si existe físicamente, sino mandamos error al log
        if(file_exists(SITEMODELPATH.$clase.".php"))
        {
            //Checamos si tiene permiso, si no mandamos error en el log
            $permiso = $this->mainObj->security->verifyModelAccess($this->mainObj, $clase);
            
            if($permiso == true)
            {
                include_once(SITEMODELPATH.$clase.".php");
                $moduleData = new $clase($this->mainObj);
                $moduleData->getData();
                //por alguna razon concatena un 1
                return true;
            }
            else
            {
                
                $this->setError(55,$clase, __CLASS__, 2);
                echo "Permission";
                return false;
            }
        }
        //Si no existe fisicamente, mandamos error pero solo por medio del log
        else
        {
            $this->setError(54,"Modelo : ".$clase.".php", __CLASS__);
            echo "serverFalse";
            return false;
        }
    }
}
?>

?>