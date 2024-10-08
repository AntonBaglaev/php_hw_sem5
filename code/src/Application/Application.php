<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Geekbrains\Application1\Domain\Controllers\AbstractController;
use Geekbrains\Application1\Infrastructure\Config;
use Geekbrains\Application1\Infrastructure\Storage;
use Geekbrains\Application1\Application\Auth;

class Application {

    private const APP_NAMESPACE = 'Geekbrains\Application1\Domain\Controllers\\';
    private string $controllerName;
    private string $methodName;
    public static Config $config;
    public static Storage $storage;
    public static Auth $auth;
    public function __construct(){
        Application::$config = new Config();
        Application::$storage = new Storage();
        Application::$auth = new Auth();

    }

    public function run() : string {
        session_start();
        $this->getControllerName();
        if(class_exists($this->controllerName)){
            $this->getMethodName();
            if(method_exists($this->controllerName, $this->methodName)){
                $controllerInstance = new $this->controllerName();
                $this->checkPermissions($controllerInstance, $this->methodName);
                return call_user_func_array([$controllerInstance, $this->methodName], []);
            }
            else {
                throw new Exception("Метод $this->methodName не существует");
            }
        }
        else{
            throw new Exception("Класс $this->controllerName не существует");
        }
    }

//    public function run() : string {
//        session_start();
//
//        $routeArray = explode('/', $_SERVER['REQUEST_URI']);
//
//        if(isset($routeArray[1]) && $routeArray[1] != '') {
//            $controllerName = $routeArray[1];
//        }
//        else{
//            $controllerName = "page";
//        }
//
//        $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller";
//
//        if(class_exists($this->controllerName)){
//            // пытаемся вызвать метод
//            if(isset($routeArray[2]) && $routeArray[2] != '') {
//                $methodName = $routeArray[2];
//            }
//            else {
//                $methodName = "index";
//            }
//
//            $this->methodName = "action" . ucfirst($methodName);
//
//            if(method_exists($this->controllerName, $this->methodName)){
//                $controllerInstance = new $this->controllerName();
//                return call_user_func_array(
//                    [$controllerInstance, $this->methodName],
//                    []
//                );
//            }
//            else {
//                throw new Exception("Метод " .  $this->methodName . " не существует");
//            }
//        }
//        else{
//            throw new Exception("Класс $this->controllerName не существует");
//        }
//    }

    private function getControllerName() {
        $routeArray = $this->getRouteArray();
        if(isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
        }
        else{
            $controllerName = "page";
        }
        $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller";
    }

    private function getMethodName() {
        $routeArray = $this->getRouteArray();
        if(isset($routeArray[2]) && $routeArray[2] != '') {
            $methodName = $routeArray[2];
        }
        else {
            $methodName = "index";
        }
        $this->methodName = "action" . ucfirst($methodName);
    }

    private function getRouteArray() : array {
        return explode('/', $_SERVER['REQUEST_URI']);
    }

    private function checkPermissions($controllerInstance, $methodName) {
        if($controllerInstance instanceof AbstractController){
            $isAllowed = $this->checkAccessToMethod($controllerInstance, $this->methodName);
            if (!$isAllowed) {
                throw new Exception("Нет доступа к методу $this->methodName");
            }
        }
    }

    private function checkAccessToMethod(AbstractController $controllerInstance, string $methodName): bool {
        if ($controllerInstance->isAlwaysEnabled($methodName)) {
            return true;
        }
        $userRoles = $controllerInstance->getUserRoles();
        $rules = $controllerInstance->getActionsPermissions($methodName);
        $isAllowed = false;
        if(!empty($rules)){
            foreach($rules as $rolePermission){
                if(in_array($rolePermission, $userRoles)){
                    $isAllowed = true;
                    break;
                }
            }
        }
        return $isAllowed;
    }

}