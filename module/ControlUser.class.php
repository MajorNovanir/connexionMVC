<?php
class ControlUser
{

    private $login;
    private $password;
    private $mail;
    private $function;

    private function data_to_array($success, $message, $login, $mail, $function, $errors)
    { //fonction de stockage de valeurs dans un array qu'on va utiliser pour retourner les erreurs et la maintenance des inputs
        $data = [
            "success" => $success,
            "message" => $message,
            "inputs" => [
                "login" => $login,
                "mail" => $mail,
                "function" => $function
            ],
            "errors" => $errors
        ];
        return $data;
    }
    private function control_field($input, $inputname, $regex, $key)
    { //fonction de contrôle de champ avec champ vide et regex,retourne erreurs
        $errors = [];
        try {
            if (empty($input)) {
                throw new Exception('Le champ ' . $inputname . ' est vide.');
            } else {
                if (!preg_match($regex, $input)) {
                    throw new Exception('Le champ ' . $inputname . ' ne convient pas.');
                }
            }
        } catch (Exception $e) {
            $errors[$key] = $e->getMessage();
        }
        return $errors;
    }
    private function control_addForm_fields($login, $password, $mail, $function)
    { //fonction de contrôle de FORM ADD avec incrémentations des erreurs dans un tableau, retourne le tableau.
        $this->login = $login;
        $this->password = $password;
        $this->mail = $mail;
        $this->function = $function;

        $loginRegex = '/^[\p{L}\s]{3,30}$/u';
        $passwordRegex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$@%*+\-_!])[\w$@%*+\-_!]{6,}$/';
        $mailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        $errors = [];//appel de la fonction control fields sur tout les inputs+incrémentation tableau d'erreur
        $errors = array_merge($errors, $this->control_field($this->login, "Utilisateur", $loginRegex, "login"));
        $errors = array_merge($errors, $this->control_field($this->password, "Mot de passe", $passwordRegex, "password"));
        $errors = array_merge($errors, $this->control_field($this->mail, "Email", $mailRegex, "mail"));
        $errors = array_merge($errors, $this->control_field($this->function, "Fonction", $loginRegex, "function"));
        return $errors;
    }

    private function control_connectForm_fields($login, $password, $mail)
    { //fonction de contrôle de FORM CONNECT avec incrémentations des erreurs dans un tableau, retourne le tableau.
        $this->login = $login; //liage des valeurs
        $this->password = $password;
        $this->mail = $mail;

        $loginRegex = '/^[\p{L}\s]{3,30}$/u';
        $passwordRegex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$@%*+\-_!])[\w$@%*+\-_!]{6,}$/';
        $mailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        $errors = [];
        $errors = array_merge($errors, $this->control_field($this->login, "Utilisateur", $loginRegex, "login"));
        $errors = array_merge($errors, $this->control_field($this->password, "Mot de passe", $passwordRegex, "password"));
        $errors = array_merge($errors, $this->control_field($this->mail, "Email", $mailRegex, "mail"));
        return $errors;
    }





    public function add_new_user($login, $password, $mail, $function)
    { //fonction principale d'ajout d'utilisateur dans la base de donnée
        $errors = $this->control_addForm_fields($login, $password, $mail, $function); //on stocke le tableau retourné par la fonction de contrôle dans une variable
        if (empty($errors)) { //si le tableau d'erreur est vide.
            $user = new User; //on apelle la classe User
            $twins = $user->check_existing_email_login($mail, $login); //on apelle la fonction pour check dans la bdd si le mail ou login existe déjà
            if ($twins === true) { //si twins retourne un true
                $user = $user->insert_user($login, $password, $mail, $function); //on apelle la fonction d'insert 
                if ($user) { //si l'utilisateur a bien été INSERE
                    $data = $this->data_to_array(true, "L'utilisateur a été ajouté, vous pouvez vous connecter!", $login, $mail, "", $errors); //retour des infos
                    require "./templates/connect_user.php"; //retour au form de connection avec login et mail
                } else { //si l'insert c'est mal passé
                    $data = $this->data_to_array(false, "L'utilisateur n'a pas pu être ajouté, veuillez réessayer", $login, $mail, $function, $errors); //retour des infos
                    require "./templates/add_user.php"; //retour au form add avec les infos d'inputs+erreurs
                }
            } else { //si twins ressort avec un message d'erreur(qu'il y a déjà un mail ou login correspondant dans la bdd)
                $data = $this->data_to_array(false, $twins, $login, $mail, $function, $errors);
                require "./templates/add_user.php"; //retour au form add avec infos inputs+erreurs
            }
        } else { //si il y a des erreurs dans le form
            $data = $this->data_to_array(false, "Il y a des erreurs dans le formulaire", $login, $mail, $function, $errors);
            require "./templates/add_user.php"; //retour au form add avec infos inputs+erreurs
        }
        return $data; //retour des datas pour les exploiter
    }

    public function connect_user($login, $password, $mail)
    { //fonction principale de connexion d'utilisateur dans la base de donnée
        $errors = $this->control_connectForm_fields($login, $password, $mail); //on stocke le tableau retourné par la fonction de contrôle dans une variable
        if (empty($errors)) { //si le tableau d'erreur est vide.
            $user = new User; //on apelle la classe User
            $check = $user->check_pwd_and_user($mail, $login, $password); //on apelle la fonction pour check dans la bdd si le mail ou login correspond+ CHECK du mail

            if ($check === true) { //si la fonction ressort un true
                $user = $user->get_user_by_login($login); // on apelle une fonction pour récupérer les infos de l'utilisateur

                if ($user !== false) { //si la fonction retourne un tableau
                    session_start(); //ouverture de sesssion
                    $key = bin2hex(random_bytes(32));// clé secrète pour le cryptage de l'id de session
                    $iv = openssl_random_pseudo_bytes(16); //clé d'initialisation
                    $session_id = session_id();//stockage de l'id de la session dans une variable
                    $encrypted_session_id = openssl_encrypt($session_id, 'AES-256-CBC', $key, 0, $iv);//cryptage de l'id avec les deux clés
                    setcookie('session_id', $encrypted_session_id, time() + 3600, '/', '', true, true);//Création d'un cookie comprenant l'id cryptée
                    $_SESSION["id"] = $encrypted_session_id;//création d'une session comprenant l'id cryptée qu'on comparera avec le cookie pour voir si l'utilisateur est connecté

                    $data = $this->data_to_array(true, "Vous êtes connecté!", $user["login_user"], $user["email_user"], $user["fonction"], $errors); //récupération des infos utilisateur
                    $data = serialize($data);//stringification du tableau d'info
                    setcookie('data', $data, time() + 3600, '/', '', true, true);//stockage des infos dans un cookie

                    header('location:./index.php?action=home');//retour à l'index à l'index avec l'action home

                } else { //si la récup des infos utilisateur est false
                    $data = $this->data_to_array(false, "Impossible de récupérer les informations de compte!", $login, $mail, "", $errors);
                    require "./templates/connect_user.php"; //retour au form connect avec erreurs+ valeurs inputs
                }
            } else { //si la fonction de check ne trouve pas d'utilisateur correspondant
                $data = $this->data_to_array(false, $check, $login, $mail, "", $errors);
                require "./templates/connect_user.php"; //retour au form connect
            }
        } else { //si il y a des erreurs dans le form
            $data = $this->data_to_array(false, "Il y a des erreurs dans le formulaire", $login, $mail, "", $errors);
            require "./templates/connect_user.php"; //retour au form Connect avec erreurs+valeurs inputs
        }
        return $data;
    }

    public function session_id_check()
    {//fonction de check d'id de session qui sécurise l'accès aux pages membre
        if ($_COOKIE['session_id'] == $_SESSION["id"]) {//si les deux id encryptés sont égaux
            $data = unserialize($_COOKIE["data"]);//déstringification de l'array data qu'on va utiliser dans l'espace membre
            require "templates/home.php";//appel de la vue home
        } else {//si les deux id ne sont pas égaux
            $data["message"] = "problème de session";//erreur + retour à connection
            require "templates/connect_user.php";
        }
        return null;
    }

    public function disconnect()
    {
        if (isset($_SESSION)) { //si les variables de session existent
            unset($_SESSION);//unset des variables
        }
        if (isset($_COOKIE['session_id'])) { //si la variable de cookie id existe
            unset($_COOKIE['session_id']);//unset de la variable
            setcookie('session_id', null, -1, '/');  //redéfinition du cookie à null
        }
        if (isset($_COOKIE['data'])) { //si la variable de cookie data existe
            unset($_COOKIE['data']); //unset de la variable 
            setcookie('data', null, -1, '/');//redéfinition du cookie à null
        }
        session_destroy(); //destroy de la session
        $data["message"] = "Vous êtes déconnecté!";

        require "templates/connect_user.php"; //retour au form CONNECT
        return null;
    }

}
?>