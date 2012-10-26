<?php
/**
 * 
 * Representa las acciones que se pueden hacer con un Certificado
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 *
 */
require_once DIR_SYSTEM . 'modelo/modelo.class.php';
require_once DIR_SYSTEM . 'controlador/clases/Certificado.class.php';

class CatalogoCertificado{
	
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
	public function  ListarCertificados($columna,$condicion,$like,$limite){
           $bd = BaseDatos::getInstance();
           $condicion_compuesta = false;
		$sql = 'SELECT 
				`Id_Certificado`,`Codigo`,`Metodo`,
				`nivel`,`Fecha_Creacion`,
				`Ultima_Modificacion` 
				FROM 
				`tipos_de_certificados` ';
                
                if ((!$columna == '0') && (!$condicion == '0')):                                                                
                    if ($like == true):                                        
                        $sql = $sql . ' WHERE @columna LIKE @condicion ';                    
                         else:
                            $sql = $sql . ' WHERE (@columna  @condicion) ';                    
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
		$Certificados = $bd->Consult();		
		$rows = count($Certificados);	                
		$MisCertificados = array();
		
		for ($i = 0;$i < $rows - 1;$i++):
			
			foreach ($Certificados[$i] as $indide):
				$miCertificado = new Certificado();
				$miCertificado->IdCertificado_ = $Certificados[$i]['Id_Certificado'];
				$miCertificado->Codigo_ = $Certificados[$i]['Codigo'];
				$miCertificado->Metodo_ = $Certificados[$i]['Metodo'];
				$miCertificado->Nivel_ = $Certificados[$i]['nivel'];
				$miCertificado->FechaCreacion_ = $Certificados[$i]['Fecha_Creacion'];
				$miCertificado->Modificacion_ = $Certificados[$i]['Ultima_Modificacion'];
				$MisCertificados[$i] = $miCertificado;			
			endforeach;
		endfor;
		
		return $MisCertificados;
                
						
	}
	
		public function  ObtenerCertificado($IdCertificado){
		$bd = BaseDatos::getInstance();
		$sql = 'SELECT 
				`Id_Certificado`, `Codigo`, `Metodo`, 
				`nivel`, `Fecha_Creacion`, `Ultima_Modificacion` 
				FROM `tipos_de_certificados` 
				WHERE `Id_Certificado` = @id_certificado;';
		
		$bd->CreateCommand($sql);
		$bd->AsignarParamNumero('@id_certificado', $IdCertificado);		
		$misCertificados = $bd->Consult();
                $miCertificado = new Certificado();
				
		$miCertificado->IdCertificado_ = $misCertificados[0]['Id_Certificado'];
		$miCertificado->Codigo_ = $misCertificados[0]['Codigo'];
		$miCertificado->Metodo_ = $misCertificados[0]['Metodo'];
		$miCertificado->Nivel_ = $misCertificados[0]['nivel'];
		$miCertificado->FechaCreacion_ = $misCertificados[0]['Fecha_Creacion'];
		$miCertificado->Modificacion_ = $misCertificados[0]['Ultima_Modificacion'];
		
		return $miCertificado;
						
		}
		
		public function CrearCertificado(&$Certificado){
		$bd = BaseDatos::getInstance();
		$sql = 'INSERT INTO  `cendendt_certificados`.`tipos_de_certificados` (
				`Codigo` , `Metodo` ,
				`nivel` , `Fecha_Creacion` 
				)VALUES (
				@codigo,
				@metodo,  
				@nivel,  
				@fecha
				);';
		$bd->CreateCommand($sql);
		$bd->AsignarParamCadena('@codigo', $Certificado->Codigo_);
		$bd->AsignarParamCadena('@metodo', $Certificado->Metodo_);
		$bd->AsignarParamNumero('@nivel', $Certificado->Nivel_);
		$bd->AsignarParamCadena('@fecha', date('Y-m-d H:m:s'));			
		
		$bd->ExecCommand();
		}
	/**
         * Actualiza un certificado 
         * @param type $idCertificadoOld
         * @param type $Certificado 
         */	
	public function ActualizarCertificado($idCertificadoOld,&$Certificado){
	$bd = BaseDatos::getInstance();
	$sql = 'UPDATE  `cendendt_certificados`.`tipos_de_certificados` 
                SET  `Codigo` =  @codigo,
                `Metodo` =  @metodo,
                `nivel` =  @nivel
                WHERE  `tipos_de_certificados`.`Id_Certificado` = @id_certificado;';
	$bd->CreateCommand($sql);
	$bd->AsignarParamCadena('@codigo', $Certificado->Codigo_);
	$bd->AsignarParamCadena('@metodo', $Certificado->Metodo_);
	$bd->AsignarParamNumero('@nivel', $Certificado->Nivel_);
	$bd->AsignarParamNumero('@id_certificado', $idCertificadoOld);
	$bd->ExecCommand();
	
		}
		
		public function EliminarCertificado($idCertificado){
			$bd = BaseDatos::getInstance();
			$sql = 'DELETE 
					FROM `cendendt_certificados`.`tipos_de_certificados` 
					WHERE `tipos_de_certificados`.`Id_Certificado` = @idCertificado;';
			$bd->CreateCommand($sql);
			$bd->AsignarParamNumero('@idCertificado', $idCertificado);
			$bd->ExecCommand();
				
		}
		
		
}