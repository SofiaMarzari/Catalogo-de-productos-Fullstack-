<?php
    require_once("api/controllerApi.php");

    class Modelcatalogo{
        private $db;

        public function __construct(){
            $this->db = new PDO('mysql:host=localhost;'.'dbname=catalogo;'.'root'.'23344');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        public function get_all_productos(){
            $data = $this->db->prepare('SELECT * FROM productos');
            $data->execute();
            $result = $data->fetchAll();

            return $result;
        }

        public function get_producto($id){
            $data = $this->db->prepare("SELECT * FROM productos WHERE id_producto=?");
            $data->execute(array($id));
            $result = $data->fetchAll();

            return $result;
        }

        public function agregar_producto($nom, $desc, $img, $cant){
            $data = $this->db->prepare("INSERT INTO productos(nombre, descripcion, imagen, cantidad) VALUES (?,?,?,?)");
            $data->execute(array($nom, $desc, $img, $cant));
        }

        public function modificar_producto($nom, $desc, $img, $cant, $id){
            $data = $this->db->prepare("UPDATE productos SET nombre=?, descripcion=?, imagen=?, cantidad=? WHERE id_producto=?");
            $data->execute(array($nom, $desc, $img, $cant, $id));
        }

        public function eliminar_producto($id){
            $data = $this->db->prepare("DELETE FROM productos WHERE id_producto=?");
            $data->execute(array($id));
        }

        public function modificar_cantidad_de_producto($cant, $id){
            $data = $this->db->prepare("UPDATE productos SET cantidad=? WHERE id_producto=?");
            $data->execute(array($cant, $id));
        }

    }

?>