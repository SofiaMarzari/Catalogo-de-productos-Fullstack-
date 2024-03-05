<?php
    require_once("api/controllerApi.php");

    class Modelcatalogo{
        private $db;

        public function __construct(){
            $this->db = new PDO('mysql:host=localhost;'.'dbname=catalogo;'.'root'.'23344');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        public function get_all_productos(){

        }

        public function get_producto($id){

        }

        public function agregar_producto($nom, $desc, $img, $cant){

        }

        public function modificar_producto($nom, $desc, $img, $cant, $id){

        }

        public function eliminar_producto($id){

        }

        public function modificar_cantidad_de_producto($cant, $id){

        }

    }

?>