<?php   
        require_once '../config.php';

	
        require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoClientes.class.php';
	

	$Clientes = new CatalogoClientes();        
        $misClientes = $Clientes->ListarClientes('Apellidos', $_GET['term'] , true , 15);        
                
        $idClientes = array();
        foreach($misClientes as $indice):                
                $idClientes[]['value'] =  $indice->IdCliente_ . '.' . $indice->Apellidos_ . ' ' . $indice->Nombres_;
                
        endforeach;       
        
	print json_encode($idClientes);
	
?>	