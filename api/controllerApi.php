<?php
    require_once("api/viewApi.php");
    require_once("model/model.php");

    class ControllerApi{

        private $model;
        private $view;
        private $data;

        public function __construct(){
            $this->model = new Modelcatalogo();
            $this->view = new ViewApi();
            $this->data = file_get_contents("php://input");
        }

        public function getAll(){
            $data = $this->model->get_all_productos();
            if(isset($data)){
                $this->view->response($data, 200);
            }else{
                $this->view->response(null, 404);
            }
        }

        public function get($param = []){
            $id_producto = $param[':ID'];
                if(isset($id_producto)){
                    $data = $this->model->get_producto($id_producto);
                    if(isset($data)){
                        $this->view->response($data, 200);
                    }else{
                        $this->view->response(null, 404);
                    }
                }else{
                    $this->view->response("Producto inexistente", 404);
                }
        }

        public function add(){
            $datos = $this->data();
                if(isset($datos)){
                    $nombre = $datos->nombres;
                    $descripcion = $datos->descripcion;
                    $imagen = $datos->imagen;
                    $cantidad = $datos->cantidad;
                    $data = $this->model->agregar_producto($nombre, $descripcion, $imagen, $cantidad);

                    if($data){
                        $this->view->response("Producto agregado con éxito", 200);
                    }else{
                        $this->view->response("Error al agregar el producto", 404);
                    }
                }
        }

        public function update($params = []){
            $id_producto = $param[':ID'];
                if(isset($id_producto)){

                    $existe = $this->model->get_producto($id_producto);
                    if(isset($existe)){
                        $datos = $this->data();
                        if(isset($datos)){
                            $nombre = $datos->nombres;
                            $descripcion = $datos->descripcion;
                            $imagen = $datos->imagen;
                            $cantidad = $datos->cantidad;
                            $data = $this->model->modificar_producto($nombre, $descripcion, $imagen, $cantidad, $id_producto);
        
                            if($data){
                                $this->view->response("Producto modificado con éxito", 200);
                            }else{
                                $this->view->response("Error al modificar el producto", 404);
                            }
                        }
                    }else{ /*No deberia llegar a esta instancia ya que si muestra el producto es porque existe*/
                        $this->view->response("Producto inexistente", 404);
                    }
                }
        }

        public function delete($param = []){
            $id_producto = $param[':ID'];
                if(isset($id_producto)){
                    $result = $this->model->eliminar_producto($id_producto);
                    if($result){
                        $this->view->response("Producto (ID: ".$id_producto.") eliminado", 200);
                    }else{
                        $this->view->response("Error al eliminar el producto", 404);
                    }
                }
        }

        public function incrementCantidad($cantidad_adicional, $param = []){
            $id_producto = $param[':ID'];
                if(isset($id_producto)){
                    $prod = $this->model->get_producto($id_producto);
                        if(isset($prod)){
                           $cant = $prod->cantidad + $cantidad_adicional;
                           $result = $this->model->modificar_cantidad_de_producto($cant, $id_producto);
                            if($result){
                                $this->view->response("Cantidad incrementada", 200);
                            }else{
                                $this->view->response("Error al incrementar cantidad del producto (ID: ".$id_producto.")", 404);
                            }
                        }else{ /*No deberia llegar a esta instancia ya que si muestra el producto es porque existe*/
                            $this->view->response("Producto inexistente", 404);
                        }
                }
        }

    }
?>