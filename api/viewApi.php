<?php
    require_once('api/controllerApi.php');

    class ViewApi{

        public function response($data, $code){
            header("Content-Type: application/json");
            header("HTTP/1.1 " . $code . " " . $this->_requestStatus( $code));
            echo json_encode($data);
        }

        private function _requestStatus($code){
            $status = array(
                200 => "OK",
                404 => "Not found",
                500 => "Internal Server Error"
            );

            return (isset($status[$code])) ? $status[$code] : $status[500];
        }
        
    }

?>