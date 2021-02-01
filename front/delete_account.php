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
    <title>Suppression de compte</title>
    <?php require("../misc/antibot.php"); ?>
</head>
<body>
    <div class="btn" onclick="window.history.back();"">
        Retour
    </div>
    <h1><?php if(isset($_GET["msg"])){ echo strip_tags($_GET["msg"]); } else { echo "Merci de confirmer votre mot de passe pour le compte"; }?></h1>
    <!--<h2>Vous allez recevoir un fichier compressé zip protegé par le mot de passe de votre connexion</h2>-->
    <form class="box" method="post" action="../back/edit_back.php?deleteaccount">
    <h2>Utilisateur: <?php echo $_SESSION["username"]; ?></h2>

    <input type="password" name="password" value="Mot de passe">
    <input type="submit" value="Confirmer">
    </form>
</body>
</html>
