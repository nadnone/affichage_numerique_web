<?php 
session_start();
require("../config/var_config.php");

require("../misc/tocken_check.php");

require("../misc/log_func.php");

if($_SESSION["username"] == $test_account_name && $test_account){ header("Location: ../front/panel.php?msg=Vous+ne+pouvez+pas+modifier+le+compte+de+teste"); return; }


try{
    $password = strip_tags($_POST["password"]);


    $query = $conn->prepare("SELECT * FROM comptes WHERE username=:user");
    $query->execute([
        ":user" => strip_tags($_SESSION["username"])
    ]);

    $bdd = $query->fetch();

    if(password_verify($password, $bdd["password"]) && $bdd["time_lock"] == null){




        // on récupère les données


        $username = $bdd["username"];
        $password_hash = $bdd["password"];
        $token = $bdd["token"];
        $tentatives = $bdd["mdp_tentative"];
        $temps_blocage = $bdd["time_lock"];
        $email = $bdd["email"];
        $user_id = $bdd["id"];


        $compte = "Données de sécurité du compte:
Utilisateur: $username
Mot de passe hashé: $password_hash
Token actuel: $token
Tentatives de connexions depuis la dernière connexion: $tentatives
temps de blocages du compte: $temps_blocage
Adresse email: $email
Votre identifiant: $user_id
";




        $query = $conn->prepare("SELECT id, label FROM labels WHERE user_id=:user_id");
        $query->execute([
            ":user_id" => $user_id
        ]);

        $bdd = $query->fetchAll();
        
        $data = "Etiquettes publiés:\n\n";

        foreach($bdd as $label){
            $id = $label["id"];
            $label_data = $label["label"];

            $data .= "######################################
Etiquette id: $id
Contenu:

$label_data

######################################


";
        }
        $zip = new ZipArchive();

        $filepath = $data_zip_path_folder."/$username-$email-personnal_data.zip";

        if($zip->open($filepath, ZipArchive::CREATE)){
            


                $zip->addFromString("comptes.txt", $compte);
                $zip->addFromString("etiquettes.txt", $data);
                
                // logs security

                $patern = "/$username/";
                $log = explode( "\n", file_get_contents("$log_file_path/panneau_affichage_security.log"));
                $data = "";
                foreach($log as $line){
                    if(preg_match($patern, $line))
                    {
                        $data .= $line . "\n";
                    }
                }

                $zip->addFromString("security_log.txt", $data);

                // log activity

                $log = explode( "### END OF LOG ACTIVITY ###", file_get_contents("$log_file_path/panneau_affichage_activity.log"));
                $data = "";
                foreach($log as $line){
                    if(preg_match($patern, $line))
                    {
                        $data .= $line . "### END OF LOG ACTIVITY ###";
                    }
                }

                $zip->addFromString("activity_log.txt", $data);


                //$zip_password = bin2hex(random_bytes((15)));
        
                //$zip->setPassword($zip_password);
                
                //$filepath = $data_zip_path_folder."/$username-$email-personnal_data_protected.zip";

                //$zip->setEncryptionName($filepath, ZipArchive::EM_AES_256);
                $zip->close();                

        
                $data = addslashes(file_get_contents($filepath));

                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: Binary");    
                header("Content-Length: ".filesize($filepath)); 
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header("Content-disposition: attachment;filename=\"".basename($filepath)."\"");

                while (ob_get_level()) 
                {
                 ob_end_clean();
                 }
                readfile($filepath);   
                // suppression du fichier zip
                unlink($filepath);



                log_append_security("$username -> Données personnelles téléchargées.");
                exit;
                ob_start ();

               

        }
        else{
            log_append_error("$username -> WARNING Création du zip echoué pour l'utilisateur");
            header("Location: ../front/dl_data.php?msg=Erreur+syst%C3%A8me");
        }


    
    }
    else{
        log_append_security("$username -> WARNING tentative de téléchargement de données personnelles, mot de passe incorrect ou compte bloqué.");
        session_destroy();
        header("Location: ../index.php?msg=Mot+de+passe+incorrect%2C+reconnexion");
    }

}catch(Error $e){
    log_append_error("WARNING Erreur:\n\n$e");
    header("Location: ../front/dl_data.php?msg=Erreur+syst%C3%A8me");
}
?>