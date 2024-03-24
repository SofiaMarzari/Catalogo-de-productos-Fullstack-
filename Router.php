<?php
    require_once("routeApi.php");

class Rut{
    public $url;
    public $metodo;
    public $controller;
    public $accion;
    public $params;

    public function __construct($url, $metodo, $controller, $accion){
            $this->url = $url;
            $this->metodo = $metodo;
            $this->controller = $controller;
            $this->accion = $accion;
            $this->params = [];
    }

    public function match($url, $metodo){
        if($this->metodo != $metodo){
            return false;
        }

        $partsResource = explode("/", trim($url, '/'));
        $partsRoute = explode("/", trim($this->url, '/'));

        if(count($partsResource) != count($partsRoute)){
            return false;
        }

        foreach($partsRoute as $clave=>$valor){
            if($valor[0] != ":"){
                if($valor != $partsResource[$clave]){
                    return false;
                }
            }

            if($valor[0] == ":"){
                $this->params[$valor] = $partsResource[$clave];
            }
        }

        return true;

    }

    public function run(){
        $controller = $this->controller;
        $accion = $this->accion;
        $params = $this->params;

        (new $controller())->$accion($params);
    }
}

class Router{
    private $routeTable = [];

    public function addRoute($url, $metodo, $controller, $accion){
        $this->routeTable[] = new Rut($url, $metodo, $controller, $accion);
    }
    public function route($url, $metodo){
        foreach($this->routeTable as $route){
            if($route->match($url, $metodo)){
                return $route->run();
            }
        }
    }

}

?>