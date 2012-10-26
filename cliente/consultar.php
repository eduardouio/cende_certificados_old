<?php 
        require '../config.php';
        
        
	require_once  DIR_SYSTEM . 'controlador/catalogos/CatalogoClientesCertificados.class.php';
	$html = file_get_contents( DIR_SYSTEM . 'vista/template/cliente/cliente.html');
        
	
	$CatClientes = new CatalogoClientes();
	$CatCertificados = new CatalogoClientesCertificados();
        $GLOBALS['certificado'] = false;

        
	
##############################################################administrador de la oagina##########################
                /**     
		 * controlador principal de cliente
		 */
		
        if ($_POST):		
		$criterio = $_POST['criterio'];	
		$opcion = $_POST['opcion'];	
		
                       if  ($opcion == 'registro'):                                        
                                        //CEN-CC-001
                                        $id_cliente = 0;
					$miCertificados = $CatCertificados->ListarClientesCertificados('Id_Registro', "= '$criterio'",false,1);					                                        
						if (!empty ($miCertificados)):
								$miCliente = $CatClientes->ObtenerCliente($id_cliente);  
                                                                var_dump($miCliente);
								$certCliente = $CatCertificados->ListarCertificadosCliente($miCliente->IdCliente_);					
								MostrarDatos($certCliente, $miCliente, $html);				
						else:
								noResultado($html);
						endif;
					
										
				elseif ($opcion == 'cedula'):
                    //1175455480
					$miCliente = $CatClientes->ListarClientes('Cedula',"= '$criterio'",false,1);					
					if((!empty($miCliente)) && ($miCliente->IdCliente_ <> NULL)):
						$misCertificados = $CatCertificados->ListarCertificadosCliente($miCliente->IdCliente_);                                        
						MostrarDatos($misCertificados, $miCliente, $html);
					else:
						noResultado($html);
					endif;
					
				
				elseif ($opcion == 'apellidos'):
					//3.Valdez Ramon					
					$indice = strpos($criterio,'.');
                    $prefijo =  substr($criterio,0,$indice);
                    $sufijo = substr($criterio,$indice+strlen('.'));
					if ($prefijo > 0):
                    $certificados = $CatCertificados->ListarCertificadosCliente($prefijo);					
					else:
					$prefijo = 0;
					endif;
					if ((!empty($certificados)) && $prefijo > 0):
						$cliente = $CatClientes->ObtenerCliente($prefijo);
						MostrarDatos($certificados,$cliente,$html);    				
					else:
						noResultado($html);
					endif;										
				else:
					$html = str_replace('{alerta}', '' ,$html);
					$html = str_replace('{Cliente}', 'Ingrese un Dato' ,$html);
					$html = str_replace('{tabla}', '',$html);
					$html = str_replace('{Datos_Extras}', 'Seleccione una opcion e ingrese un dato', $html);
					Render($html);					
			endif;
			
			else:	
					$html = str_replace('{alerta}', '' ,$html);
                                        $html = str_replace('{Cliente}', 'Ingrese un Dato' ,$html);
					$html = str_replace('{tabla}', '',$html);
					$html = str_replace('{Datos_Extras}', 'Seleccione una opcion e ingrese un dato', $html);
					Render($html);
								
	endif;
	####################################funcionalidades de la pagina #############################################
	function Render($html){                
		$caracter = array(
					  'á'=>'&aacute;',
					  'é'=>'&eacute;',
					  'í'=>'&iacute;',
					  'ó'=>'&oacute;',
					  'ú'=>'&uacute;',
					  'ñ'=>'&ntilde;');
	
	foreach ($caracter as $vocal => $valor):
		$html = str_replace($vocal, $valor, $html);		
	endforeach;
	print $html;
	}
	
	function MostrarDatos(&$certificados, &$cliente,$html){
						//var_dump($certificados)	;
                                                $tabla = '';
    						$tabla =  '<table class="table table-striped">';
    						$tabla = $tabla . '<thead><tr>';
    						$tabla = $tabla . '<th>Registro</th>';
//    						$tabla = $tabla . '<th>Nombre</th>';
    						$tabla = $tabla . '<th>Método</th>';
    						$tabla = $tabla . '<th>Fecha Certificación</th>';
    						$tabla = $tabla . '<th>Fecha Recertificación</th>';
    						$tabla = $tabla . '</tr></thead>';   		                                                                
								$class = 'class="1"';
  			  					foreach ($certificados as $certificado => $valor):                                                                                                                                                                                                                        
                                                                        
                                                                    if (Validez($valor->Vencimiento_) == true):
                                                                            $class = 'class="0"';                                                                    
                                                                    else:                                 
                                                                            $class = 'class="novalido"';
                                                                            $GLOBALS['certificado'] = true;
                                                                    endif;                                                                    
									$tabla = $tabla . '<tbody><tr ' . $class . '><td>';
									$tabla = $tabla . 	$valor->IdRegistro_;
									$tabla = $tabla . '</td><td>';
									$tabla = $tabla . 	$valor->MiCertificado_->Codigo_;
									$tabla = $tabla . '</td><td>';
									$tabla = $tabla . 	$valor->FechaCertificacion_;
									$tabla = $tabla . '</td><td>';
									$tabla = $tabla . 	$valor->FechaVencimiento_;
									$tabla = $tabla . '</td><td>';
								/*	$tabla = $tabla .   $valor->ExaGeneral_;
									$tabla = $tabla . '</td><td>';
									$tabla = $tabla .   $valor->ExaEspecifica_;
									$tabla = $tabla . '</td><td>';
									$tabla = $tabla . 	$valor->ExaParcial_;
									$tabla = $tabla . '</td><td>';
									$tabla = $tabla . 	$valor->Examen_;
									$tabla = $tabla . '</td><td>';*/
									$tabla = $tabla . '</td> </tr>';
			 					endforeach;
	
							$tabla = $tabla . '</table>';
							$cliente_ = /*$cliente->Profesion_ . */'Mr. ' . $cliente->Apellidos_ . ' ' . $cliente->Nombres_ . ' - Company  '.$cliente->Empresa_.' <span class="label label-info">'. $cliente->Pais_ .'</span>';
							$datos_extras = count($certificados) . ' Certificados emitidos por CENDE';
							$html = str_replace('{Cliente}', $cliente_ ,$html);
							$html = str_replace('{tabla}', $tabla,$html);
                                                        if ($GLOBALS['certificado'] == true):
                                                            $alerta = ' <p>&nbsp;</p> 
                                                                    <div class="alert"> 
                                                                    Uno o mas de un certificado en este listado se encuentra vencido, si desea 
                                                                    renovarlo comuníquese con <a href="http://cendendt.com/contactenos.html">nosotros</a> 
                                                                    para recibir la información necesaria Tel: +593 (02) 2226965 o si desea escríbanos 
                                                                    un mail a <span>info@cendendt.com </span></div>';
                                                            else:
                                                                $alerta = '';
                                                        endif;
							
                                                        $html = str_replace('{alerta}', $alerta, $html);
							$html = str_replace('{Datos_Extras}', $datos_extras, $html);							
							$mes = array(
							'January'=>'Enero',
							'February'=>'Febrero',
							'March'=>'Marzo',
							'April'=>'Abril',
							'May'=>'Mayo',
							'June'=>'Junio',
							'July'=>'Julio',
							'August'=>'Agosto',
							'September'=>'Septiembre',
							'October'=>'Octubre',
							'November'=>'Noviembre',
							'December'=>'Diciembre');
							foreach ($mes as $idice => $valor):
								$html = str_replace($idice, $valor, $html);
							endforeach;							
							Render($html);
	}
	
        /**
         * El mensaje que se muestra cuando no hay respuesta a la consula
         * @param string $html  pagina html en una variable
         */
	function noResultado($html){
		$alerta = ' <p>&nbsp;</p>
					<p>&nbsp;</p> 
					<div class="alert"> 
					Si su certificado no se encuentra en este listado o si desea obtener uno comuniquese con 
					<a href="http://cendendt.com/contactenos.html">nosotros</a> para recibir la información necesaria 
					Tel: +593 (02) 2226965 o si desea escribanos un mail a <span>info@cendendt.com </span></div>';
		$html = str_replace('{Cliente}', 'No hay resultado para esta consulta' ,$html);
		$html = str_replace('{tabla}', '',$html);
		$html = str_replace('{Datos_Extras}', 'Elija una de las opciones para hacer una nueva consulta', $html);
		$html = str_replace('{alerta}',$alerta,$html);
		Render($html);
	}
          /**
         * Comprueba la validez de un certificado y la almacena en una global
         * @param date(yyyy-mm-dd) $vence 
         * @return boolean true el certificado vale false no vale
         */
        function Validez($vence){             
            $valido = false;
            $anio = Date("Y");
            $mes  = Date("m");            
            $anio_venc = substr($vence,0,4);
            $mes_venc  = substr($vence,5,2);
                        
                if($anio < $anio_venc):
                        $valido = true;
                elseif($anio == $anio_venc):
                    if($mes <= $mes_venc):
                        $valido = true;
                    endif; 
                else:        
                    $valido= false;
            endif; 
            
            return $valido;
    }
 
        
	
?>
