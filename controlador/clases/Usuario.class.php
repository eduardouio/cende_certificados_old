<?php

/**
 * 
 * Objeto Usuario
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.2
 *
 */
class Usuario{
	public $Usuario_;
	public $Pass_;
	public $Nombres_;
	public $FechaCreacion_;
	public $Modificacion_;
		
	/**
	 * 
	 * crea un Objeto vacio
	 */
	public function __construct(){
		$this->Usuario_			= NULL;
		$this->Pass_ 			= NULL;
		$this->Nombres_ 		= NULL;
		$this->FechaCreacion_ 	= NULL;
		$this->Modificacion_ 	= NULL;
		
	}
	
	/**
	 * 
	 * Crea un objeto completo
	 * @param string $usuario
	 * @param string $pass
	 * @param string $nombres
	 * @param datetime $fechacrea
	 * @param datetime $modificacion
	 */
	public function Nuevo($usuario,$pass,$nombres,
						  $fechacrea,$modificacion){
		$this->Usuario_			= $usuario;
		$this->Pass_ 			= $pass;
		$this->Nombres_ 		= $nombres;
		$this->FechaCreacion_ 	= $fechacrea;
		$this->Modificacion_ 	= $modificacion;
		
	}
        
      
}

?>