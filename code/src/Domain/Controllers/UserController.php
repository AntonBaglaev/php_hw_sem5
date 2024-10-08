<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;
use Geekbrains\Application1\Application\Auth;
use Geekbrains\Application1\Domain\Controllers\PageController;



class UserController extends AbstractController {

    protected array $actionsPermissions = [
        'actionIndex' => ['admin', 'guest'],
        'actionCreate' => ['admin'],
        'actionEdit' => ['admin'],
        'actionDelete' => ['admin'],
        'actionSave' => ['admin'],
        'actionUpdate' => ['admin']
    ];

    protected array $alwaysEnabledMethods = ['actionAuth', 'actionLogin', 'actionLogout'];

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

    public function actionCreate(): string {
        $render = new Render();
        return $render->renderPageWithForm(
            'user-form.twig',
            Auth::addSessionData(
                [
                    'title' => 'Форма создания пользователя',
                    'action' => 'save',
                    'editing' => false
                ]));
    }

    public function actionEdit(): string {
        if(User::exists($_POST['id'])) {
            $render = new Render();
            return $render->renderPageWithForm(
                'user-form.twig',
                Auth::addSessionData(
                    [
                        'title' => 'Форма создания пользователя',
                        'action' => 'update',
                        'editing' => true,
                        'id' => $_POST['id'],
                        'login' => $_POST['login'],
                        'name' => $_POST['name'],
                        'lastname' => $_POST['lastname'],
                        'birthday' => $_POST['birthday'],
                        'password' => $_POST['password']
                    ]));
        }
        else {
            throw new Exception("Пользователь не существует");
        }
    }

    public function actionDelete(): string {
        if(User::exists($_POST['id'])) {
            User::deleteFromStorage($_POST['id']);
            return $this->actionIndex();
        }
        else {
            throw new Exception("Пользователь не существует");
        }
    }

    public function actionSave(): string {
        if(User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();
            return $this->actionIndex();
        }
        else {
            throw new Exception("Переданные данные некорректны");
        }
    }

    public function actionUpdate(): string {
        if(User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->updateInStorage();
            return $this->actionIndex();
        }
        else {
            throw new Exception("Переданные данные некорректны");
        }
    }

    public function actionAuth(): string {
        $render = new Render();
        return $render->renderPageWithForm(
            'user-auth.twig',
            Auth::addSessionData(
                [
                    'title' => 'Форма логина'
                ]));
    }

    public function actionLogin(): string {
        $result = isset($_POST['login'])
            && isset($_POST['password'])
            && Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
        if(!$result){
            $render = new Render();
            return $render->renderPageWithForm(
                'user-auth.twig',
                [
                    'title' => 'Форма логина',
                    'auth-fail' => true,
                    'auth-error' => 'Неверные логин или пароль'
                ]);
        }
        else{
            $controller = new PageController;
            return $controller->actionIndex();
        }
    }

    public function actionLogout(): string {
        session_unset();
        $controller = new PageController;
        return $controller->actionIndex();
    }



    public function actionHash(): string {
        return Auth::getPasswordHash($_GET['pass_string']);
    }



}