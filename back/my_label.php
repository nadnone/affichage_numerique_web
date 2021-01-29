<?php
session_start();

require("../config/var_config.php");

require("../misc/tocken_check.php");

require("../misc/mail_init.php");

require("../misc/log_func.php");

if($_SESSION["username"] == $test_account_name && $test_account){ header("Location: ../front/panel.php?msg=Vous+ne+pouvez+pas+modifier+le+compte+de+teste"); return; }


if(isset($_POST["action"])){

    try{
        switch($_POST["action"]){


            case "delete":
                $query = $conn->prepare("DELETE FROM labels WHERE user_id=:user_id AND id=:id;");
                $query->execute([
                    ":user_id" => $rslt["id"],
                    ":id" => strip_tags($_POST["id"])
                ]);
                log_append_activity($_SESSION["username"] . " vient de supprimer une étiquette");
                header("Location: ../front/panel.php");
                break;

            case "add":
                if(!empty($_POST["mylabel"]) && isset($_POST["mylabel"])){

                    $query = $conn->prepare("INSERT INTO labels(label, user_id) VALUES(:label, :user_id);");
                    $query->execute([
                        ":user_id" => $rslt["id"],
                        ":label" => strip_tags($_POST["mylabel"])
                    ]);

                    
                    $query = $conn->prepare("SELECT email, username FROM comptes;");
                    $query->execute();

                    $mails = $query->fetchAll();

                    try{

                            foreach($mails as $mail){

                                if($mail["username"] != $_SESSION["username"])
                                {
            
                                    $to = $mail["email"];

                                    if($to != NULL)
                                    {


                                        //Recipients
                                        $mailer->setFrom($mail_server_user);
                                        $mailer->addAddress($to);

                                        // Content
                                        $mailer->isHTML(true);                                  // Set email format to HTML
                                        $mailer->Subject = 'Information immeuble WebApp';
                                        $mailer->Body    = '
                                            Hey '.$mail["username"].', quelqu\'un a ajouté une étiquette.
                                            <p>'.strip_tags($_POST["mylabel"]).'</p>
                    
                                            <footer>Ce mail est automatique, merci de ne pas y répondre. Pour vous désinscrire, vous devez supprimer votre compte à l\'adresse '.$_SERVER["SERVER_NAME"].'/affichage'.'</footer>
                                        ';
                                        $mailer->AltBody = 'Hey '.$mail["username"].', 
                                        quelqu\'un a ajouté une étiquette.

                                        Ce mail est automatique, merci de ne pas y répondre. Pour vous désinscrire, vous devez supprimer votre compte à l\'adresse <a href="'.$_SERVER["SERVER_NAME"].'/affichage">'.$_SERVER["SERVER_NAME"].'</a>
                                        ';




                                        $mailer->send();
                                    }
            
                                    
                                }

                            }
                            log_append_activity($_SESSION["username"] . " vient d'envoyer l'étiquette:\n\"" . strip_tags($_POST["mylabel"]). "\"");
                            header("Location: ../front/panel.php?msg=Etiquette+envoy%C3%A9e");


                    }
                    catch(Exception $e){
                        log_append_error("Tentative d'envoi de mail échoué, erreur\n\n$e");
                        header("Location: ../index.php?msg=Erreur+serveur");
                    }
                                    
                }
                break;
        
            default:
                break;
        }
        

    }
    catch(PDOException $e){
        log_append_error("Erreur:\n\n$e");
        header("Location: ../index.php?msg=Erreur+serveur");
    }

}

return;









?>