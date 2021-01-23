<?php
session_start();

require("./var_config.php");

$conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database;port=$mysql_port;charset=utf8", $mysql_username, $mysql_password);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$query = $conn->prepare("SELECT token, id, totp_key FROM comptes WHERE username=:user");
$query->execute([
    ":user" => $_SESSION["username"]
]);

$rslt = $query->fetch();

if(strip_tags($_SESSION["token"])  != $rslt["token"] || !isset($_SESSION["token"])){
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
    <title>Affichage numérique | <?php echo $_SESSION["username"]; ?> (édition)</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
</head>
<body>
        <script>
            function check(){
                if(window.confirm("Vous-êtes sur ?")) window.location.href='./edit_back.php?deleteaccount';
            }
        </script>
    <div class="panel">
        <div class="btn" onclick="window.location.href='./panel.php';">
            Retour
        </div>
        <div class="btn" onclick="check()">
            Supprimer mon compte
        </div>

        <h1>Compte: <?php echo $_SESSION["username"]; ?></h1>
        <form action="login.php" method="POST">
            <input type="hidden" name="deconnect" value="true"/>
            <input type="submit" value="Déconnexion">
        </form>


            
            <form class="edit" action="edit_back.php" method="post">
                <?php echo isset($_GET["msg"]) ? "<h1>".$_GET["msg"]."</h1>" : "<h1>Sécurité</h1>" ?>
                <input type="password" placeholder="Ancien mot de passe" name="password0"/>
                <input type="password" placeholder="Mot de passe" name="password1"/>
                <input type="password" placeholder="Répéter le mot de passe" name="password2"/>
                <input type="submit" value="Changer">
            </form>

            <table>
                <tr><th>Côté utilisateur</th></tr>
                <tr><td>- 20 caractères minimum</td></tr>
                <tr><td>- 2 chiffres minimum</td></tr>
                <tr><td>- 2 majuscules minimum</td></tr>
                <tr><td>- 2 minuscules minimum</td></tr>
                <tr><td>- <a href="https://support.google.com/chrome/answer/7570435?hl=fr&co=GENIE.Platform%3DDesktop">Si vous utilisez Google Chrome</a></td></tr>
                
                <tr><th>Côté serveur</th></tr>
                <tr><td>- mots de passes hashés (je ne peux pas les lire)</td></tr>
                <tr><td>- code sensible traité de ce côté (mots de passes traité par le serveur)</td></tr>
                <tr><td>- Un token est enregistré dans les cookies pour reconnaître votre navigateur, s'il n'est plus valable. il est supprimé et vous êtes déconnectés</td></tr>
                <tr><td>- Une fois supprimés, les étiquettes ou les comptes sont perdus à jamais, sauf si quelqu'un d'autre les a copié</td></tr>

                <tr><th>Recommandations</th></tr>
                <tr><td>- Ne pas divulger d'informations sensibles</td></tr>
                <tr><td>- Le risque zéro n'existe pas (même si au fond ce n'est pas une banque)</td></tr>


            </table>
           
    </div>

</body>
</html>