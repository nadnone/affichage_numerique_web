<?php
session_start();

require("../config/var_config.php");


if(!empty($_POST["deconnect"]) && isset($_POST["deconnect"])){

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


        $query = $conn->prepare("SELECT password FROM comptes WHERE username=:user");
        $query->execute(array(
            ":user" => $username
        ));

        if (($hash = $query->fetch()) != false){


            if(password_verify($password, $hash["password"])){

                // connexion sans TOTP
               
                $token = bin2hex(random_bytes(512));

                $_SESSION["username"] = $username;
                $_SESSION["token"] = $token;

                $query = $conn->prepare("UPDATE comptes SET token=:token WHERE username=:user");
                $query->execute(array(
                    ":token" => $token,
                    ":user"  => $username
                ));              
                
                
                
                //redirection panel
                header("Location: ../front/panel.php");

            }
            else{
                header("Location: ../index.php?msg=R%C3%A9essayez");
            }


        }
        else{
            header("Location: ../index.php?msg=R%C3%A9essayez");
        }

    }
    catch(PDOException $e){
        // à changer pour la production
        header("Location: ../index.php?msg=Erreur+server");
    }
    

    
}
else{
    header("Location: ../index.php?msg=Champ+manquant%0D%0A");
}









?>