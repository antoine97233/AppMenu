<?php

include "Controller/HomeController.php";
include "Controller/BaseController.php";
include "Controller/GroupController.php";
include "Controller/CategoryController.php";
include "Controller/ItemController.php";
include "Controller/LoginController.php";
include "Controller/UserController.php";




class Dispatcher
{
    public function dispatch()
    {
        $controller = (isset($_GET['controller']))
            ? $_GET['controller'] : "Front";

        $controller = '\\App\\Menu\\Controller\\' . ucfirst($controller) . 'Controller';

        $action = (isset($_GET['action'])) ? $_GET['action'] : "show";
        $action = $action . "Action";

        $my_controller = new $controller();
        $my_controller->$action();
    }
}
