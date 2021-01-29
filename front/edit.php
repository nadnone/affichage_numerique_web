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
    <title>Affichage numérique | <?php echo $_SESSION["username"]; ?> (édition)</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
</head>
<body>
        <script>
            function check(){
                if(window.confirm("Vous-êtes sur ?")) window.location.href='../back/edit_back.php?deleteaccount';
            }
        </script>
    <div class="panel">
        <div class="btn" onclick="window.location.href='../front/panel.php';">
            Retour
        </div>
        <div class="btn" onclick="check()">
            Supprimer mon compte
        </div>
        <div class="btn" onclick="window.location.href='../front/dl_data.php';">
            Télécharger mes données
        </div>
        <div class="btn" onclick="window.open('../front/legal.html', 'blank');"">
            Mentions légales
        </div>

        <h1>Compte: <?php echo $_SESSION["username"]; ?></h1>

            
            <form class="edit" action="../back/edit_back.php" method="post">
                <?php echo isset($_GET["msg"]) ? "<h1>".$_GET["msg"]."</h1>" : "<h1>Sécurité</h1>" ?>
                <input type="password" placeholder="Ancien mot de passe" name="password0"/>
                <input type="password" placeholder="Mot de passe" name="password1"/>
                <input type="password" placeholder="Répéter le mot de passe" name="password2"/>
                <input type="submit" value="Changer">
            </form>

            <table>
                <tr><th>A savoir</th></tr>
                <tr><td>- 12 caractères minimum</td></tr>
                <tr><td>- 2 chiffres minimum</td></tr>
                <tr><td>- 2 majuscules minimum</td></tr>
                <tr><td>- 2 minuscules minimum</td></tr>
                
                <tr><th>Recommandations</th></tr>
                <tr><td>- Ne pas divulger d'informations sensibles</td></tr>
                <tr><td>- Le risque zéro n'existe pas (même si au fond ce n'est pas une banque)</td></tr>
            </table>
           
    </div>

</body>
</html>