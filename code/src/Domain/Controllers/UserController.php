<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;
use Geekbrains\Application1\Application\Auth;



class UserController extends AbstractController {

    protected array $actionsPermissions = [
        'actionHash' => ['admin'],
        'actionSave' => ['admin'],
        'actionEdit' => ['admin'],
        'actionIndex' => ['admin', 'guest'],
        'actionLogout' => ['admin'],

        'actionCreate' => ['admin'],
        'actionDelete' => ['admin'],
        'actionUpdate' => ['admin']
    ];

    public function actionIndex(){
        $users = User::getAllUsersFromStorage();
        $render = new Render();
        if(!$users){
            return $render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список пуст или не найден"
                ]);
        }
        else{
            return $render->renderPage(
                'user-index.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users
                ]);
        }
    }

    public function actionSave(): string {
        if(User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();

            $render = new Render();

            return $render->renderPage(
                'user-created.twig',
                [
                    'title' => 'Пользователь создан',
                    'message' => "Создан пользователь " . $user->getUserName() . " " . $user->getUserLastName()
                ]);
        }


        else {
            throw new Exception("Переданные данные некорректны");
        }
    }

    public function actionDelete(): string {
        if(User::exists($_GET['id'])) {
            User::deleteFromStorage($_GET['id']);

            header('Location: /user');
            die();

        }
        else {
            throw new \Exception("Пользователь не существует");
        }
    }

    public function actionEdit(): string {
        $render = new Render();


        $action = '/user/save';
        if(isset($_GET['id'])){
            $userId = $_GET['id'];
            $action = '/user/update';
            $userData = User::getUserDataByID($userId);

        }

        return $render->renderPageWithForm(
            'user-form.twig',
            [
                'title' => 'Форма создания пользователя',
                'user_data'=> $userData ?? [],
                'action' => $action
            ]);
    }

    public function actionUpdate(): string {
        if(User::exists($_POST['id'])) {
            $user = new User();
            $user->setUserId($_POST['id']);

            $arrayData = [];

            if(isset($_POST['name']))
                $arrayData['user_name'] = $_POST['name'];

            if(isset($_POST['lastname'])) {
                $arrayData['user_lastname'] = $_POST['lastname'];
            }

            $user->updateUser($arrayData);
        }
        else {
            throw new \Exception("Пользователь не существует");
        }

        $render = new Render();
        return $render->renderPage(
            'user-created.twig',
            [
                'title' => 'Пользователь обновлен',
                'message' => "Обновлен пользователь " . $user->getUserId()
            ]);
    }

    public function actionAuth(): string {
        $render = new Render();
        return $render->renderPageWithForm(
            'user-auth.twig',
            [
                'title' => 'Форма логина'
            ]);
    }

    public function actionHash(): string {
        return Auth::getPasswordHash($_GET['pass_string']);
    }

    public function actionLogin() : string {
        $result = false;

        if(isset($_POST['login']) && isset($_POST['password'])){
            $result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
            if($result &&
                isset($_POST['user-remember']) && $_POST['user-remember'] == 'remember'){
                $token = Application::$auth->generateToken($_SESSION['auth']['id_user']);

                User::setToken($_SESSION['auth']['id_user'], $token);
            }
        }

        if(!$result){
            $render = new Render();

            return $render->renderPageWithForm(
                'user-auth.twig',
                [
                    'title' => 'Форма логина',
                    'auth-success' => false,
                    'auth_error' => 'Неверные логин или пароль'
                ]);
        }
        else{
            header('Location: /');
            return "";
        }
    }

    public function actionLogout(): void {
        User::destroyToken();
        session_destroy();
        unset($_SESSION['auth']);
        header("Location: /");
        die();
    }

}