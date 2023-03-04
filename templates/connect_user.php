<?php
$title = "Connexion MVC";
ob_start();
//FORM CONNECT HTML Inscription des messages erreurs+inputs si ils existent RAS
?>

<h1>
    <?= $title ?>
</h1>
<div class="form-wrapper">
<form id="connectform" class="form-container" action="index.php?action=connect" method="post">
    <button type="button"><a href="./index.php?action=addform">Créer un compte</a></button>
    <h2 id="message"><?= (isset($data))? $data["message"] : '' ?></h2>
    <label for="login">
        <p>Utilisateur :</p>
        <input type="text" name="login" id="login" value="<?= (isset($data["inputs"]["login"]))? $data["inputs"]["login"] : '' ?>">
        <p class="error" id="loginError"><?= isset($data["errors"]["login"])? $data["errors"]["login"] : '' ?></p>
    </label>
    <label for="mail">
        <p>Email :</p>
        <input type="email" name="mail" id="mail" value="<?= (isset($data["inputs"]["mail"]))? $data["inputs"]["mail"] : '' ?>">
        <p class="error" id="mailError"><?= isset($data["errors"]["mail"])? $data["errors"]["mail"] : '' ?></p>
    </label>
    <label for="password">
        <p>Mot de passe :</p>
        <input type="password" name="password" id="password">
        <p class="error" id="passwordError"><?= isset($data["errors"]["password"])? $data["errors"]["password"] : '' ?></p>
    </label>
    <div id="buttons">
        <button id="submit" type="submit">Valider</button>
        <button id="reset" type="reset">Annuler</button>
    </div>
</form>
</div>
<?php
$content = ob_get_clean();
include "baselayout.php";
?>