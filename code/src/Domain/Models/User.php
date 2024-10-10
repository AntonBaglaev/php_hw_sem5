<?php

namespace Geekbrains\Application1\Domain\Models;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Auth;
use Geekbrains\Application1\Infrastructure\Storage;

class User {

    private ?int $id_user;
    private ?string $userLogin;
    private ?string $userName;
    private ?string $userLastName;
    private ?int $userBirthday;
    private ?string $userPasswordHash;
    private ?string $userRole;


    public function __construct(
        int $id = null,
        string $login = null,
        string $name = null,
        string $lastName = null,
        int $birthday = null,
        string $passwordHash = null,
        string $role = null){
        $this->id_user = $id;
        $this->userLogin = $login;
        $this->userName = $name;
        $this->userLastName = $lastName;
        $this->userBirthday = $birthday;
        $this->userPasswordHash = $passwordHash;
        $this->userRole = $role;
    }

    public function setUserId(int $id_user): void {
        $this->id_user = $id_user;
    }

    public function getUserId(): ?int {
        return $this->id_user;
    }

    public function setUserLogin(int $userLogin): void {
        $this->userLogin = $userLogin;
    }

    public function getUserLogin(): string {
        return $this->userLogin;
    }

    public function setName(string $userName) : void {
        $this->userName = $userName;
    }

    public function setLastName(string $userLastName) : void {
        $this->userLastName = $userLastName;
    }

    public function getUserName(): string {
        return $this->userName;
    }

    public function getUserLastName(): string {
        return $this->userLastName;
    }

    public function getUserBirthday(): int {
        return $this->userBirthday;
    }

    public function setBirthdayFromString(string $birthdayString) : void {
        $this->userBirthday = strtotime($birthdayString);
    }

    public function setPasswordHash(string $userPasswordHash) : void {
        $this->userPasswordHash = $userPasswordHash;
    }

    public function getUserPasswordHash(): string {
        return $this->userPasswordHash;
    }

    public function setUserRole(string $userRole) : void {
        $this->userRole = $userRole;
    }

    public function getUserRole(): string {
        return $this->userRole;
    }

    public static function getAllUsersFromStorage(): array|false {
        $sql = "SELECT * FROM users";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute();
        $result = $handler->fetchAll();
        $users = [];
        foreach($result as $item){
            $user = new User(
                $item['id_user'],
                $item['user_login'],
                $item['user_name'],
                $item['user_lastname'],
                $item['user_birthday_timestamp'],
                $item['user_password_hash'],
                $item['user_role']);
            $users[] = $user;
        }
        return $users;
    }

    public static function validateRequestData(): bool{
        $result = true;

        if(!(
            isset($_POST['name']) && !empty($_POST['name']) &&
            isset($_POST['lastname']) && !empty($_POST['lastname']) &&
            isset($_POST['birthday']) && !empty($_POST['birthday'])
        )){
            $result = false;
        }

        if(preg_match('/<([^>]+)>/', $_POST['name']) || preg_match('/<([^>]+)>/', $_POST['lastname'])){
            $result =  false;
        }

        if(!preg_match('/^(\d{2}-\d{2}-\d{4})$/', $_POST['birthday'])){
            $result =  false;
        }

        if(!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $_POST['csrf_token']){
            $result = false;
        }

        return $result;
    }

    public function setParamsFromRequestData(): void {
        if ($_POST['id'] != '') {
            $this->id_user = htmlspecialchars($_POST['id']);
        }
        $this->userLogin = htmlspecialchars($_POST['login']);
        $this->userName = htmlspecialchars($_POST['name']);
        $this->userLastName = htmlspecialchars($_POST['lastname']);
        $this->setBirthdayFromString($_POST['birthday']);
        $this->userPasswordHash = Auth::getPasswordHash($_POST['password']);
        $this->userRole = "guest";
    }

    public static function getUserDataByID(int $userID): array {
        $userSql = "SELECT * FROM users WHERE id_user = :id";


        $handler = Application::$storage->get()->prepare($userSql);
        $handler->execute(['id' => $userID]);
        return $handler->fetch();
    }

    public static function exists(int $id): bool{
        $sql = "SELECT count(id_user) as user_count FROM users WHERE id_user = :id_user";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute([
            'id_user' => $id
        ]);

        $result = $handler->fetchAll();

        if(count($result) > 0 && $result[0]['user_count'] > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function updateUser(array $userDataArray): void{
        $sql = "UPDATE users SET ";

        $counter = 0;
        foreach($userDataArray as $key => $value) {
            $sql .= $key ." = :".$key;

            if($counter != count($userDataArray)-1) {
                $sql .= ",";
            }

            $counter++;
        }
        $sql .= " WHERE id_user = " . $this->getUserId();


        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute($userDataArray);
    }

    public static function destroyToken(): array {
        $userSql = "UPDATE users SET user_password_hash = :user_password_hash WHERE id_user = :id";

        $handler = Application::$storage->get()->prepare($userSql);
        //$handler->execute(['user_password_hash' => md5(bin2hex(random_bytes(16))), 'id => $_SESSION['auth']['id_user']]);
        $result = $handler->fetchAll();

        return $result[0] ?? [];
    }

    public static function verifyToken(string $token) : array {
        $userSql = "SELECT * FROM users where user_password_hash = :user_password_hash";

        $handler = Application::$storage->get()->prepare($userSql);
        $handler->execute(['user_password_hash' => $token]);
        $result = $handler->fetchAll();

        return $result[0] ?? [];
    }

    public static function setToken(int $userID, string $token): void {
        $userSql = "UPDATE users SET user_password_hash = :user_password_hash WHERE id_user = :id";

        $handler = Application::$storage->get()->prepare($userSql);
        $handler->execute(['id' => $userID, 'user_password_hash' => $token]);

        setcookie('auth_token', $token, time() + 60*60*24*30, '/');
    }

    public static function deleteFromStorage(int $user_id) : void {
        $sql = "DELETE FROM users WHERE id_user = :id_user";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['id_user' => $user_id]);
    }

    public function saveToStorage(): void
    {
        $sql = "INSERT INTO users(user_login, user_name, user_lastname, user_birthday_timestamp, user_password_hash, user_role) VALUES (:user_login, :user_name, :user_lastname, :user_birthday, :user_password_hash, :user_role)";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute([
            'user_login' => $this->userLogin,
            'user_name' => $this->userName,
            'user_lastname' => $this->userLastName,
            'user_birthday' => $this->userBirthday,
            'user_password_hash' => $this->userPasswordHash,
            'user_role' => $this->userRole
        ]);
    }

}