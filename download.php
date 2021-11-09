<?php

include_once 'src/lib/funciones.php';
include_once 'src/lib/moduleSecurity.php';
include_once ("src/lib/config.php");

session_start ();

if (! isset ( $_SESSION ["autentified"] ))
{
    error_log ( "No se ha iniciado sesión para download.php" );
    die ();
}
//Si estamos activos en la sesión mandamos a ejecutar, si no envia error
$download = new download ();
$download->getFile ();

/**
 * 
 * Clase para forzar la descarga proveniente de un modelo de jQuery Ajax
 * @author pcalzada
 *
 */
class download 
{
    /**
     * 
     * Función que se encarga de descargar el archivo
     * Llamamos metodos de funciones y moduleSecurity sin ser instanciadas las clases
     */
    public function getFile()
    {
        $funciones = "funciones";
        $data = rawurldecode ( $funciones::getGVariable ( "data" ) );
        
        if ($data)
        {
            $security = "moduleSecurity";
            $data = $security::decryptVariable ( 1, $data );
            $data = explode ( "||", $data );
            
            if (is_array ( $data ) && sizeof ( $data ) >= 2)
            {
                $nuevoArchivo = $data [0];
                $nombreArchivoBajar = $data [1];
                //quito espacios a arhcivos
                $nombreArchivoBajar = preg_replace("/ /", "", $nombreArchivoBajar);
                
                header ( "Content-Disposition:attachment;filename=$nombreArchivoBajar");
                header ( "Content-Type:application/force-download" );
                header ( "Content-Length:".filesize ( $nuevoArchivo ) );
                readfile ( $nuevoArchivo );
            }
            else
            {
                error_log ( "Los parámetros enviados para la descarga son incorrectos" );
            }
        }
        else
        {
            error_log ( "No existen parámetros para la descarga" );
            return "";
        }
    }
}
?>