<?php
session_start();
require("./config/var_config.php");

// si déjà connecté
if(isset($_SESSION["username"]) && isset($_SESSION["token"])){
    header("Location: front/panel.php");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage numérique</title>
    <link rel="stylesheet" href="style.css">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
</head>
<body>
    
        <?php

            $query = $conn->prepare("SELECT COUNT(*) FROM comptes;");
            $query->execute();

            $nb_comptes = (String)($query->fetch()["COUNT(*)"]-1); // moins le compte de test

        
        ?>

        <form action="./back/login.php" method="post" class="box">
            <?php echo isset($_GET["msg"]) ?  "<h1>". $_GET["msg"] . "</h1>": " <h1>Panneau numérique</h1>"; ?>
            
            <?php
                if ($nb_comptes > 2) echo "<h2>Déjà $nb_comptes membres (: </h2>";
            ?>
            
            
            <input type="text" name="username" placeholder="username">
            <input type="password" name="password" placeholder="password">

            <input type="submit" value="Connexion" placeholder="password">
        
        </form>

        <div class="footer">
            Si vous connaissez les languages utilisés ou que vous avez des idées, vous pouvez proposer des améliorations.<br/>
            <a href="https://github.com/spoutnik911/affichage_numerique_web" target="_blank">Code source / Propositions</a><br/>
            <a href="./front/legal.html">Mentions légales</a><br/>
        </div>


</body>
</html>