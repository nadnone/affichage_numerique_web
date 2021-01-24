<?php
session_start();

require("./var_config.php");

$conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database;port=$mysql_port;", $mysql_username, $mysql_password);

$query = $conn->prepare("SELECT token, id FROM comptes WHERE username=:user");
$query->execute([
    ":user" => strip_tags($_SESSION["username"])
]);

$rslt = $query->fetch();

if(strip_tags($_SESSION["token"])  != $rslt["token"] || !isset($_SESSION["token"])){
    session_destroy();
    header("Location: ./index.php");
    return;
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Affichage numérique | <?php echo $_SESSION["username"]; ?></title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
</head>
<body>
        
    <div class="panel">

        <h1>Bienvenue, <?php echo $_SESSION["username"]; ?></h1>
        <form action="login.php" method="POST">
            <input type="hidden" name="deconnect" value="true"/>
            <input type="submit" value="Déconnexion">
        </form>
        <div class="btn" onclick="window.location.href='./edit.php';">
            Sécurité
        </div>
        <h3><?php echo  isset($_GET["msg"]) ? $_GET["msg"] : ""; ?></h3>
        <div class="table">
            

            <script>
                function check_mail(){

                    if(window.confirm("Si vous validez, cette action va envoyer un mail à toute la liste des comptes, continuer ?")){
                        document.getElementById("post_label").submit();
                    }
                }
            </script>


            <form action="my_label.php" id="post_label" class="mylabel" method="post">
                <textarea maxlength="500" placeholder="Blablabla (500 caractères max)" name="mylabel"></textarea>
                <input type="hidden" name="action" value="add"/>
                <input type="button" onclick="check_mail()" value="Envoyer"/>
            </form>

            <?php
            try{                
                
                $query = $conn->prepare("SELECT * FROM labels");
                $query->execute();
                $labels = array_reverse($query->fetchAll());

                if(!$labels) return;

                foreach($labels as $label){

                    $label_str = strip_tags($label["label"]);
                    $label_id  = $label["id"];
                    $label_userID = $label["user_id"];

                    echo "<div class='row'><p>$label_str</p>";

                    if($label_userID == $rslt["id"]){
                        echo <<<EOT
                        <form method="post" action="my_label.php" >
                        <input type="hidden" name="id" value="$label_id"/>
                        <input type="hidden" name="action" value="delete"/>
                        <input type="submit" value="Supprimer"/>
                        </form>
                        EOT;
                    }

                    echo "</div>";
                   
                                }
            }
            catch(PDOException $e){
                //echo $e;
                header("Location: ./panel.php?msg=Erreur+serveur");
            }
              

            ?>
   

        </div>

    </div>

</body>
</html>