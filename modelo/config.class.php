<?php
/**
 * carga la configuracion a partir del fichero 
 *
 * @author eduardo
 * @version 2.1
 * @name configuracion base de datos
 */
class Config {
    
    private $_Host_;
    private $_Pass_;
    private $_User_;
    private $_DataBase_;
    
    static $_Instance;
    
    private function __construct() {
        require_once 'conf.php';        
        $this->_DataBase_   =   $database;
        $this->_Host_       =   $host;
        $this->_Pass_       =   $pass;
        $this->_User_       =   $user;        
    }   
    
    /**
     *evitamos el clonado de la clase una sola instancia para todos 
     */
    private function __clone(){}     
    
    
    /**
     * si no existe la instancia se crea y si existe se devuelve la 
     * creada antes
     * @return insacia de un objeto 
     */
    public static function getInstance(){
        if(!(self::$_Instance instanceof self)):
            self::$_Instance = new self();
        endif;
        
        return self::$_Instance;
    }
    
    
    ########################Propiedades de la clase############################
    /**
     *con esto evitamnos que se sobreescriba los parametros de conexxion a la bd 
     */
    
    /**
     *obtener usuario
     * @return string
     */
    public function getUser(){
        return $this->_User_;
    }
    
    /**
     *obtener password
     * @return string
     */
    public function getPass(){
        return $this->_Pass_;
    }
    
    /**
     *obtener host
     * @return string
     */
    public function getHost(){
        return $this->_Host_;
    }
    
    /**
     *obtener base de datos
     * @return string
     */
    public function getDataBase(){
        return $this->_DataBase_;        
    }
}

?>
