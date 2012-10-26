<?php

/**
 * 
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 * 
 * Administrador de Clientes implementa <catalogoclientes.class>
 * <catalogoestructura.class> 
 * 
 * acciones importantes
 * GET
 * Editar-Borrar-Refrescar
 * POST
 * buscar nuevo editar
 */
session_start();
require_once '../config.php';
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoClientes.class.php';
require_once DIR_SYSTEM . 'controlador/helpers/cadenas.class.php';
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoEstructura.class.php';
$html = file_get_contents(DIR_SYSTEM . 'vista/template/admin/admin.html');
$form = file_get_contents(DIR_SYSTEM . 'vista/template/admin/formcliente.html');
#######################################Manejador Principal############################

if ($_POST):

    $accion_registro = $_POST['t_accion'];
    if ($accion_registro == 'new'):
        $Func_Cadenas = new Cadenas();
        $cat_clientes = new CatalogoClientes();
        $cliente = new Cliente();
        $cliente->Apellidos_ = $Func_Cadenas->Mayusculas($_POST['apellidos']);
        $cliente->Nombres_ = $Func_Cadenas->Mayusculas($_POST['nombres']);
        $cliente->Cedula_ = $_POST['cedula'];
        $cliente->Empresa_ = $Func_Cadenas->Mayusculas($_POST['empresa']);
        $cliente->Profesion_ = $Func_Cadenas->Mayusculas($_POST['profesion']);
        $cliente->Pais_ = $Func_Cadenas->Mayusculas($_POST['pais']);
        $cliente->Notas_ = $Func_Cadenas->Mayusculas($_POST['notas']);
        $cat_clientes->CrearCliente($cliente);
        $html = '';
        $form = RenderFormulario($form, 0);
        print str_replace('{t_accion}', 'new', $form);
    elseif ($accion_registro > 0):
        $cat_clientes = new CatalogoClientes();
        $cliente = new Cliente();
        $Func_Cadenas = new Cadenas();
        $cliente->Apellidos_ = $Func_Cadenas->Mayusculas($_POST['apellidos']);
        $cliente->Nombres_ = $Func_Cadenas->Mayusculas($_POST['nombres']);
        $cliente->Cedula_ = $_POST['cedula'];
        $cliente->Empresa_ = $Func_Cadenas->Mayusculas($_POST['empresa']);
        $cliente->Profesion_ = $Func_Cadenas->Mayusculas($_POST['profesion']);
        $cliente->Pais_ = $Func_Cadenas->Mayusculas($_POST['pais']);
        $cliente->Notas_ = $Func_Cadenas->Mayusculas($_POST['notas']);
        $cliente->IdCliente_ = $_POST['t_accion'];
        $cat_clientes->ActualizarCliente($cliente->IdCliente_, $cliente);
        $html = '';
        $form = RenderFormulario($form, $_POST['t_accion']);
        print str_replace('{t_accion}', $_POST['t_accion'], $form);

    elseif ($accion_registro == 'buscar'):
        $cat_clientes = new CatalogoClientes();
        $clientes = $cat_clientes->ListarClientes($_POST['columna'], $_POST['criterio'], true, 300);
        print RenderAdministrador($html, $clientes);
    else:
        $form = '';
        $cat_clientes = new CatalogoClientes();
        $clientes = $cat_clientes->ListarClientes('0', '0', '0', 100);
        print RenderAdministrador($html, $clientes);
    endif;
elseif ($_GET):
    //refresh, nuevo, editar, borrar
    $accion = $_GET['accion'];
    $id_cliente = $_GET['id'];

    switch ($accion) {
        case 'refresh':
            $form = '';
            $cat_clientes = new CatalogoClientes();
            $clientes = $cat_clientes->ListarClientes('0', '0', '0', 100);
            print RenderAdministrador($html, $clientes);

            break;
        case 'new':
            $html = '';
            $form = RenderFormulario($form, $id_cliente);
            print str_replace('{t_accion}', 'new', $form);
            break;
        case 'edit':
            $html = '';
            $form = RenderFormulario($form, $id_cliente);
            print str_replace('{t_accion}', $id_cliente, $form);
            break;
        case 'delete':
            $form = '';
            $cat_clientes = new CatalogoClientes();
            $cat_clientes->EliminarCliente($id_cliente);
            $clientes = $cat_clientes->ListarClientes('0', '0', '0', 100);
            print RenderAdministrador($html, $clientes);
            break;
        default:
            $cat_clientes = new CatalogoClientes();
            $clientes = $cat_clientes->ListarClientes('0', '0', '0', 100);
            print RenderAdministrador($html, $clientes);
            break;
    }

else:
    $cat_clientes = new CatalogoClientes();
    $clientes = $cat_clientes->ListarClientes('0', '0', '0', 100);
    print RenderAdministrador($html, $clientes);
endif;

##################################################Administrar Render Adinistrador#################################

function RenderAdministrador($html, &$clientes) {

    $cat_campos = new CatalogoBd();
    $columnas = @$cat_campos->ObtenerColumnas('clientes');
    $opciones = '';

    foreach ($columnas as $indice => $valor):
        $opciones = $opciones . ' <option> ' . $valor . ' </option>';
    endforeach;

    $datos_render = array(
        'á' => '&aacute;',
        'é' => '&eacute;',
        'í' => '&iacute;',
        'ó' => '&oacute;',
        'ú' => '&uacute;',
        'ñ' => '&ntilde;',
        '{t_accion}' => 'buscar',
        '{titulo}' => 'Clientes CENDE',
        '{active1}' => '',
        '{active2}' => 'active',
        '{active3}' => '',
        '{active4}' => '',
        '{seccion}' => 'Desde esta pantalla usted puede administrar sus clientes.',
        '{crear_registro}' => 'adminpersonas.php?accion=new&id=0',
        '{listar_todos}' => 'adminpersonas.php?accion=refresh&id=0',
        '{envio_form}' => 'adminpersonas.php');
    $tabla = cuerpotabla($clientes);

    $html = str_replace('{tabla}', $tabla, $html);
    $html = str_replace('{seleccion_opcion}', $opciones, $html);
    foreach ($datos_render as $indice => $valor):
        $html = str_replace($indice, $valor, $html);
    endforeach;
    return $html;
}

#####-----------------------------------------

function cuerpotabla(&$clientes) {
    $tabla = '';
    $tabla = $tabla . '<table id="datos" class="table table-bordered table-striped">';
    $tabla = $tabla . '<thead><tr>';
    //$tabla = $tabla . '<th>Id</th>';
    $tabla = $tabla . '<th>Cédula</th>';
    $tabla = $tabla . '<th>Profesión</th>';
    $tabla = $tabla . '<th>Nombres</th>';
    $tabla = $tabla . '<th>Apellidos</th>';
    $tabla = $tabla . '<th>Empresa</th>';
    $tabla = $tabla . '<th>Pais</th>';
    //$tabla = $tabla . '<th>Notas</th>';
    $tabla = $tabla . '<th>Fec. Crea.</th>';
    $tabla = $tabla . '<th>Ult. Mod.</th>';
    $tabla = $tabla . '<th>Acciones</th>';
    $tabla = $tabla . '</tr></thead>';

    foreach ($clientes as $indice => $valor):
        //$tabla = $tabla . '<tr><td>'. $valor->IdCliente_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Cedula_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Profesion_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Nombres_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Apellidos_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Empresa_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Pais_ . '</td>';
        //$tabla = $tabla . '<td>' . $valor->Notas_ .'</td>';
        $tabla = $tabla . '<td>' . $valor->FechaCreacion_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Modificacion_ . '</td>';
        $tabla = $tabla . '<td><a href="adminpersonas.php?accion=delete&id=' . $valor->IdCliente_ . '" class="btn btn-danger btn-mini pull-right"><i class="icon-trash icon-white"></i>Delete</a>';
        $tabla = $tabla . '<a href="adminpersonas.php?accion=edit&id=' . $valor->IdCliente_ . '" class="btn btn-info btn-mini pull-left"><i class="icon-edit icon-white"></i>Edit</a> ';
        $tabla = $tabla . '</td></tr>';
    endforeach;
    return $tabla;
}

##################################################Administrar Render formulario para nuevo y editar -----#################################

function RenderFormulario($form, $id_cliente) {

    if ($id_cliente == 0):
        $diccionario = array(
            'á' => '&aacute;',
            'é' => '&eacute;',
            'í' => '&iacute;',
            'ó' => '&oacute;',
            'ú' => '&uacute;',
            'ñ' => '&ntilde;',
            '{cedula_cliente}' => '',
            '{profesion}' => '',
            '{nombres}' => '',
            '{apellidos}' => '',
            '{empresa}' => '',
            '{pais}' => '',
            '{notas}' => ''
        );
        foreach ($diccionario as $indice => $valor):
            $form = str_replace($indice, $valor, $form);
        endforeach;
        return $form;
    elseif ($id_cliente > 0):
        $cat_clientes = new CatalogoClientes();
        $cliente = $cat_clientes->ObtenerCliente($id_cliente);
        $diccionario = array(
            'á' => '&aacute;',
            'é' => '&eacute;',
            'í' => '&iacute;',
            'ó' => '&oacute;',
            'ú' => '&uacute;',
            'ñ' => '&ntilde;',
            '{cedula_cliente}' => $cliente->Cedula_,
            '{profesion}' => $cliente->Profesion_,
            '{nombres}' => $cliente->Nombres_,
            '{apellidos}' => $cliente->Apellidos_,
            '{empresa}' => $cliente->Empresa_,
            '{pais}' => $cliente->Pais_,
            '{notas}' => $cliente->Notas_);
        foreach ($diccionario as $indice => $valor):
            $form = str_replace($indice, $valor, $form);
        endforeach;
        return $form;
    endif;
}

?>
