<?php   
        require_once '../config.php';

	
        require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoClientes.class.php';
	

	$Clientes = new CatalogoClientes();        
        $misClientes = $Clientes->ListarClientes('Apellidos', $_GET['term'] , true , 15);        
                
        $idClientes = array();
        foreach($misClientes as $indice):                
                $idClientes[] = array('value' =>  $indice->Apellidos_ . ' ' . $indice->Nombres_,
                                      'cedula' => $indice->Cedula_,
                                      'id'     => $indice->IdCliente_,
                                      'notas'  => $indice->Notas_);
                
        endforeach;       
        
	print json_encode($idClientes);
	
?>
