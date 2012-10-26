<?php
/**
 * 
 * Objeto tipo cliente
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 *
 */
class Cliente{
    public $IdCliente_;
    public $Cedula_;
    public $Profesion_;
    public $Nombres_;
    public $Apellidos_;
    public $Empresa_;
    public $Pais_;
    public $Notas_;
    public $FechaCreacion_;
    public $Modificacion_;
    
    /**
     * 
     * Construye un objeto vacio
     */
    public function __construct(){
        $this->IdCliente_ 		= NULL;
        $this->Cedula_ 			= NULL;
        $this->Profesion_ 		= NULL;
        $this->Nombres_ 		= NULL;
        $this->Apellidos_ 		= NULL;
        $this->Empresa_ 		= NULL;
        $this->Pais_ 			= NULL;
        $this->Notas_ 			= NULL;
        $this->FechaCreacion_           = NULL;
        $this->Modificacion_            = NULL;
    }
    
    /**
     * 
     * Contruye un objeto completo
     * @param integer $id
     * @param srting $cedula
     * @param srting $profesion
     * @param srting $nombres
     * @param srting $apellidos
     * @param srting $empresa
     * @param srting $pais
     * @param srting $notas
     * @param date $fecha
     * @param srting $modificacion
     */
        public function Nuevo($id,$cedula,$profesion,$nombres,$apellidos,
        							$empresa,$pais,$notas,$fecha,$modificacion){
        $this->IdCliente_ 		= $id;
        $this->Cedula_ 			= $cedula;
        $this->Profesion_ 		= $profesion;
        $this->Nombres_ 		= $nombres;
        $this->Apellidos_ 		= $apellidos;
        $this->Empresa_ 		= $empresa;
        $this->Pais_ 			= $pais;
        $this->Notas_ 			= $notas;
        $this->FechaCreacion_ 	        = $fecha;
        $this->Modificacion_ 	        = $modificacion;
    }
    
  
}

?>