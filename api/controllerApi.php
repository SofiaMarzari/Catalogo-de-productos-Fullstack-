<?php
    require_once('api/viewApi.php');
    require_once('model/modell.php');

    class ControllerApi{

        private $model;
        private $view;
        private $data;

        public function __construct(){
            $this->model = new Modelcatalogo();
            $this->view = new ViewApi();
            $this->data = file_get_contents("php://input");
        }

        public function data(){
            return json_decode($this->data);
        }

        public function getAll(){
            $datos = $this->model->get_all_productos();
    
            if(isset($datos)){    
                $this->view->response($datos, 200);
            }else{
                $this->view->response(null, 404);
            }
        }

        public function getIdUltimo(){
            $datos = $this->model->get_id_ultimo_insert();
    
            if(isset($datos)){    
                $this->view->response($datos, 200);
            }else{
                $this->view->response(null, 404);
            }
        }

        public function get($param = []){
            $id_producto = $param[':ID'];
                if(isset($id_producto)){
                    $query = $this->model->get_producto($id_producto);
                    $query2 = $this->model->get_imagenes($id_producto);
                    
                    if(isset($query)){
                        $data = array("datos"=>$query,"arrayimagenes"=>$query2);
                        $this->view->response($data, 200);
                    }else{
                        $this->view->response(null, 404);
                    }
                }else{
                    $this->view->response("Producto inexistente", 404);
                }
        }

        public function add(){
            //$datos= $this->data();
            $data_nombre= $_POST['nombre'];
            $data_descripcion= $_POST['descripcion'];
            $data_cantidad = $_POST['cantidad'];
            $data_imagenfile = $_FILES['file_imagen']; 
                if(isset($data_nombre)&&isset($data_descripcion)&&isset($data_cantidad)&&isset($data_imagenfile)){
                   /* $nombre = $datos->nombre;
                    $descripcion = $datos->descripcion;
                    $imagen = $datos->imagen;
                    $cantidad = $datos->cantidad;*/

                    $query = $this->model->agregar_producto($data_nombre, $data_descripcion, $data_imagenfile, $data_cantidad);
                    if(empty($query)){
                        $this->view->response("Producto agregado con éxito", 200);
                    }else{
                        $this->view->response("Error al agregar el producto", 404);
                    }
                }
        }

        public function addImagen($params = []){
            $id_prod = $param[':ID'];
                if(isset($id_prod)){
                    $existe = $this->model->get_producto($id_producto);
                    if(isset($existe)){
                        $data_imagenfile = $_FILES['file_imagen']; 
                        $query = $this->model->agregar_imagen_de_producto($data_imagenfile, $id_prod);
                        if(empty($query)){
                            $this->view->response("Imagen agregada con éxito", 200);
                        }else{
                            $this->view->response("Error al agregar la imagen", 404);
                        }
                    }else{ /*No deberia llegar a esta instancia ya que si muestra el producto es porque existe*/
                        $this->view->response("Producto inexistente", 404);
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
                            ($datos->descripcion != '') ? $descripcion = $datos->descripcion : $descripcion = $existe[0]['descripcion'] ;
                            ($datos->nombre != '') ? $nombre = $datos->nombre : $nombre = $existe[0]['nombre'] ;
                            ($datos->cantidad > 0) ? $cantidad = $datos->cantidad : $cantidad = $existe[0]['cantidad'];
                            
                            $query = $this->model->modificar_producto($nombre, $descripcion, $cantidad, $id_producto);
                            if(empty($query)){
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
                    $query = $this->model->eliminar_producto($id_producto);
                    if(empty($query)){
                        $this->view->response("Producto (ID: ".$id_producto.") eliminado", 200);
                    }else{
                        $this->view->response("Error al eliminar el producto", 404);
                    }
                }
        }

        public function deleteImagen($param = []){
            $id_producto = $param[':ID'];
            $id_imagen = $param[':IMG'];
          
            if(isset($id_producto)){
                 
                        $query = $this->model->eliminar_imagen_secundaria($id_producto, $id_imagen);
              
                    if(empty($query)){
                        $this->view->response("Imagen del producto (ID: ".$id_producto.") eliminada", 200);
                    }else{
                        $this->view->response("Error al eliminar imagen", 404);
                    }
            }
        }

       /* public function incrementCantidad($cantidad_adicional, $param = []){
            $id_producto = $param[':ID'];
                if(isset($id_producto)){
                    $prod = $this->model->get_producto($id_producto);
                        if(isset($prod)){
                           $cant = $prod->cantidad + $cantidad_adicional;
                           $query = $this->model->modificar_cantidad_de_producto($cant, $id_producto);
                            if(empty($query)){
                                $this->view->response("Cantidad incrementada", 200);
                            }else{
                                $this->view->response("Error al incrementar cantidad del producto (ID: ".$id_producto.")", 404);
                            }
                        }else{ *//*No deberia llegar a esta instancia ya que si muestra el producto es porque existe*/
                            /*$this->view->response("Producto inexistente", 404);
                        }
                }
        }*/

    }
?>