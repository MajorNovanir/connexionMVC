<?php
$title = "Ajouter un utilisateur";
ob_start();
//FORM ADD HTML Inscription des messages erreurs+inputs si ils existent RAS
?>

<h1>
    <?= $title ?>
</h1>
<form id="addform" action="index.php?action=add" method="post">
    <h2 id="message">
        <?=(isset($data)) ? $data["message"] : '' ?>
    </h2>
    <label for="login">
        <p>Utilisateur :</p>
        <input type="text" name="login" id="login" value="<?=(isset($data)) ? $data["inputs"]["login"] : '' ?>">
        <p class="error" id="loginError">
            <?= isset($data["errors"]["login"]) ? $data["errors"]["login"] : '' ?>
        </p>
    </label>
    <label for="password">
        <p>Mot de passe :</p>
        <input type="password" name="password" id="password">
        <p class="error" id="passwordError">
            <?= isset($data["errors"]["password"]) ? $data["errors"]["password"] : '' ?>
        </p>
    </label>
    <label for="mail">
        <p>Email :</p>
        <input type="text" name="mail" id="mail" value="<?=(isset($data)) ? $data["inputs"]["mail"] : '' ?>">
        <p class="error" id="mailError">
            <?= isset($data["errors"]["mail"]) ? $data["errors"]["mail"] : '' ?>
        </p>
    </label>
    <label for="function">
        <p>Fonction :</p>
        <input type="text" name="function" id="function"
            value="<?=(isset($data)) ? $data["inputs"]["function"] : '' ?>">
        <p class="error" id="functionError">
            <?= isset($data["errors"]["function"]) ? $data["errors"]["function"] : '' ?>
        </p>
    </label>
    <div id="buttons">
        <button id="submit" type="submit">Valider</button>
        <button id="reset" type="reset">Annuler</button>
    </div>
    <button type="button"><a href="./index.php">Retour à l'accueil</a></button>
</form>
<?php
$content = ob_get_clean();
include "baselayout.php";
?>