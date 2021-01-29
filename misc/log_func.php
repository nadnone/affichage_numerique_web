<?php


function log_append_activity($info){
    
    require("../config/var_config.php");

    try{
        $logfile = fopen($log_file_path."/panneau_affichage_activity.log", "a");
        fwrite($logfile, "[*] INFORMATION: ". date("d/m/Y H:i:s") . "\n" . $info . "\n### END OF LOG ACTIVITY ###\n\n");
        fclose($logfile);
    }
    catch(Exception $e){
        echo "Error when saving log";
    }
    
}

function log_append_security($info){
    
    require("../config/var_config.php");

    try{
        $logfile = fopen($log_file_path."/panneau_affichage_security.log", "a");
        fwrite($logfile, "[*] SECURITY INFORMATION: ". date("d/m/Y H:i:s") ." ". $info . "\n");
        fclose($logfile);
    }
    catch(Exception $e){
        echo "Error when saving log";
    }
    
}

function log_append_error($info){
    
    require("../config/var_config.php");

    try{
        $logfile = fopen($log_file_path."/panneau_affichage_error.log", "a");
        fwrite($logfile, "[!] ERROR: ". date("d/m/Y H:i:s") . "\n" . $info . "\n\n### END OF ERROR ###\n\n");
        fclose($logfile);
    }
    catch(Exception $e){
        echo "Error when saving log";
    }
    
}

?>