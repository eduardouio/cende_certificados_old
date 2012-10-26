<?php
/**
 *ejemplo practico para validar a una clase 
 */

require_once 'modelo.class.php';

$bd = BaseDatos::getInstance();

    $bd->CreateCommand('select * from usuarios;');

$res = $bd->Consult();

if ($res[''] == NULL):
    print 'el array esta vacio o no tiene asignado un valor e la primera pos    ';
else:
    var_dump($res);
endif;





?>
