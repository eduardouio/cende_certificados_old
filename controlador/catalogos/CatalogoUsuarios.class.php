<?php
/**
 * 
 * Acciones sobre los objetos tipo usuario
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 *
 */
require_once DIR_SYSTEM . 'modelo/modelo.class.php';
require_once DIR_SYSTEM . 'controlador/clases/Usuario.class.php';

class CatalogoUsuarios{

	/**
	 * 
	 * Obtiene un usuario
	 * @param String $idusuario
	 * @return Usuario
	 */
		public function ObtenerUsuario($idusuario){
		$bd = BaseDatos::getInstance();
		$sql = 'SELECT 
				`Usuario`,`Pass`,`Nombres`, 
				`Fecha_Creacion`, `Ultima_Modificacion` 
				FROM `usuarios` 
				WHERE `Usuario` = @usuario';
		$bd->CreateCommand($sql);
		$bd->AsignarParamCadena('@usuario', $idusuario);
		$datos = $bd->Consult();
		
		$miuser = new Usuario();
		$miuser->Usuario_ = $datos[0]['Usuario'];
		$miuser->Pass_ = $datos[0]['Pass'];
		$miuser->Nombres_ = $datos[0]['Nombres'];
		$miuser->FechaCreacion_ = $datos[0]['Fecha_Creacion'];
		$miuser->Modificacion_ = $datos[0]['Ultima_Modificacion'];
		return $miuser;
	}
	
	/**
	 * 
	 * Crea un usuario despues de recibir uno por parametro
	 * @param Usuario $Usuario
	 */
	public function CrearUsuario(&$Usuario){
		$bd = BaseDatos::getInstance();
                
		$sql = 'INSERT INTO `usuarios`
		(`Usuario`, `Pass`, `Nombres`, 
		`Fecha_Creacion`)
		 VALUES (
		 @usuario,@pass,@nombres,@fecha)';
		$bd->CreateCommand($sql);
		$bd->AsignarParamCadena('@usuario', $Usuario->Usuario_);
		$bd->AsignarParamCadena('@pass', $Usuario->Pass_ );
		$bd->AsignarParamCadena('@nombres', $Usuario->Nombres_);
		$bd->AsignarParamCadena('@fecha', date('Y-m-d H:m:s'));
		$bd->ExecCommand();		
	}
	
	/**
	 * 
	 * Actualiza los datos de un usuario
	 * @param String $usuariold
	 * @param Usuario $Usuario
	 */
	public function ActualizarUsuario($usuariold,&$Usuario){
	$bd = BaseDatos::getInstance();
	$sql = 'UPDATE `usuarios` 
			SET `Usuario`= @usuario,
			`Pass`= @pass,
			`Nombres`= @nombres
			WHERE `Usuario` = @usuario_old ;';
		$bd->CreateCommand($sql);

		$bd->AsignarParamCadena('@usuario', $Usuario->Usuario_);
		$bd->AsignarParamCadena('@pass', $Usuario->Pass_ );
		$bd->AsignarParamCadena('@nombres', $Usuario->Nombres_);
		$bd->AsignarParamCadena('@usuario_old', $usuariold);
		$bd->ExecCommand();
		
		}
		/**
		 * 
		 * Elimina un usuario de la base de datos
		 * @param Stgring $id_usuario
		 */
		public function EliminarUsuario($id_usuario){
			$bd = BaseDatos::getInstance();
			$sql = 'DELETE 
					FROM `usuarios` 
					WHERE `Usuario` = @usuario;';
			$bd->CreateCommand($sql);
			$bd->AsignarParamCadena('@usuario', $id_usuario);
			$bd->ExecCommand();
				
		}

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
        public function ListarUsuarios($columna,$condicion,$like,$limite){
		
		$bd = BaseDatos::getInstance();
                $condicion_compuesta = false;                
		$sql = 'SELECT 
				`Usuario`,`Pass`,`Nombres`, 
				`Fecha_Creacion`, `Ultima_Modificacion` 
				FROM `usuarios`';
                
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
		$Usuarios = array();
		for ($i = 0; $i < $rows - 1  ;$i++ ):
		
                    foreach ($datos[$i] as $valor):
                        $miuser = new Usuario();
                        $miuser->Usuario_ = $datos[$i]['Usuario'];
                        $miuser->Pass_ = $datos[$i]['Pass'];
                        $miuser->Nombres_ = $datos[$i]['Nombres'];
                        $miuser->FechaCreacion_ = $datos[$i]['Fecha_Creacion'];
                        $miuser->Modificacion_ = $datos[$i]['Ultima_Modificacion'];
                        $Usuarios[$i]= $miuser;
                    endforeach;
		endfor;
		return $Usuarios;		
	}

}