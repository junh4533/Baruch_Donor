<?php
    //log user action with timestamp
    date_default_timezone_set("EST"); 
    $datetime = date("M d, Y h:i:s A", time()); 
    $log = "[" . $datetime . "] " . "[" . $log_user . "] " . $log_message;

    fwrite($log_file, "\n". $log);
    fclose($log_file); 
?>