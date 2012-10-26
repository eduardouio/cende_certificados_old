<?php

/**
 * 
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.2
 * 
 * Administrador tipos de certificados implementa <catalogocertif.class>
 * <catalogoestructura.class> 
 * 
 * acciones importantes
 * GET
 * http://127.0.0.1/cende/certificados/admin/adminusuarios.php 
 * Editar-Borrar-Refrescar
 * POST
 * buscar nuevo editar
 */
session_start();
require_once '../config.php';
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoUsuarios.class.php';
require_once DIR_SYSTEM . 'controlador/helpers/cadenas.class.php';
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoEstructura.class.php';
$html = file_get_contents(DIR_SYSTEM . 'vista/template/admin/admin.html');
$form = file_get_contents(DIR_SYSTEM . 'vista/template/admin/formusuario.html');

#######################################Manejador Principal############################

if ($_POST):
    $accion_registro = $_POST['t_accion'];

    //preparamos la pantalla para ingresar un n uevo registro
    if ($accion_registro == 'new'):
        $Func_Cadenas = new Cadenas();
        $cat_usuarios = new CatalogoUsuarios();
        $usuario = new Usuario();
        $usuario->Usuario_ = $_POST['usuario'];
        $usuario->Nombres_ = $Func_Cadenas->Mayusculas($_POST['nombres']);
        $usuario->Pass_ = $_POST['pass'];
        print $cat_usuarios->CrearUsuario($usuario);
        $html = '';
        $form = RenderFormulario($form, 0);
        print str_replace('{t_accion}', 'new', $form);

    elseif ($accion_registro > 0):
        $cat_usuarios = new CatalogoUsuarios();
        $usuario = new Usuario();
        $usuario->Usuario_ = $_POST['usuario'];
        $usuario->Nombres_ = $Func_Cadenas->Mayusculas($_POST['nombres']);
        $usuario->Pass_ = $_POST['pass'];
        $cat_usuarios->ActualizarUsuario($usuario->Usuario_, $usuario);
        $html = '';
        $form = RenderFormulario($form, $_POST['t_accion']);
        print str_replace('{t_accion}', $_POST['t_accion'], $form);

    elseif ($accion_registro == 'buscar'):
        $cat_usuarios = new CatalogoUsuarios();
        $usuarios = $cat_usuarios->ListarUsuarios($_POST['columna'], $_POST['criterio'], true, 300);
        print RenderAdministrador($html, $usuarios);
    else:
        $form = '';
        $cat_usuarios = new CatalogoUsuarios();
        $usuarios = $cat_usuarios->ListarUsuarios('0', '0', '0', 100);
        print RenderAdministrador($html, $usuarios);
    endif;
elseif ($_GET):
    //refresh, nuevo, editar, borrar
    $accion = $_GET['accion'];
    $id_usuario = $_GET['id'];

    switch ($accion) {
        case 'refresh':
            $form = '';
            $cat_usuarios = new CatalogoUsuarios();
            $usuarios = $cat_usuarios->ListarUsuarios('0', '0', '0', 100);
            print RenderAdministrador($html, $usuarios);

            break;
        case 'new':
            $html = '';
            $form = RenderFormulario($form, $id_usuario);
            print str_replace('{t_accion}', 'new', $form);
            break;
        case 'edit':
            $html = '';
            $form = RenderFormulario($form, $id_usuario);
            print str_replace('{t_accion}', $id_usuario, $form);
            break;
        case 'delete':
            $form = '';
            $cat_usuarios = new CatalogoUsuarios();
            $cat_usuarios->EliminarUsuario($id_usuario);
            $usuarios = $cat_usuarios->ListarUsuarios('0', '0', '0', 100);
            print RenderAdministrador($html, $usuarios);
            break;
        default:
            $cat_usuarios = new CatalogoUsuarios();
            $usuarios = $cat_usuarios->ListarUsuarios('0', '0', '0', 100);
            print RenderAdministrador($html, $usuarios);
            break;
    }

else:
    $cat_usuarios = new CatalogoUsuarios();
    $usuarios = $cat_usuarios->ListarUsuarios('0', '0', '0', 100);
    print RenderAdministrador($html, $usuarios);
endif;

#################################################Administrar Render Adinistrador#################################

function RenderAdministrador($html, &$usuarios) {

    $cat_campos = new CatalogoBd();
    $columnas = @$cat_campos->ObtenerColumnas('usuarios');
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
        '{titulo}' => 'Usuarios CENDE',
        '{active1}' => '',
        '{active2}' => '',
        '{active3}' => '',
        '{active4}' => 'active',
        '{seccion}' => 'Administrar Usuarios del sistema.',
        '{crear_registro}' => 'adminusuarios.php?accion=new&id=0',
        '{listar_todos}' => 'adminusuarios.php?accion=refresh&id=0',
        '{envio_form}' => 'adminusuarios.php');
    $tabla = cuerpotabla($usuarios);

    $html = str_replace('{tabla}', $tabla, $html);
    $html = str_replace('{seleccion_opcion}', $opciones, $html);
    foreach ($datos_render as $indice => $valor):
        $html = str_replace($indice, $valor, $html);
    endforeach;

    return $html;
}

#####-----------------------------------------

function cuerpotabla(&$usuarios) {
    $tabla = '';
    $tabla = $tabla . '<table id="datos" class="table table-bordered table-striped">';
    $tabla = $tabla . '<thead><tr>';
    $tabla = $tabla . '<th>Nombres</th>';
    $tabla = $tabla . '<th>Usuario</th>';
    $tabla = $tabla . '<th>Contraseña</th>';
    $tabla = $tabla . '<th>Fecha Creación</th>';
    $tabla = $tabla . '<th>Ult Mod</th>';
    $tabla = $tabla . '<th>Acciones</th></thead>';
    foreach ($usuarios as $indice => $valor):
        $tabla = $tabla . '<tr><td>' . $valor->Nombres_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Usuario_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Pass_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->FechaCreacion_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Modificacion_ . '</td>';
        $tabla = $tabla . '<td><a href="adminusuarios.php?accion=delete&id=' . $valor->Usuario_ . '" class="btn btn-danger btn-mini pull-right"><i class="icon-trash icon-white"></i>Delete</a>';
        $tabla = $tabla . '<a href="adminusuarios.php?accion=edit&id=' . $valor->Usuario_ . '" class="btn btn-info btn-mini pull-left"><i class="icon-edit icon-white"></i>Edit</a> ';
        $tabla = $tabla . '</td></tr>';
    endforeach;

    return $tabla;
}

##################################################Administrar Render formulario para nuevo y editar -----#################################

function RenderFormulario($form, $id_usuario) {

    if ($id_usuario == 0):
        $diccionario = array(
            'á' => '&aacute;',
            'é' => '&eacute;',
            'í' => '&iacute;',
            'ó' => '&oacute;',
            'ú' => '&uacute;',
            'ñ' => '&ntilde;',
            '{nombres}' => 'nombres',
            '{usuario}' => 'usuario',
            '{pass}' => 'pass'
        );
        foreach ($diccionario as $indice => $valor):
            $form = str_replace($indice, $valor, $form);

        endforeach;
        return $form;
    elseif ($id_usuario > 0):
        $cat_usuarios = new CatalogoUsuarios();
        ;
        $usuario = $cat_usuarios->ObtenerCertificado($id_usuario);
        $diccionario = array(
            'á' => '&aacute;',
            'é' => '&eacute;',
            'í' => '&iacute;',
            'ó' => '&oacute;',
            'ú' => '&uacute;',
            'ñ' => '&ntilde;',
            '{nombres}' => $usuario->Codigo_,
            '{usuario}' => $usuario->Metodo_,
            '{pass}' => $usuario->Nivel_);
        foreach ($diccionario as $indice => $valor):
            $form = str_replace($indice, $valor, $form);
        endforeach;
        return $form;
    endif;
}

?>
