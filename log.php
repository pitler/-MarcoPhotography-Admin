<?php
 
$file = "logs/sysLog.log"; //gammu's sms log
    $data = file($file);

    $end = count($data);
    $first = $end-350;

    $number = range($first,$end);

    foreach($number as $n) {
    	
        $log_data .= $data[$n]."<br>";
    }

    echo $log_data;
 
 ?>