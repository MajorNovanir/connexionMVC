<?php
require "module/User.class.php"; // REQUIRE des classes nécessaire à l'éxécution du code
require "module/ControlUser.class.php";
require "module/Database.class.php";
session_start();
if (!isset($_GET["action"])) { //si il n'y a pas d'action
    if (isset($_COOKIE['data'])) {//si les cookies de connexion existent, on l'envoie à la page home avec tout de même les vérifications
        header("location:index.php?action=home");
    } else {//si pas de cookie
        require "templates/connect_user.php";//affichage form connexion
    }
}
if (isset($_GET["action"])) {
    $controller = new ControlUser;//appel de la class controller

    if ($_GET["action"] === "addform") { //si action == addform au clic du bouton créer un compte
        require "templates/add_user.php"; //affichage du form ADD
    }

    if ($_GET["action"] === "add") { //si action == add au submit du form ADD
        if (isset($_POST["login"]) && isset($_POST["password"]) && isset($_POST["mail"]) && isset($_POST["function"])) { //si les variables post existent
            $data = $controller->add_new_user($_POST["login"], $_POST["password"], $_POST["mail"], $_POST["function"]); //appel de la fonction d'ajout de la classe controller
        }
    }

    if ($_GET["action"] === "connect") { //si action == connect au submit du form CONNECT
        if (isset($_POST["login"]) && isset($_POST["password"]) && isset($_POST["mail"])) { //si les variables post existent
            $data = $controller->connect_user($_POST["login"], $_POST["password"], $_POST["mail"]); //appel de la fonction connexion de la classe controller
        }
    }

    if ($_GET["action"] === "home") { //si action == home  
        if (!empty($_COOKIE['data'])) { //si cookie[data] pas vide  (doit être passé par la fonction de connexion)
            $controller = $controller->session_id_check(); //check de l'id de session + passage à home   
        } else {//si il essaie de gruger l'action dans l'url sans cookie
            header("location:index.php"); //retour à l'index vanilla
        }
    }
    if ($_GET["action"] === "disconnect") { //si action == disconnect  
        if (!empty($_COOKIE['data'])) { //si cookie[data] pas vide  (doit être passé par la fonction de connexion)
            $controller = $controller->disconnect(); //destruction des variables sessions et cookie et renvoi à la connexion
        } else { //si il essaie de gruger l'action dans l'url sans cookie
            header("location:index.php"); //retour à l'index vanilla
        }
    }
}
?>