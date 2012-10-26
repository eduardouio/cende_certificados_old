<?php

/**
 * 
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 * 
 * Administrador tipos de certificados implementa <catalogocertif.class>
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
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoCertificados.class.php';
require_once DIR_SYSTEM . 'controlador/helpers/cadenas.class.php';
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoEstructura.class.php';
$html = file_get_contents(DIR_SYSTEM . 'vista/template/admin/admin.html');
$form = file_get_contents(DIR_SYSTEM . 'vista/template/admin/formcertificados.html');

#######################################Manejador Principal############################

if ($_POST):

    $accion_registro = $_POST['t_accion'];


    if ($accion_registro == 'new'):
        $Func_Cadenas = new Cadenas();
        $cat_certificados = new CatalogoCertificado();
        $certificado = new Certificado();
        $certificado->Codigo_ = $Func_Cadenas->Mayusculas($_POST['codigo']);
        $certificado->Metodo_ = $Func_Cadenas->Mayusculas($_POST['metodo']);
        $certificado->Nivel_ = $Func_Cadenas->Mayusculas($_POST['nivel']);
        $cat_certificados->CrearCertificado($certificado);
        $html = '';
        $form = RenderFormulario($form, 0);
        print str_replace('{t_accion}', 'new', $form);

    elseif ($accion_registro > 0):
        $Func_Cadenas = new Cadenas();        
        $cat_certificados = new CatalogoCertificado();
        $certificado = new Certificado();
        $certificado->Codigo_ = $Func_Cadenas->Mayusculas($_POST['codigo']);
        $certificado->Metodo_ = $Func_Cadenas->Mayusculas($_POST['metodo']);
        $certificado->Nivel_ = $Func_Cadenas->Mayusculas($_POST['nivel']);
        $certificado->IdCertificado_ = $_POST['t_accion'];
        $cat_certificados->ActualizarCertificado($certificado->IdCertificado_, $certificado);
        $html = '';
        $form = RenderFormulario($form, $_POST['t_accion']);
        print str_replace('{t_accion}', $_POST['t_accion'], $form);

    elseif ($accion_registro == 'buscar'):
        $cat_certificados = new CatalogoCertificado();
        ;
        $certificados = $cat_certificados->ListarCertificados($_POST['columna'], $_POST['criterio'], true, 300);
        print RenderAdministrador($html, $certificados);
    else:
        $form = '';
        $cat_certificados = new CatalogoCertificado();
        $certificados = $cat_certificados->ListarCertificados('0', '0', '0', 100);
        print RenderAdministrador($html, $certificados);
    endif;
elseif ($_GET):
    //refresh, nuevo, editar, borrar
    $accion = $_GET['accion'];
    $id_certificado = $_GET['id'];

    switch ($accion) {
        case 'refresh':
            $form = '';
            $cat_certificados = new CatalogoCertificado();
            $certificados = $cat_certificados->ListarCertificados('0', '0', '0', 100);
            print RenderAdministrador($html, $certificados);

            break;
        case 'new':
            $html = '';
            $form = RenderFormulario($form, $id_certificado);
            print str_replace('{t_accion}', 'new', $form);
            break;
        case 'edit':
            $html = '';
            $form = RenderFormulario($form, $id_certificado);
            print str_replace('{t_accion}', $id_certificado, $form);
            break;
        case 'delete':
            $form = '';
            $cat_certificados = new CatalogoCertificado();
            $cat_certificados->EliminarCertificado($id_certificado);
            $certificados = $cat_certificados->ListarCertificados('0', '0', '0', 100);
            print RenderAdministrador($html, $certificados);
            break;
        default:
            $cat_certificados = new CatalogoCertificado();
            $certificados = $cat_certificados->ListarCertificados('0', '0', '0', 100);
            print RenderAdministrador($html, $certificados);
            break;
    }

else:
    $cat_certificados = new CatalogoCertificado();

    $certificados = $cat_certificados->ListarCertificados('0', '0', '0', 100);
    print RenderAdministrador($html, $certificados);
endif;

##################################################Administrar Render Adinistrador#################################

function RenderAdministrador($html, &$certificados) {

    $cat_campos = new CatalogoBd();
    $columnas = @$cat_campos->ObtenerColumnas('tipos_de_certificados');
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
        '{titulo}' => 'Certificados CENDE',
        '{active1}' => '',
        '{active2}' => '',
        '{active3}' => 'active',
        '{active4}' => '',
        '{seccion}' => 'Desde esta pantalla usted puede administrar los certificados que emite.',
        '{crear_registro}' => 'admincertificados.php?accion=new&id=0',
        '{listar_todos}' => 'admincertificados.php?accion=refresh&id=0',
        '{envio_form}' => 'admincertificados.php');
    $tabla = cuerpotabla($certificados);

    $html = str_replace('{tabla}', $tabla, $html);
    $html = str_replace('{seleccion_opcion}', $opciones, $html);
    foreach ($datos_render as $indice => $valor):
        $html = str_replace($indice, $valor, $html);
    endforeach;

    return $html;
}

#####-----------------------------------------

function cuerpotabla(&$certificados) {
    $tabla = '';
    $tabla = $tabla . '<table id="datos" class="table table-bordered table-striped">';
    $tabla = $tabla . '<thead><tr>';
    $tabla = $tabla . '<th>Id</th>';
    $tabla = $tabla . '<th>Código</th>';
    $tabla = $tabla . '<th>Método</th>';
    $tabla = $tabla . '<th>Nivel</th>';
    $tabla = $tabla . '<th>Fecha Creación</th>';
    $tabla = $tabla . '<th>Ult Mod</th>';
    $tabla = $tabla . '<th>Acciones</th>';
    $tabla = $tabla . '</tr></thead>';

    foreach ($certificados as $indice => $valor):
        $tabla = $tabla . '<tr><td>' . $valor->IdCertificado_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Codigo_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Metodo_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Nivel_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->FechaCreacion_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Modificacion_ . '</td>';
        $tabla = $tabla . '<td><a href="admincertificados.php?accion=delete&id=' . $valor->IdCertificado_ . '" class="btn btn-danger btn-mini pull-right"><i class="icon-trash icon-white"></i>Delete</a>';
        $tabla = $tabla . '<a href="admincertificados.php?accion=edit&id=' . $valor->IdCertificado_ . '" class="btn btn-info btn-mini pull-left"><i class="icon-edit icon-white"></i>Edit</a> ';
        $tabla = $tabla . '</td></tr>';
    endforeach;
    return $tabla;
}

##################################################Administrar Render formulario para nuevo y editar -----#################################

function RenderFormulario($form, $id_certificado) {

    if ($id_certificado == 0):
        $diccionario = array(
            'á' => '&aacute;',
            'é' => '&eacute;',
            'í' => '&iacute;',
            'ó' => '&oacute;',
            'ú' => '&uacute;',
            'ñ' => '&ntilde;',
            '{codigo}' => '',
            '{metodo}' => '',
            '{nivel}' => ''
        );
        foreach ($diccionario as $indice => $valor):
            $form = str_replace($indice, $valor, $form);
        endforeach;
        return $form;
    elseif ($id_certificado > 0):
        $cat_certificados = new CatalogoCertificado();
        ;
        $certificado = $cat_certificados->ObtenerCertificado($id_certificado);
        $diccionario = array(
            'á' => '&aacute;',
            'é' => '&eacute;',
            'í' => '&iacute;',
            'ó' => '&oacute;',
            'ú' => '&uacute;',
            'ñ' => '&ntilde;',
            '{codigo}' => $certificado->Codigo_,
            '{metodo}' => $certificado->Metodo_,
            '{nivel}' => $certificado->Nivel_);
        foreach ($diccionario as $indice => $valor):
            $form = str_replace($indice, $valor, $form);
        endforeach;
        return $form;
    endif;
}

?>
