<?php

class User
{//class User comprenant les requêtes SQL
    private $login;
    private $password;
    private $mail;
    private $function;


    private function sanitize_string($str) {//fonction pour laver les strings avant de les envoyer en bdd
        $str = trim($str);
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        return $str;
    }

    public function insert_user($login, $password, $mail, $function)
    {//fonction d'insert RAS CLASSIQUE

        $password = password_hash($password, PASSWORD_BCRYPT);//cryptage du pwd
        $login = ucfirst(strtolower($login));

        $this->login = $this->sanitize_string($login) ; //liage des valeurs
        $this->password = $this->sanitize_string($password);
        $this->mail =$this->sanitize_string($mail);
        $this->function = $this->sanitize_string($function);

        $pdo = Database::connect(); //connection à la DB
        $sql = "INSERT INTO utilisateurs(email_user, login_user, pwd_user, fonction) VALUES (:mail, :login, :pwd, :function)"; //requête SQL permettant l'insert
        $response = $pdo->prepare($sql); //préparation de la requête
        $response->bindParam(':mail', $this->mail, PDO::PARAM_STR);
        $response->bindParam(':login', $this->login, PDO::PARAM_STR);
        $response->bindParam(':pwd', $this->password);
        $response->bindParam(':function', $this->function, PDO::PARAM_STR);
        if ($response->execute()) {
            return true;
        }
        return false;
    }



    public function check_existing_email_login($mail, $login)
    {//fonction pour check si le login ou le mail existe déjà
        $login = ucfirst(strtolower($login));

        $this->login = $this->sanitize_string($login);
        $this->mail = $this->sanitize_string($mail);

        $pdo = Database::connect();
        $sql = "SELECT * FROM utilisateurs WHERE email_user = :mail OR login_user = :login";
        $response = $pdo->prepare($sql);
        $response->bindParam(':mail', $this->mail, PDO::PARAM_STR);
        $response->bindParam(':login', $this->login, PDO::PARAM_STR);
        $response->execute();
        $result = $response->fetch();
        if ($result) {// si il y a des doublons
            if ($result['email_user'] == $this->mail) {//si c'est le même mail
                return "L'email existe déjà.";//retourne erreur
            }
            if ($result['login_user'] == $this->login) {//si c'est le même login
                return "L'utilisateur existe déjà.";//retourne erreur
            }
        } else {//si il n'y a pas de doublon
            return true;//retour true
        }
    }

    public function check_pwd_and_user($mail, $login, $password)
    {//fonction pour check si le login ou le mail correspondent + check password
        $login = ucfirst(strtolower($login));

        $this->password = $this->sanitize_string($password);
        $this->login = $this->sanitize_string($login);
        $this->mail = $this->sanitize_string($mail);

        $pdo = Database::connect();
        $sql = "SELECT * FROM utilisateurs WHERE login_user = :login AND email_user = :mail";
        $response = $pdo->prepare($sql);
        $response->bindParam(':login', $this->login, PDO::PARAM_STR);
        $response->bindParam(':mail', $this->mail, PDO::PARAM_STR);
        $response->execute();
        $user = $response->fetch();

        if ($user) {//si il y a une correspondance
            if (password_verify($this->password, $user["pwd_user"])) {//check password
                return true;//connection reussie, retour true
            } else {
                return "Mauvais mot de passe!";//sinon mauvais mot de passe
            }
        } else {//si il n'y a pas de correspondance
            return "Aucun utilisateur trouvé, vérifiez votre login et votre email";
        }
    }
    public function get_user_by_login($login)
    {//fonction de récupération d'info user classique RAS
        $login = ucfirst(strtolower($login));

        $this->login = $this->sanitize_string($login);

        $pdo = Database::connect();
        $sql = "SELECT * FROM utilisateurs WHERE login_user = :login";
        $response = $pdo->prepare($sql);
        $response->bindParam(':login', $this->login, PDO::PARAM_STR);
        $response->execute();
        $user = $response->fetch();
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
}
?>