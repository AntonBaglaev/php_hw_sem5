<?php

namespace Geekbrains\Application1\Application;

class Auth{

    public static function getPasswordHash(string $rawPassword): string {
        return password_hash($rawPassword, PASSWORD_BCRYPT);
    }

    public function proceedAuth(string $login, string $password): bool{
        $sql = "SELECT id_user, user_name, user_lastname, user_password_hash FROM users WHERE user_login = :login";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['login' => $login]);
        $result = $handler->fetchAll();

        if(!empty($result) && password_verify($password, $result[0]['user_password_hash'])){
            $_SESSION['auth']['user_name'] = $result[0]['user_name'];
            $_SESSION['auth']['user_lastname'] = $result[0]['user_lastname'];
            $_SESSION['auth']['id_user'] = $result[0]['id_user'];

            return true;
        }
        else {
            return false;
        }
    }
}