<?php
/**
 * 
 * Objeto tipo certificado
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 *
 */
class Certificado{
	public $IdCertificado_;
	public $Codigo_;
	public $Metodo_;
	public $Nivel_;
	public $FechaCreacion_;
	public $Modificacion_;
	
	/**
	 * 
	 * Contruye un objeto vacio
	 */
	public function __construct(){
		$this->IdCertificado_           = NULL;
		$this->Codigo_			= NULL;
		$this->Metodo_ 			= NULL;
		$this->Nivel_ 			= NULL;
		$this->FechaCreacion_           = NULL;
		$this->Modificacion_            = NULL;
	}
	
	/**
	 * 
	 * Contruye un objeto Competo
	 * @param integer $id
	 * @param string $codigo
	 * @param string $metodo
	 * @param string $nivel
	 * @param date $fecha
	 * @param datetime $modificacion
	 */
	public function Nuevo($id,$codigo,$metodo,$nivel,
						  $fecha,$modificacion){
						  	
		$this->IdCertificado_ 	= $id;
		$this->Codigo_		= $codigo;
		$this->Metodo_ 		= $metodo;
		$this->Nivel_ 		= $nivel;
		$this->FechaCreacion_ 	= $fecha;
		$this->Modificacion_ 	= $modificacion;
	}
		
		
}
?>