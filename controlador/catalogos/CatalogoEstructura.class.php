<?php
/**
 * 
 * acciones sobre la base de datos ==> las tablas y sus campos 
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 *
 */
require_once DIR_SYSTEM . 'modelo/modelo.class.php';


/**
  * Manipulacion de objetos tipo estructura bd
  * para que no muestre las advertencias se usa @ antes 
  * de llamar a los metodos de este catalogo
  */
class CatalogoBd {
	
        /**
         * Obtiene las columnas de la base de datos de certificados
         * @return array con el listado de las tablas
         */
	function ObtenerTablas(){
		$bd = BaseDatos::getInstance();
		$sql = "show tables from cendendt_certificados";
		$bd->CreateCommand($sql);
                $tablas = array();
		$res = $bd->Consult();
			foreach ($res as $indice => $dato):				
					$tablas[] = $dato;				
			endforeach;	
		return  $tablas;
		}
                //212
	
                /**
                 * retorna los campos de una tabla 
                 * @param string $tabla nombre de la tabla
                 * @return array 
                 */
	function ObtenerColumnas($tabla){
		$bd = BaseDatos::getInstance();
		$sql = "describe @tabla;";
		$bd->CreateCommand($sql);
		$bd->AsignarParamCadenaSola('@tabla', $tabla);                
                $columnas = array();
		$res = $bd->Consult();                
		foreach ($res as $indice => $val):				
                    $columnas[] = $val['Field'];				
		endforeach;
		
		return $columnas;
		}
}
