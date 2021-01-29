<?php

$query = $conn->prepare("SELECT token, id FROM comptes WHERE username=:user");
$query->execute([
    ":user" => $_SESSION["username"]
]);

$rslt = $query->fetch();

if(strip_tags($_SESSION["token"])  != $rslt["token"] || !isset($_SESSION["token"])){
    session_destroy();
    header("Location: ../index.php?msg=Votre+token+n%27est+plus+valide%2C+vous+avez+%C3%A9t%C3%A9+d%C3%A9connect%C3%A9");
    return;
}
?>