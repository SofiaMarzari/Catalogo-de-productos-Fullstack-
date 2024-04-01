<?php
    require_once("api/controllerApi.php");

    class Modelcatalogo{
        private $db;

        public function __construct(){
            $this->db = new PDO('mysql:host=localhost;'.'dbname=catalogos;','root','23344');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        public function get_all_productos(){
            $data = $this->db->prepare("SELECT * FROM producto");
            $data->execute();
            $result = $data->fetchAll();
            
            return $result;
        }

        public function get_producto($id){
            $data = $this->db->prepare("SELECT * FROM producto WHERE id_producto=?");
            $data->execute(array($id));
            $result = $data->fetchAll();

            return $result;
        }

        public function get_imagenes($id){
            $data = $this->db->prepare("SELECT * FROM imagen WHERE id_producto=?");
            $data->execute(array($id));
            $result = $data->fetchAll();

            return $result;
        }

        public function agregar_producto($nom, $desc, $img, $cant){
            $filepath = 'uploads/productos/'.uniqid("", true);
            $name_file = $img['name'];
            $path = $filepath . $name_file;
            move_uploaded_file($img['tmp_name'], $path);
            $data = $this->db->prepare("INSERT INTO producto(nombre, descripcion, imagen, cantidad) VALUES (?,?,?,?)");
            $data->execute(array($nom, $desc, $path, $cant));
        }

        public function agregar_imagen_de_producto($img, $id){
            $filepath = 'uploads/productos/'.uniqid("", true);
            $name_file = $img['name'];
            $path = $filepath . $name_file;
            move_uploaded_file($img['tmp_name'], $path);
            $data = $this->db->prepare("INSERT INTO imagen(imagen, id_producto) VALUES (?,?)");
            $data->execute(array($path, $id));
        }

        public function get_id_ultimo_insert(){
            return $this->db->lastInsertId();
        }

        public function modificar_producto($nom, $desc, $cant, $id){
            $data = $this->db->prepare("UPDATE producto SET nombre=?, descripcion=?, cantidad=? WHERE id_producto=?");
            $data->execute(array($nom, $desc, $cant, $id));
        }

        public function eliminar_producto($id){
            $data = $this->db->prepare("DELETE FROM producto WHERE id_producto=?");
            $data->execute(array($id));
        }

        public function eliminar_imagen_secundaria($id, $id_img){
            $data = $this->db->prepare("DELETE FROM imagen WHERE id_producto=? AND id_imagen=?");
            $data->execute(array($id, $id_img));
        }

        public function modificar_cantidad_de_producto($cant, $id){
            $data = $this->db->prepare("UPDATE producto SET cantidad=? WHERE id_producto=?");
            $data->execute(array($cant, $id));
        }

    }

?>