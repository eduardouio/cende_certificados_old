<?php
/**
 * Esta clase se encarga de manejar las cadenas de los valores
 * de enviados a la base de datos, revisar el if de una linea
 *
 * @author Eduardo Villota <ev_villota@hotmail.com>
 * @version 1.0
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @access Global
 * @category helpers
 */
class Cadenas{
    
    private $Cadena_;
    
        public function Mayusculas($cadena){
        $this->Cadena_ = strtoupper($cadena);               
        return $this->Cadena_;       
        }
            
            
}

?>
