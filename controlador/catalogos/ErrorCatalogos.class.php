<?php
/**
 * esta clse recibe un error y me lo envia por mail a mi cuena de correo
 *
 * @author eduardo
 * @category manejo de errores
 * @version 1.0
 * @package base de datos
 */
class ErrorCatalogos {
    
    public function Enviar($error, $mensaje) {
        $cuerpo = 'Tienes un Error en CENDENDT tipo Catalogos: '.$error . '  Mensaje =>' . $mensaje;
        mail('ev_villota@hotmail.com', 'Error en CENDENDT', $cuerpo);
        
    }
    
     ###############################Errores de esta clase#################    
    /**
     *
     * @param type $mensaje mensaje del error
     * @param type $error   descripcion tecnica del error
     * @param type $errorn  numero de error en cas de averlo
     * @param type $extra   Dato estra del error
     */
    private function Error($mensaje,$error,$errorn,$extra){
        print ($mensaje .'</br>'.
               $errorn . '</br>'.
               $error. '</br>' .
               'si el problema Persiste Comunicarse con: 
                <b>ev_villota@hotmail.com </b>
                <p>Detalles del error</p>' . 
               $error . '</br>' .
               $extra);
        $msj = $mensaje . '<p>'.$error .'</p>' . '<p>'.$extra .'</p>'; 
        $this->Enviar($errorn, $msj);
        error_log($msj,0);
        exit('<p><b>Disculpe las molestias -_-</b></p>');
    }              
}

?>
