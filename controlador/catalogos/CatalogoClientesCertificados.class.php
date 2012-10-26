<?php

/**
 * 
 * Representa las acciones sobre los clientes certificados
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 *
 */

require_once DIR_SYSTEM . 'modelo/modelo.class.php';
require_once DIR_SYSTEM .'controlador/clases/ClienteCertificado.class.php';

class CatalogoClientesCertificados{

         /**
         * Metodo maestro quye retorna uno o un grupo de registros 
         * de  acuerdo a un criterio de busqueda:
         * si se desea buscar con el like simplemete se cambia a true la condicion
         * y se genera la cosulta con el like 
         * 
         * @param string $columna  nombre real de la columna
         * @param string $condicion condicion incluido el =  >=  <= o = y <> 
         * todo lo qu soporte mysql
         * @param boolean $like         
         * @param integer $limite
         * @return obj(cliente)
         */
	public function ListarClientesCertificados($columna,$condicion,$like,$limite){
		$bd = BaseDatos::getInstance();
                $condicion_compuesta = false;
		$sql = 'SELECT 
				`Id_Registro`, 
				`Id_Cliente`, 
				`Id_Certificado`, 
				`Edision`, 
				`Fecha_Certificaion`,
				`Fecha_Vencemiento`, 
				`Examinacion_general`, 
				`Examinacion_especifica`, 
				`Examinacion_parcial`, 
				`Estado_Examen`, 
				`Fecha_Creacion`, 
				`Ultima_Modificacion` 
				FROM 
				`clientes_certificados`';
                if ((!$columna == '0') && (!$condicion == '0')):                                                                
                    if ($like == true):                                        
                        $sql = $sql . 'WHERE @columna LIKE @condicion';                    
                    else:
                        $sql = $sql . 'WHERE (@columna  @condicion)';                    
                    endif;            
                    $condicion_compuesta = true;
                endif;                    
             $sql = $sql . ' LIMIT @limit;';                                                
             $bd->CreateCommand($sql);        
             $bd->AsignarParamNumero('@limit',$limite); 
            
            if (($like == true) && ($condicion_compuesta == true)):            
                $bd->AsignarParamCadenaSola('@columna', $columna);
                $bd->AsignarParamCadena('@condicion',$condicion . '%');            
            elseif (($like == false) && ($condicion_compuesta == true)):            
                $bd->AsignarParamCadenaSola('@columna', $columna);
                $bd->AsignarParamCadenaSola('@condicion',$condicion);            
            endif;
                //$bd->showCommand();
		$datos = $bd->Consult();
		$rows = count($datos);
		$clientesCertificados = array();
		for ($i = 0; $i < $rows - 1 ; $i++):
		$clientecatalogo = new ClienteCertificado();
		$clientecatalogo->Nuevo($datos[$i]['Id_Registro'], $datos[$i]['Id_Cliente'], 
								$datos[$i]['Id_Certificado'], $datos[$i]['Edision'], 
								$datos[$i]['Fecha_Certificaion'], $datos[$i]['Fecha_Vencemiento'], 
								$datos[$i]['Examinacion_general'], $datos[$i]['Examinacion_especifica'], 
								$datos[$i]['Examinacion_parcial'], $datos[$i]['Estado_Examen'], 
								$datos[$i]['Fecha_Creacion'], $datos[$i]['Ultima_Modificacion'],
                                                                $datos[$i]['Fecha_Vencemiento']);
		$clientesCertificados[$i] = $clientecatalogo;
		endfor;
                if($limite == 1):
                    return $clientecatalogo;
                    else:
                return $clientesCertificados;			    
                endif;
		
			}
			
	/**
	 * 
	 * retorna los certificados para una persona
	 * @param $id_cliente
	 */		
	public function 
                ListarCertificadosCliente($id_cliente){
		$bd = BaseDatos::getInstance();
				
		$sql = "SELECT 
				`Id_Registro`, 
				`Id_Cliente`, 
				`Id_Certificado`, 
				`Edision`, 
				DATE_FORMAT(`Fecha_Certificaion`,'%M-%Y') as `Fecha_Certificaion`,
				DATE_FORMAT(`Fecha_Vencemiento`,'%M-%Y') as `Fecha_Vencemiento`,				
				`Examinacion_general`, 
				`Examinacion_especifica`, 
				`Examinacion_parcial`, 
				`Estado_Examen`, 
				`Fecha_Creacion`, 
				`Ultima_Modificacion`,
                                `Fecha_Vencemiento` as Vencimiento
				FROM 
				`clientes_certificados`
				WHERE
				`Id_Cliente` = @idCliente;";
		
		$bd->CreateCommand($sql);
		$bd->AsignarParamNumero('@idCliente',$id_cliente);
		$datos = $bd->Consult();
		$rows = count($datos);
		$clientesCertificados = array();
		for ($i = 0; $i < $rows - 1 ; $i++):
		$clientecatalogo = new ClienteCertificado();
		$clientecatalogo->Nuevo($datos[$i]['Id_Registro'], $datos[$i]['Id_Cliente'], 
								$datos[$i]['Id_Certificado'], $datos[$i]['Edision'], 
								$datos[$i]['Fecha_Certificaion'], $datos[$i]['Fecha_Vencemiento'], 
								$datos[$i]['Examinacion_general'], $datos[$i]['Examinacion_especifica'], 
								$datos[$i]['Examinacion_parcial'], $datos[$i]['Estado_Examen'], 
								$datos[$i]['Fecha_Creacion'], $datos[$i]['Ultima_Modificacion'],
                                                                $datos[$i]['Vencimiento'] );
		$clientesCertificados[] = $clientecatalogo;
		endfor;                
		return $clientesCertificados;		
	
	}		
	
	/**
         * Crea una certificacion para un cliente
         * @param ClienteCertificado $ClienteCertificado 
         */
	public function CrearClienteCertificado(&$ClienteCertificado){
		$bd = BaseDatos::getInstance();
		$sql ='INSERT INTO  `cendendt_certificados`.`clientes_certificados` (
				`Id_Registro` ,`Id_Cliente` ,`Id_Certificado` ,
				`Edision` ,`Fecha_Certificaion` ,`Fecha_Vencemiento` ,
				`Examinacion_general` ,`Examinacion_especifica` ,`Examinacion_parcial` ,
				`Estado_Examen` ,`Fecha_Creacion` 
				)VALUES (
				@codigo,  
				@id_cliente,
				@id_certificado,
				@edision,  
				@fecha,
				@vencimiento,
				@exaGeneral,
				@exaEspe,
				@exaParci,
				@Examen,
				@fechaCrea
				);';	
		$bd->CreateCommand($sql);
				
		$bd->AsignarParamCadena('@codigo', $ClienteCertificado->IdRegistro_);
		$bd->AsignarParamCadena('@id_cliente', $ClienteCertificado->MiCliente_->IdCliente_);
		$bd->AsignarParamCadena('@id_certificado', $ClienteCertificado->MiCertificado_->IdCertificado_);
		$bd->AsignarParamCadena('@edision',$ClienteCertificado->Edicion_);
		$bd->AsignarParamCadena('@fecha',$ClienteCertificado->FechaCertificacion_);
		$bd->AsignarParamCadena('@vencimiento', $ClienteCertificado->FechaVencimiento_);
		$bd->AsignarParamNumero('@exaGeneral', $ClienteCertificado->ExaGeneral_);
		$bd->AsignarParamNumero('@exaEspe',$ClienteCertificado->ExaEspecifica_);
		$bd->AsignarParamNumero('@exaParci',$ClienteCertificado->ExaParcial_);
		$bd->AsignarParamCadena('@Examen',$ClienteCertificado->Examen_);
		$bd->AsignarParamCadena('@fechaCrea',date('Y-m-d H:m:s'));		
		$bd->ExecCommand();
		}
	
                /**
                 * Actuaiza la certificacion de un cliente 
                 * @param integer $id_registro_old id del registro a modificar
                 * @param ClienteCertificado  objeto con los datos nuevos para realizar el cambio
                 */
	public function ActualizarClienteCertificado($id_registro_old,$id_certificado_old,&$ClienteCertificado){
		$bd = BaseDatos::getInstance();                
		$sql = 'UPDATE  `cendendt_certificados`.`clientes_certificados` 
				SET  `Id_Registro` =  @codigo,
				`Id_Cliente` =  @idCliente,
				`Id_Certificado` =  @idCertificado,
				`Edision` =  @edision,
				`Fecha_Certificaion` =  @fechaCert,
				`Fecha_Vencemiento` =  @fechaVenc,
				`Examinacion_general` =  @exaGeneral,
				`Examinacion_especifica` =  @exaEspe,
				`Examinacion_parcial` =  @exaParci,
				`Estado_Examen` =  @examen				
				WHERE 
				`Id_Cliente`= @id_cliente_old 
				AND
				`Id_Certificado`= @id_certificado_old 
				AND
				`Id_Registro` = @codigoOld;';
		$bd->CreateCommand($sql);		
		$bd->AsignarParamCadena('@codigo',$ClienteCertificado->IdRegistro_);
		$bd->AsignarParamNumero('@idCliente',$ClienteCertificado->MiCliente_->IdCliente_);
		$bd->AsignarParamNumero('@idCertificado', $ClienteCertificado->MiCertificado_->IdCertificado_);
		$bd->AsignarParamCadena('@edision',$ClienteCertificado->Edicion_);
		$bd->AsignarParamCadena('@fechaCert', $ClienteCertificado->FechaCertificacion_);
		$bd->AsignarParamCadena('@fechaVenc',$ClienteCertificado->FechaVencimiento_);
		$bd->AsignarParamNumero('@exaGeneral', $ClienteCertificado->ExaGeneral_);
		$bd->AsignarParamNumero('@exaEspe',$ClienteCertificado->ExaEspecifica_);
		$bd->AsignarParamNumero('@exaParci',$ClienteCertificado->ExaParcial_);
		$bd->AsignarParamCadena('@examen',$ClienteCertificado->Examen_);		
		$bd->AsignarParamCadenaSola('@id_cliente_old',$ClienteCertificado->MiCliente_->IdCliente_);
                $bd->AsignarParamCadenaSola('@id_certificado_old',$id_certificado_old);				
                $bd->AsignarParamCadena('@codigoOld',$id_registro_old);
		$bd->ExecCommand();
	
	}
	
        /**
         *  elimina el certificado de un cliente
         * @param integer id del certificado a eliminar en el clinete 
         */
	public function EliminarClienteCertificado($id_registro_old,$id_certificado_old){
		$bd = BaseDatos::getInstance();
                
		$sql = 'DELETE 
				FROM 
				`cendendt_certificados`.`clientes_certificados`
				WHERE 
                                `clientes_certificados`.`Id_Registro` = @registro_old
                                AND
                                `clientes_certificados`.`Id_Certificado` = @id_certificado_old
                                ;';
		
		$bd->CreateCommand($sql);
		$bd->AsignarParamCadena('@registro_old', $id_registro_old);
                $bd->AsignarParamCadena('@id_certificado_old', $id_certificado_old);
		$bd->ExecCommand();
		}
}