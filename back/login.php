<?php
session_start();

require("../config/var_config.php");
require("../misc/log_func.php");


if(!empty($_POST["deconnect"]) && isset($_POST["deconnect"])){
 log_append_security($_SESSION["username"] . " s'est déconnecté");
 session_destroy();
 header("Location: ../index.php?msg=Vous+%C3%AAtes+bien+d%C3%A9connect%C3%A9%2C+reconnexion+%3F");
 return;
}


// si déjà connecté
if(isset($_SESSION["username"]) && isset($_SESSION["token"])){
    header("Location: ../front/panel.php");
}


// connexion
if(!empty($_POST["username"]) && !empty($_POST["password"]) && isset($_POST["username"]) && isset($_POST["password"])){

    $username = strip_tags($_POST["username"]);
    $password = strip_tags($_POST["password"]);

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try{


        $query = $conn->prepare("SELECT password, time_lock FROM comptes WHERE username=:user");
        $query->execute(array(
            ":user" => $username
        ));

        $data = $query->fetch();

        $user_time_lock = strtotime($data["time_lock"]); // timestamp for when the account will be unlock

        if(($hash = $data["password"]) != false && (time() > $user_time_lock) || $data["time_lock"] == null){


            if(password_verify($password, $hash)){

                // connexion sans TOTP
               
                $token = bin2hex(random_bytes(512));

                $_SESSION["username"] = $username;
                
                if($username == $test_account_name && $test_account) $token = "guest";

                $query = $conn->prepare("UPDATE comptes SET token=:token , mdp_tentative='0', time_lock=NULL WHERE username=:user");
                $query->execute([
                    ":token" => $token,
                    ":user"  => $username,
                ]);     

                $_SESSION["token"] = $token;                    
                
                
                
                //redirection panel
                log_append_security($_SESSION["username"] . " -> connexion réussie");
                header("Location: ../front/panel.php");


            }
            else{

                $query = $conn->prepare("SELECT mdp_tentative FROM comptes WHERE username=:user");
                $query->execute([
                    ":user" => $username
                ]);

                $tentative = $query->fetch()["mdp_tentative"];
                $tentative++;
                
                $query = $conn->prepare("UPDATE comptes SET mdp_tentative=:tentative WHERE username=:user");
                $query->execute([
                    ":user" => $username,
                    ":tentative" => $tentative
                ]);
                
                if($tentative > $max_connect_try){

                    $query = $conn->prepare("UPDATE comptes SET time_lock=:time_lock_Start , mdp_tentative='0' WHERE username=:user");
                    $query->execute([
                        ":user" => $username,
                        ":time_lock_Start" => date("Y-m-d H:i:s", time()+$time_lock_account)
                    ]);

                    log_append_security($username . " -> WARNING tentative numéro $tentative de connexion échoué. compte temporairement bloqué");
                    header("Location: ../index.php?msg=Trop+de+tentatives+de+connexion%2C+compte+temporairement+bloqu%C3%A9");
                    return;
                } 


                log_append_security($username . " -> tentative numéro $tentative de connexion échoué.");
                header("Location: ../index.php?msg=R%C3%A9essayez");
            }


        }
        elseif(time() < $user_time_lock){
            log_append_security($username . " -> WARNING connexion échoué. compte temporairement bloqué jusqu'à ".date("d/m/Y H:i:s", $user_time_lock));
            header("Location: ../index.php?msg=Trop+de+tentatives+de+connexion%2C+compte+temporairement+bloqu%C3%A9+pendant+30+minutes");
        }
        else{
            header("Location: ../index.php?msg=Ce+compte+n%27existe+pas");
        }

    }
    catch(Error $e){
        log_append_security($username . " -> tentative de connexion échoué. (erreur système)");
        log_append_error("Connection échoué erreur:\n\n$e");
        header("Location: ../index.php?msg=Erreur+server");
    }
    

    
}
else{
    header("Location: ../index.php?msg=Champ+manquant%0D%0A");
}









?>