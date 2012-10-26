<?php
/**
 * 
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 2.1
 * 
 * Manejador de la BD  
 */
require_once 'config.class.php';
require_once 'ErroresBd.class.php';

class BaseDatos{    
    
    protected $Host_;
    protected $User_;
    protected $Pass_;
    protected $Conn_;
    protected $DataBase_;
    protected $Comando_;    
    protected $Result_;
    protected $Datos_;
    
    static $_Instance;
    
    /**
     * dispare enventos en la instancia 
     */
    private function __construct() {
        $this->setConection();        
    }
            
    /**
     *evitamos el clonado de la base de datos 
     */
    private function __clone(){}
    
    /**
     *configuramos los parametros de conexion 
     */
    private function setConection(){
        $config = Config::getInstance();
        $this->Host_    =   $config->getHost();
        $this->DataBase_ =  $config->getDataBase();
        $this->Pass_    =   $config->getPass();
        $this->User_    =   $config->getUser();
    }
    
    /**
     *instancia la clase o devuelva la existente
     * @return type objeto e mismo siempre 
     */
   public function getInstance(){
       if(!(self::$_Instance instanceof self)):
           self::$_Instance = new self;
       endif;
       
       return self::$_Instance;
   } 
   
   private function Conect(){
       @$this->Conn_ = new mysqli($this->Host_,  $this->User_,
                                 $this->Pass_,  $this->DataBase_);
       if ($this->Conn_->connect_error):
           $this->Error('Error al conectarse al servidor', 
                        $this->Conn_->connect_error, 
                        $this->Conn_->connect_errno, 
                        date("d-m-Y"));
       
       endif;
       
   }
   
   /**
    *desconecta de la base de datos 
    */
    private function Disconn(){
        $this->Conn_->close();
    }
    
    /**
     * Crearmos un comando Sql
     */
    public function CreateCommand($sql){
        if ($sql):
            $this->Comando_ = '';        
            trim($sql);
                if (strlen($sql) > 0 ):
                $this->Comando_ = $sql;
                else:
                    $this->Error('No se ha creado el comando', 
                                 'cadena vacia',0, '');
                endif;
        else:
                    $this->Error('No se ha creado el comando', 
                                 'no existe la variable',0, '');                  
        endif;    	
    }
    
    /**
     * Genera una consulta en la base de datos
     * @return type array assoc
     */
    public function Consult(){
        $this->Conect();        
        $this->Datos_ = NULL;
        if ($this->Comando_):
            @$this->Result_ = $this->Conn_->query($this->Comando_);
            if (!($this->Conn_->error)):
                while($this->Datos_[] = $this->Result_->fetch_assoc());
            else:
                 $this->Error('Error al ejecutar un Comando',
                               $this->Conn_->error, 
                               $this->Conn_->errno, 
                               $this->Comando_);
            endif;
            
        else:
               $this->Error('No se ha creado el comando', 
                                 'no existe la propiedad',0, '');                   
        endif;        
        $this->Disconn();
        return $this->Datos_;
    }
    
    /**
     * el ejecuta un comando sin obtener un resultado 
     */
    public function ExecCommand(){
        $this->Conect();        
        if ($this->Comando_):            
            @$this->Result_ = $this->Conn_->query($this->Comando_);            
            if (isset($this->Result_->error)):           
                 $this->Error('Error al ejecutar un Comando',
                               $this->Result_->error, 
                               $this->Result_->errno, 
                               $this->Comando_);
            endif;            
        else:
               $this->Error('No se ha creado el comando', 
                                 'no existe la propiedad',0, '');                   
        endif;
        $this->Disconn();        
    } 
  
    ##########Metodos para manejo de Cadenas#####
    
    //asignacion de cadenas
    private function AsignarParametro($nombre,$separador,$valor){
    $indice = strpos($this->Comando_,$nombre);
    $prefijo =  substr($this->Comando_,0,$indice);
    $sufijo = substr($this->Comando_,$indice+strlen($nombre));
    $this->Comando_ = $prefijo . $separador . $valor . $separador . $sufijo;
    }
    
    //asinga un parametro tipo cadena al strin del Comando_
    public function AsignarParamCadena($nombre,$valor){
        $separador = "'";
        $this->AsignarParametro($nombre,$separador,$valor);
        
    }
    
    
    //asinga un parametro tipo cadena al strin del Comando_
    public function AsignarParamCadenaSola($nombre,$valor){
        $separador = '';
        $this->AsignarParametro($nombre,$separador,$valor);
    }
    
    //asinga un parametro tipo cadena al strin del Comando_
    public function AsignarParamNumero($nombre,$valor){
        $separador = '';
        $this->AsignarParametro($nombre,$separador,$valor);
    }
    
    //asinga un parametro tipo cadena al strin del Comando_
    public function AsignarParamFecha($nombre,$valor){
        $separador = "'";
                $this->AsignarParametro($nombre,$separador,$valor);
    }
    
    //asinga un parametro tipo cadena al strin del Comando_
    public function AsignarParmamNullo($nombre){
        $this->AsignarParametro($nombre,'','NULL');
    }    
    
    public function showCommand(){
        print '<b>' . $this->Comando_ . '</b>';
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
        new Error($errorn, $msj);
        error_log($msj,0);
        exit('<p><b>Disculpe las molestias -_-</b></p>');
    }                        
}
?>