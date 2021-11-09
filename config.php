<?php

/**
 * Este archivo sirve para configurar parámetros en el sistema
 * * @author pitler
 */

/**
 * Define el nombre de la página que aparece en el encabezado del navegador Ex :: PitWweb
 * @var String
 */
define('PAGETITLE', " ADMIN MBPhotography");
//Estoy en local machine


/**
 * nombre que aparece en la portada del loguin
 * @var String
 */
define('MAINTITLE', " ADMIN MBPhotography");


/**  
 * Variable para el nombre en el admin Ex : PitWeb
 * @var String Variable para el nombre en el admin
 */
define('SITENAME', "mbf");

/**
 * 
 * Nombre del sistema que se esta cargando Ex : PitWeb
 * @var String  Nombre del sistema
 */
define('SITEID', "mbf");

/**
 * Definimos el tipo de conexión a la base de datos que deseamos usar,
 * 1 .- Para conexiones de mysql
 * 2 .- Para conexiones a oracle
 * 3 .- Para conexiones del javaBridge
 * 4 .- Para conexiones a slqServer
 * 5 .- Para conexiones personalizadas 
 * @var Integer Definimos el tipo de conexión a la base de datos que deseamos usar
 */
define("DBASE", 1);

/**
 * Nos dice en que esquema estamos, si en el sitio o el admin
 * @var unknown
 */
define('SITEMODE', "admin");

/**
 * Versión del sistema en donde stamos trabajando
 */
define('SITEVERSION', "(PW ADMIN DEV)");


/**
 * Tipo del esquema, 1.- Admin, 2.- Site
 * @var unknown
 */

define('SITETYPE', 1);


/*** DEFINIMOS LAS RUTAS PARA EL SISTEMA Y SUS SITIOS ***/

/**
 * Ruta de la raiz de la página
 * @var Ruta de la raiz de la página
 */
define("SITEPATH", dirname(__FILE__).'/');




/**
 * Ruta de la raiz del servidor
 * @var Ruta de la raiz del servidor
 */
define("SYSPATH",dirname(SITEPATH)."/");


/**
 * Ruta de la raiz del servidor donde se encuentra el pitWeb
 * @var Ruta de la raiz del servidor donde se encuentra el pitWeb
 * Se debe de cambiar en produccion
 */
//define("PITWEB", "E:/Sitios/pitweb3/");
define("PITWEB", SYSPATH."pitweb/");


/**
 * Ruta a la carpeta de librerias del sistema
 * @var String Ruta a la carpeta de librerias del sistema
 */
define("PWSYSLIB", PITWEB."lib/");


/**
 * Ruta a la carpeta del repositorio del sistema
 * @var String Ruta a la carpeta del repositorio del sistema
 */
define("PWSREPOSITORY", PITWEB."repository/".SITEID."/");



/**
 * Ruta a la carpeta de utilidades del pitweb
 * @var String Ruta a la carpeta de utilidades del sistema
 */
/*define("PWSYSUTILS", PITWEB."utils/");
*/
/**
 * Ruta a la carpeta de assets del pitweb
 * @var String Ruta a la carpeta de utilidades del sistema
 */
define("PWASSETS", PITWEB."assets/");


/**
 * Ruta a la carpeta de librerias del sitio
 * @var String Ruta a la carpeta de librerias del sitio
 */
//define("SITELIB", SITEPATH."src/lib/");


/**
 * Ruta a la carpeta de las plantillas css
 * @var String Ruta a la carpeta de las plantillas css
 */
define('SITECSSPATH', SITEPATH."css/system/");

/**
 * Ruta a la carpeta de los archivos js para cada modulo
 * @var String Ruta a la carpeta de los archivos js
 */
define('SITEJSPATH', SITEPATH."src/js/");


/**
 * Ruta a la carpeta de los módulos del sistema
 * @var String Ruta a la carpeta de los módulos del sistema
 */
define('SITECLASESPATH', SITEPATH."src/clases/");

/**
 * Ruta a la carpeta de los controladores
 * @var String Ruta a la carpeta de los controladores
 */
define('SITECONTROLLERPATH', SITEPATH."src/controller/");

/**
 * Ruta a la carpeta de los controladores
 * @var String Ruta a la carpeta de los controladores
 */
define('SITEMODELPATH', SITEPATH."src/model/");

/**
 * Ruta a la carpeta de los lenguajes
 * @var String Ruta a la carpeta de los lenguajes
 */
define('SITELANGPATH', SITEPATH."lang/");


/**
 * Ruta a la carpeta de las utilidades
 * @var String Ruta a la carpeta de las utilidades
 */
define('SITEUTILSPATH', SITEPATH."utils/");

/**
 * Nombre de la clase de las funciones locales $mainObj->system
 * @var String Nombre de la clase de las funciones locales $mainObj->system
 */
define('LOCALFUNCTIONS', "adminFunctions");


/**
 * Definimos si la aplicación necesita autenticación
 * 0.- No 
 * 1.- Si
 * @var Integer Definimos si la aplicación necesita autenticación
 */
define ('REQLOGIN', 1);

/**
 * Definimos el lenguaje por default del sistema
 * @var String La abreviatura del idioma
 */
define('DEFAULTLANG', "es");

/**
 * Define si necesitamos usar captcha para la autentificación
 * true.- Si se usa, false.- No se usa
 * @var Boolean
 */
define('CAPTCHA', false);

/**
 * Define si necesitamos debuggear la aplicación
 * Recomendado deshabilitar en producción
 * true.- Si , false.- No
 * @var Boolean
 */
define('SYSDEBUG', true);

/**
 * Definimos la ruta a donde enviara el link del correo
 */
define('ADMINRECOVER', '');


define('EXECUTETIME', true);


/**
 * Definimos la ruta a del domiio donde presentar imagenes
 */
define('URLIMAGE', 'https://marcobphotography.com/repository/');



?>