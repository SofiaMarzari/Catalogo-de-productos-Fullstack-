<?php
    require_once("api/controllerApi.php");
    require_once("Router.php");

    $resource = $_GET['resource'];
    $metodo = $_SERVER['REQUEST_METHOD'];

    $ruteador = new Router();

    $ruteador->addRoute('productos', 'GET', 'controllerApi', 'getAll');
    $ruteador->addRoute('productos/:ID', 'GET', 'controllerApi', 'get');
    $ruteador->addRoute('productos', 'POST', 'controllerApi', 'add');
    $ruteador->addRoute('productos/:ID', 'PUT', 'controllerApi', 'update');
    $ruteador->addRoute('productos/:ID', 'DELETE', 'controllerApi', 'delete');
    $ruteador->addRoute('productos/:ID/cantidad/:ID', 'PUT', 'controllerApi', 'incrementCantidad');

    $ruteador->route($resource, $metodo);

?>