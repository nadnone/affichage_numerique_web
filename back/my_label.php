<?php
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require("../PHPMailer/src/Exception.php");
require("../PHPMailer/src/PHPMailer.php");
require("../PHPMailer/src/SMTP.php");

require("../config/var_config.php");


$query = $conn->prepare("SELECT token, email, id FROM comptes WHERE username=:user");
$query->execute([
    ":user" => strip_tags($_SESSION["username"])
]);

$rslt = $query->fetch();

if(strip_tags($_SESSION["token"]) != $rslt["token"]){
    header("Location: ../index.html");
    return;
}
if($_SESSION["username"] == "testeur"){ header("Location: ../front/panel.php?msg=Vous+ne+pouvez+pas+modifier+le+compte+de+teste"); return; }


if(isset($_POST["action"])){

    try{
        switch($_POST["action"]){


            case "delete":
                $query = $conn->prepare("DELETE FROM labels WHERE user_id=:user_id AND id=:id;");
                $query->execute([
                    ":user_id" => $rslt["id"],
                    ":id" => strip_tags($_POST["id"])
                ]);
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


                                        // send mail
                                        $mailer = new PHPMailer(true);

                                        //$mailer->SMTPDebug = 4;
                                        
                                        $mailer->CharSet = "UTF-8";
                                        //Server settings
                                        $mailer->isSMTP();                                            // Send using SMTP
                                        $mailer->Host       = $mail_server_host;                      // Set the SMTP server to send through
                                        $mailer->SMTPAuth   = true;                                   // Enable SMTP authentication
                                        $mailer->Username   = $mail_server_user;                      // SMTP username
                                        $mailer->Password   = $mail_server_password;                  // SMTP password
                                        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                                        $mailer->Port       = $mail_server_SMTP_port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

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
                            header("Location: ../front/panel.php?msg=Etiquette+envoy%C3%A9e");


                    }
                    catch(Exception $e){
                        header("Location: ../index.php?msg=Erreur+serveur");
                    }
                                    
                }
                break;
        
            default:
                break;
        }
        

    }
    catch(PDOException $e){
        header("Location: ../index.php?msg=Erreur+serveur");
    }

}

return;









?>