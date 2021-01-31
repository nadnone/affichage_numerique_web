<?php
session_start();

require("../config/var_config.php");

require("../misc/tocken_check.php");

require("../misc/log_func.php");


if($_SESSION["username"] == $test_account_name && $test_account){ header("Location: ../front/edit.php?msg=Vous+ne+pouvez+pas+modifier+le+compte+de+teste"); return; }

elseif(isset($_GET["deleteaccount"]) && isset($_POST["password"])){
        try{


            $query = $conn->prepare("SELECT password, time_lock FROM comptes WHERE username=:user");
            $query->execute([
                ":user" => $_SESSION["username"]
            ]);
            
            $bdd = $query->fetch();

            if(!password_verify(strip_tags($_POST["password"]), $bdd["password"]) && $bdd["time_lock"] == null){
                session_destroy();
                header("Location: ../index.php?msg=Mot+de+passe+incorrect%2C+reconnexion"); 
                log_append_security($_SESSION["username"] . "-> WARNING tentative de suppression de compte échoué, mot de passe incorrect ou compte bloqué.");
                return;
            }




            $ancien_id = $rslt["id"];
            $query = $conn->prepare("DELETE FROM labels WHERE user_id=:id");
            $query->execute([
                ":id" => $rslt["id"]
            ]);
    
            $query = $conn->prepare("DELETE FROM comptes WHERE id=:id");
            $query->execute([
                ":id" => $rslt["id"]
            ]);
    
            session_destroy();
            log_append_security("Ancien compte avec l'id numéro $ancien_id a été supprimé.");
            header("Location: ../index.php?msg=Compte+supprimé");
        }
        catch(PDOException $e){
            log_append_security("Ancien compte avec l'id numéro $ancien_id n'a pas pu être supprimé. (erreur système)");
            log_append_error("Ancien compte avec l'id numéro $ancien_id n'a pas pu être supprimé. \n\n$e");
            header("Location: ../front/edit.php?msg=Erreur+serveur");
        }
        return;
}
elseif(!empty($_POST["password1"]) && !empty($_POST["password0"]) && $_POST["password1"] == $_POST["password2"] && isset($_POST["password1"]) && isset($_POST["password0"]) && isset($_POST["password2"])){

    $password_striped = strip_tags($_POST["password1"]);

    if(sizeof(str_split($password_striped)) < 12){ header("Location: ../front/edit.php?msg=20+caract%C3%A8res+minimum"); return;}
    if(preg_match_all("/[0-9]/", $password_striped) < 2){ header("Location: ../front/edit.php?msg=2+Chffres+minimum"); return;}
    if(preg_match_all("/[A-Z]/", $password_striped) < 2){ header("Location: ../front/edit.php?msg=2+majuscules+minimum"); return;}
    if(preg_match_all("/[a-z]/", $password_striped) < 2){ header("Location: ../front/edit.php?msg=2+minuscules+minimum"); return;}

    $password = password_hash(strip_tags($password_striped), PASSWORD_DEFAULT);


    try{
        $query = $conn->prepare("SELECT password FROM comptes WHERE username=:user");
        $query->execute([
            ":user" => strip_tags($_SESSION["username"])
        ]);
        if(!password_verify(strip_tags($_POST["password0"]), $query->fetch()["password"])){ 
            log_append_security($_SESSION["username"] . " -> tentative de changement de mot de passe échoué.");
            header("Location: ../front/edit.php?msg=Mot+de+passe+incorrect"); return;
        }
        
        $query = $conn->prepare("UPDATE comptes SET password=:password WHERE username=:user");
        $password = password_hash(strip_tags($password_striped), PASSWORD_DEFAULT);
        $query->execute([
            ":user" => strip_tags($_SESSION["username"]),
            ":password" => $password
        ]);
        session_destroy();
        log_append_security($_SESSION["username"] . " -> le mot de passe a été changé.");
        header("Location: ../index.php?msg=Mot+de+passe+chang%C3%A9%2C+reconnexion");
        return;
    }
    catch(PDOException $e){
        log_append_security($_SESSION["username"] . " -> mot de passe inchangé. (erreur système)");
        log_append_error("Le mot de passe n'a pas pu être changé pour l'utilisateur ".strip_tags($_SESSION["username"])." \n\n$e");
        header("Location: ../index.php?msg=Erreur+server");
    }



}
elseif($_POST["password1"] != $_POST["password2"]){
    header("Location: ../front/edit.php?msg=Les+nouveaux+mots+de+passe+ne+correspondent+pas");
}
else{
    header("Location: ../front/edit.php?msg=Champ+menquant");
}



?>