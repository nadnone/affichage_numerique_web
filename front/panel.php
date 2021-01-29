<?php
session_start();

require("../config/var_config.php");

require("../misc/tocken_check.php");


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Affichage numérique | <?php echo $_SESSION["username"]; ?></title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
</head>
<body>
        
    <div class="panel">

        <h1>Bienvenue, <?php echo $_SESSION["username"]; ?></h1>
        <form action="../back/login.php" method="POST">
            <input type="hidden" name="deconnect" value="true"/>
            <input type="submit" value="Déconnexion">
        </form>
        <div class="btn" onclick="window.location.href='./edit.php';">
            Sécurité
        </div>
        <div class="btn" onclick="window.location.href='../front/dl_data.php';">
            Télécharger mes données
        </div>
        <div class="btn" onclick="window.location.href='../front/legal.html';">
            Mentions légales
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


            <form action="../back/my_label.php" id="post_label" class="mylabel" method="post">
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

                $color = 0;

                $color_0 = "#27ae60";
                $color_1 = "#9b59b6";
                $color_2 = "#f39c12";

                foreach($labels as $label){

                    $label_str = strip_tags($label["label"]);
                    $label_id  = $label["id"];
                    $label_userID = $label["user_id"];


                    if($label_userID == $rslt["id"]){

                        switch($color){
                            case 0:
                                echo "<div style='background-color: $color_0;' class='row'><p>$label_str</p>";
                                $color++;
                                break;
                            case 1:
                                echo "<div style='background-color: $color_1;' class='row'><p>$label_str</p>";
                                $color++;
                                break;
                            case 2:
                                echo "<div style='background-color: $color_2;' class='row'><p>$label_str</p>";
                                $color = 0;
                                break;

                        }


                        echo <<<EOT
                        <form method="post" action="../back/my_label.php">
                        <input type="hidden" name="id" value="$label_id"/>
                        <input type="hidden" name="action" value="delete"/>
                        <input type="submit" value="Supprimer"/>
                        </form>
                        EOT;
                        
                    echo "</div>";
                    
                    }
                    else{

                        switch($color){
                            case 0:
                                echo "<div style='background-color: $color_0;' class='row'><p>$label_str</p>";
                                $color++;
                                break;
                            case 1:
                                echo "<div style='background-color: $color_1;' class='row'><p>$label_str</p>";
                                $color++;
                                break;
                            case 2:
                                echo "<div style='background-color: $color_2;' class='row'><p>$label_str</p>";
                                $color = 0;
                                break;

                        }
                        
                    echo "</div>";
                    }

                   
                }
            }
            catch(PDOException $e){
                log_append_error("Erreur:\n\n$e");
                header("Location: panel.php?msg=Erreur+serveur");
            }
              

            ?>
   

        </div>

    </div>

</body>
</html>