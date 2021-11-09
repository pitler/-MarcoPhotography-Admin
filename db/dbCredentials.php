<?php


define("BDCONNECTIONS", serialize(array(
    //Mysql
    1 => array(
        "type" => "pdo_mysql",
       "username" => 'marcobph_dbUser',
        "password" => 'g!XL!Le7S#LP#fbk',
        "host" => 'localhost',
        "db" => "marcobph_photography"

    ),

    //Oracle
    2 => array(
        "type" => "PDO_OCI",
        "username" => '',
        "password" => '',
        "host" => '',
        "db" => ""
    ),
    
    //Sqlserver
    4 => array(
        "type" => "pdo_sqlsrv",
        "username" => '',
        "password" => '',
        "host" => '',
        "db" => ""
    )
   
   )));