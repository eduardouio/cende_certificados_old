<?php

        require_once '../config.php';
	
        require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoClientesCertificados.class.php';

		$Certificados = new CatalogoClientesCertificados();	
		$misCertificados = $Certificados->ListarClientesCertificados('id_Registro',$_GET['term'],true,15);
        $id_registros = array();
        
        foreach($misCertificados as $indice ):		   
           $id_registros[]['value'] =  $indice->IdRegistro_;            
        endforeach;
                
                	
	print json_encode($id_registros);
	
?>	