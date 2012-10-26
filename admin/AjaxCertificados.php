<?php   
        require_once '../config.php';

	
        require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoCertificados.class.php';
	
        $CatCertificados = new CatalogoCertificado(); 
        $certificados = $CatCertificados->ListarCertificados('Codigo', $_GET['term'], true, 15);
	
        $idClientes = array();
        foreach($certificados as $indice):                
                $idClientes[] = array( 'value' => $indice->Codigo_ . ' ' . $indice->Metodo_,
                                       'id_metodo' => $indice->IdCertificado_ ,
                                       'metodo' => $indice->Metodo_,
                                       'codigo' => $indice->Codigo_);
        endforeach;       
        
	print json_encode($idClientes);
	
?>
