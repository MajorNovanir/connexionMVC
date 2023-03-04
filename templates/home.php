<?php
$title = "Home";
ob_start();
//page d'acceuil qui suit la connection inscription des infos User avec les variables de session
?>
<div id="userInfo">
    <h2>Informations utilisateur</h2>
    <h3>Login : <?=$data["inputs"]["login"]?></h3>
    <p>Email : <?=$data["inputs"]["mail"]?></p>
    <p>Fonction : <?=$data["inputs"]["function"]?></p>
</div>
<div id="wtf">
    <h1>Vous êtes connecté!</h1>
    <div class="img"><img src="src/img/logo1.png" alt=""></div>
</div>
<button id="disconnect" type="button"><a href="./index.php?action=disconnect">Déconnexion</a></button>
<?php
$content = ob_get_clean();
include "baselayout.php";
?>