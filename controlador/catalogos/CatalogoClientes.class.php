<?php
/**
 * 
 * Representa las acciones que se pueden hacer con un cliente
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 2.0
 *
 */
require_once DIR_SYSTEM . 'controlador/catalogos/ErrorCatalogos.class.php';
require_once DIR_SYSTEM . 'modelo/modelo.class.php';
require_once DIR_SYSTEM . 'controlador/clases/Cliente.class.php';
    
class CatalogoClientes{
    
    private $Clientes_;
	
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
	public function ListarClientes($columna,$condicion,$like,$limite){
           
           $bd = BaseDatos::getInstance();
           $condicion_compuesta = false;
           $sql = 'SELECT
				`Id_Cliente`,`Cedula`,`Profesion`,
				`Nombres`,`Apellidos`,`Empresa`,`pais`,
				`Notas`,`Fecha_Creacion`,`Ultima_Modificacion`
				 FROM
				`clientes`';
        
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
		$datos = $bd->Consult();		
		$rows = count($datos);                
                if ($datos[0]['Id_Cliente'] == NULL):
                    $this->Clientes_ = new Cliente(); 
                else:
                    //$Clientes = array();
                        for ($i = 0; $i < $rows - 1  ;$i++ ):
			
                            foreach ($datos[$i] as $indice):
                                $micliente = new Cliente();
                                @$micliente->IdCliente_  =   $datos[$i]['Id_Cliente'];
                                @$micliente->Cedula_     =   $datos[$i]['Cedula'];
                                @$micliente->Profesion_  =   $datos[$i]['Profesion'];
                                @$micliente->Nombres_    =   $datos[$i]['Nombres'];
                                @$micliente->Apellidos_  =   $datos[$i]['Apellidos'];
                                @$micliente->Empresa_    =   $datos[$i]['Empresa'];
                                @$micliente->Pais_       =   $datos[$i]['pais'];
                                @$micliente->Notas_      =   $datos[$i]['Notas'];
                                @$micliente->FechaCreacion_  = $datos[$i]['Fecha_Creacion'];
                                @$micliente->Modificacion_   = $datos[$i]['Ultima_Modificacion'];
                                $this->Clientes_[$i] = $micliente;
                            endforeach;	
                    endfor;		
                endif;
                
		return $this->Clientes_;
	}	
	

	/**
	 * 
	 * Obtiene un cliente de la base de datos
	 * @param string $IdCliente
	 * @return Cliente
	 */
		public function ObtenerCliente($IdCliente){                    
                    $MiCliente = new Cliente();
                    $bd = BaseDatos::getInstance();
                    $sql = 'SELECT 
				`Id_Cliente`,`Cedula`,`Profesion`,
				`Nombres`,`Apellidos`,`Empresa`,`pais`,
				`Notas`,`Fecha_Creacion`,`Ultima_Modificacion` 
				FROM 
				`clientes` 
				WHERE 
				`Id_Cliente` = @idcliente;';                    
		$bd->CreateCommand($sql);  
		$bd->AsignarParamNumero('@idcliente', $IdCliente);                   
		$datos = $bd->Consult();
		$MiCliente->IdCliente_ = $datos[0]['Id_Cliente'];
		$MiCliente->Cedula_= $datos[0]['Cedula'];
		$MiCliente->Nombres_ = $datos[0]['Nombres'];
		$MiCliente->Apellidos_ = $datos[0]['Apellidos'];
		$MiCliente->Profesion_= $datos[0]['Profesion'];
		$MiCliente->Empresa_ = $datos[0]['Empresa'];
		$MiCliente->Pais_ = $datos[0]['pais'];
		$MiCliente->Notas_ = $datos[0]['Notas'];
		$MiCliente->FechaCreacion_ = $datos[0]['Fecha_Creacion'];
		$MiCliente->Modificacion_ = $datos[0]['Ultima_Modificacion'];                
		var_dump($datos[0]);
                return $MiCliente;
		
	}
				
	/**
	 * 
	 * Crea un cliente en la base de datos
	 * @param Cliente $Cliente
	 */
	public function CrearCliente(&$Cliente){
            
		$bd = BaseDatos::getInstance();
		$sql = 'INSERT INTO  `cendendt_certificados`.`clientes` (
			`Cedula` , `Profesion` ,
			`Nombres` , `Apellidos` , `Empresa` ,
			`pais` , `Notas` , `Fecha_Creacion`)
			VALUES (
			@cedula,  
			@profesion,  
			@nombres, 
			@apellidos,  
			@empresa,  
			@pais,  
			@notas,  
			@fecha );';
				
		$bd->CreateCommand($sql);		
		
		$bd->AsignarParamCadena('@cedula', $Cliente->Cedula_);
		$bd->AsignarParamCadena('@profesion', $Cliente->Profesion_);
		$bd->AsignarParamCadena('@nombres', $Cliente->Nombres_);
		$bd->AsignarParamCadena('@apellidos', $Cliente->Apellidos_);
		$bd->AsignarParamCadena('@empresa', $Cliente->Empresa_);
		$bd->AsignarParamCadena('@pais', $Cliente->Pais_);
		$bd->AsignarParamCadena('@notas', $Cliente->Notas_);
		$bd->AsignarParamCadena('@fecha', date('Y-m-d H:m:s'));
		$bd->ExecCommand();		
	}
	
	/**
	 * 
	 * Actualiza un cliente en la base de dats
	 * @param integer $idcliente_old
	 * @param Cliente $cliente
	 */
	public function ActualizarCliente($idcliente_old,&$cliente){
	$bd = BaseDatos::getInstance();
	$sql = 'UPDATE  `cendendt_certificados`.`clientes` 
			SET  `Id_Cliente`               =  @id_cliente,
			`Cedula` 			=  @cedula,
			`Profesion`                     =  @profesion,
			`Nombres` 			=  @nombres,
			`Apellidos`                     =  @apellidos,
			`Empresa` 			=  @empresa,
			`pais` 				=  @pais,
			`Notas` 			=  @notas
 			WHERE  `clientes`.`Id_Cliente`  = @idcliente_old';
		
		$bd->CreateCommand($sql);

		$bd->AsignarParamNumero('@id_cliente', $cliente->IdCliente_);
		$bd->AsignarParamCadena('@cedula', $cliente->Cedula_);
		$bd->AsignarParamCadena('@profesion', $cliente->Profesion_);
		$bd->AsignarParamCadena('@nombres', $cliente->Nombres_);
		$bd->AsignarParamCadena('@apellidos', $cliente->Apellidos_);
		$bd->AsignarParamCadena('@empresa', $cliente->Empresa_);
		$bd->AsignarParamCadena('@pais', $cliente->Pais_);
		$bd->AsignarParamCadena('@notas', $cliente->Notas_);
		$bd->AsignarParamNumero('@idcliente_old', $idcliente_old);
		$bd->ExecCommand();
		
		}
		
		/**
		 * 
		 * Elimina un cliente en la base de datos
		 * solamemente si no tiene certificado
		 * @param intenger $id_cliente
		 */
		public  function EliminarCliente($id_cliente){
			$bd = BaseDatos::getInstance();
			
			$sql = 'DELETE 
					FROM `Clientes` 
					WHERE `Id_Cliente` = @id_cliente;';
			$bd->CreateCommand($sql);
			$bd->AsignarParamCadena('@id_cliente', $id_cliente);
			$bd->ExecCommand();
				
		}                
       
}