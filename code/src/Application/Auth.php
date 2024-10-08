<?php

namespace Geekbrains\Application1\Application;

class Auth {

    public static function getPasswordHash(string $rawPassword): string {
        return password_hash($rawPassword, PASSWORD_BCRYPT);
    }

    public function proceedAuth(string $login, string $password): bool{
        $sql = "SELECT id_user, user_name, user_lastname, user_password_hash FROM users WHERE user_login = :login";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['login' => $login]);
        $result = $handler->fetchAll();
        $success = !empty($result) && password_verify($password, $result[0]['user_password_hash']);
        if($success){
            $_SESSION['user_name'] = $result[0]['user_name'];
            $_SESSION['user_lastname'] = $result[0]['user_lastname'];
            $_SESSION['id_user'] = $result[0]['id_user'];
        }
        return $success;
    }

    public static function addSessionData(array $templateVariables) : array {
        if (isset($_SESSION['user_name'])) {
            $templateVariables['islogin'] = true;
            $templateVariables['username'] = $_SESSION['user_name'] . ' ' . $_SESSION['user_lastname'];
        }
        return $templateVariables;
    }
}