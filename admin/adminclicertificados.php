<?php

/**
 * 
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 * 
 * Administrador los clientes certificados implementa las clases <calaogoclientescertificados.class>
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
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoClientesCertificados.class.php';
require_once DIR_SYSTEM . 'controlador/helpers/cadenas.class.php';
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoClientes.class.php';
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoCertificados.class.php';
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoEstructura.class.php';
$html = file_get_contents(DIR_SYSTEM . 'vista/template/admin/admin.html');
$form = file_get_contents(DIR_SYSTEM . 'vista/template/admin/formclicertificado.html');

#######################################Manejador Principal############################

if ($_POST):
    //crea un nuevo registro
    $accion_registro = $_POST['t_accion'];    
    if ($accion_registro == 'new'):
        $Fun_Cadenas = new Cadenas();
        $cat_cli_certificados = new CatalogoClientesCertificados();
        $cat_cliente = new CatalogoClientes();
        $cat_certificado = new CatalogoCertificado();        
        $cliente_certificado = new ClienteCertificado();
        $cliente_certificado->IdRegistro_ = $Fun_Cadenas->Mayusculas($_POST['id_registro']);
        $cliente_certificado->MiCliente_ = $cat_cliente->ObtenerCliente($_POST['id_cliente']);
        $cliente_certificado->MiCertificado_ = $cat_certificado->ObtenerCertificado($_POST['id_certificado']);
        $cliente_certificado->Edicion_ = $Fun_Cadenas->Mayusculas($_POST['edicion']);
        $cliente_certificado->FechaCertificacion_ = $_POST['fecha_certificacion'];
        $cliente_certificado->FechaVencimiento_ = $_POST['fecha_vencimiento'];
        $cliente_certificado->ExaGeneral_ = $_POST['exa_general'];
        $cliente_certificado->ExaEspecifica_ = $_POST['exa_especifica'];
        $cliente_certificado->ExaParcial_ = $_POST['exa_parcial'];
        $cliente_certificado->Examen_ = $Fun_Cadenas->Mayusculas($_POST['examen']);
        $cat_cli_certificados->CrearClienteCertificado($cliente_certificado);
        $html = '';
        $form = RenderFormulario($form, 0);
        print str_replace('{t_accion}', 'new', $form);
        //actualiza un registro existente
    elseif (( strlen($accion_registro) > 4)&&($accion_registro != 'buscar')):                 
        $cat_cli_certificados = new CatalogoClientesCertificados();
        $cat_cliente = new CatalogoClientes();
        $cat_certificado = new CatalogoCertificado();
        $Fun_Cadenas = new Cadenas();
        $cliente_certificado = new ClienteCertificado();        
        $cliente_certificado->IdRegistro_ = $Fun_Cadenas->Mayusculas($_POST['t_accion']);
        $cliente_certificado->MiCliente_ = $cat_cliente->ObtenerCliente($_POST['id_cliente']);
        $cliente_certificado->MiCertificado_ = $cat_certificado->ObtenerCertificado($_POST['id_certificado']);
        $cliente_certificado->Edicion_ = $Fun_Cadenas->Mayusculas($_POST['edicion']);
        $cliente_certificado->FechaCertificacion_ = $_POST['fecha_certificacion'];
        $cliente_certificado->FechaVencimiento_ = $_POST['fecha_vencimiento'];
        $cliente_certificado->ExaGeneral_ = $_POST['exa_general'];
        $cliente_certificado->ExaEspecifica_ = $_POST['exa_especifica'];
        $cliente_certificado->ExaParcial_ = $_POST['exa_parcial'];
        $cliente_certificado->Examen_ = $Fun_Cadenas->Mayusculas($_POST['examen']);        
        //$cliente_certificado->FechaCreacion_          = $_POST['nivel'];
        //$cliente_certificado->Modificacion_		= $_POST['nivel'];	        
        $cat_cli_certificados->ActualizarClienteCertificado($cliente_certificado->IdRegistro_,$cliente_certificado->MiCertificado_->IdCertificado_,$cliente_certificado);
        $html = '';
        $form = RenderFormulario($form, $_POST['t_accion']);
        print str_replace('{t_accion}', $_POST['t_accion'], $form);

    elseif ($accion_registro == 'buscar'):
        $cat_cli_certificados = new CatalogoClientesCertificados();
        $cliente_certificados = $cat_cli_certificados->ListarClientesCertificados($_POST['columna'], $_POST['criterio'], true, 300);
        print RenderAdministrador($html, $cliente_certificados);
    else:
        $form = '';
        $cat_cli_certificados = new CatalogoClientesCertificados();
        $cliente_certificados = $cat_cli_certificados->ListarClientesCertificados('0', '0', '0', 100);
        print RenderAdministrador($html, $cliente_certificados);
    endif;
elseif ($_GET):    
    $accion = $_GET['accion'];
    @$certifcado = $_GET['cert'];
    $id_certificado = $_GET['id'];
    //comprobamos que el certificado exista
    empty($certifcado)?$certifcado=0:1==1;    
    switch ($accion) {
        case 'refresh':
            $form = '';
            $cat_cli_certificados = new CatalogoClientesCertificados();
            $cliente_certificados = $cat_cli_certificados->ListarClientesCertificados('0', '0', '0', 100);
            print RenderAdministrador($html, $cliente_certificados);

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
            $cat_cli_certificados = new CatalogoClientesCertificados();
            $cat_tipo_certificados = new CatalogoCertificado();
            $id_certificado_old = 0;
            $id_tipo_certificado = $cat_tipo_certificados->ListarCertificados('Codigo',"= '" . $certifcado . "'" ,false,1);            
            foreach ($id_tipo_certificado as $indice => $valor):
                    $id_certificado_old = $valor->IdCertificado_;
            endforeach;            
            $cat_cli_certificados->EliminarClienteCertificado($id_certificado,$id_certificado_old);            
            $cliente_certificados = $cat_cli_certificados->ListarClientesCertificados('0', '0', '0', 100);
            print RenderAdministrador($html, $cliente_certificados);
            break;
        default:
            $cat_cli_certificados = new CatalogoClientesCertificados();
            $cliente_certificados = $cat_cli_certificados->ListarClientesCertificados('0', '0', '0', 100);
            print RenderAdministrador($html, $cliente_certificados);
            break;
    }

else:
    $cat_cli_certificados = new CatalogoClientesCertificados();
    $cliente_certificados = $cat_cli_certificados->ListarClientesCertificados('0', '0', '0', 100);
    print RenderAdministrador($html, $cliente_certificados);
endif;

##################################################Administrar Render Adinistrador#################################

function RenderAdministrador($html, &$cliente_certificados) {
    $cat_campos = new CatalogoBd();
    $columnas = @$cat_campos->ObtenerColumnas('clientes_certificados');
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
        '{titulo}' => 'Personas Certificadas por CENDE',
        '{active1}' => 'active',
        '{active2}' => '',
        '{active3}' => '',
        '{active4}' => '',
        '{seccion}' => 'Desde esta pantalla usted puede administrar las personas que certifica.',
        '{crear_registro}' => 'adminclicertificados.php?accion=new&id=0',
        '{listar_todos}' => 'adminclicertificados.php?accion=refresh&id=0',
        '{envio_form}' => 'adminclicertificados.php');
    $tabla = cuerpotabla($cliente_certificados);
    $html = str_replace('{tabla}', $tabla, $html);
    $html = str_replace('{seleccion_opcion}', $opciones, $html);
    foreach ($datos_render as $indice => $valor):
        $html = str_replace($indice, $valor, $html);
    endforeach;
    return $html;
}

#####-----------------------------------------

function cuerpotabla(&$cliente_certificados) {
    $tabla = '';
    $tabla = $tabla . '<table id="datos" class="table table-bordered table-striped">';
    $tabla = $tabla . '<thead><tr>';
    $tabla = $tabla . '<th>Registro</th>';
    $tabla = $tabla . '<th>Cliente</th>';
    $tabla = $tabla . '<th>Certificado</th>';
    //$tabla = $tabla . '<th>Edisión</th>';
    $tabla = $tabla . '<th>Fech. Certific</th>';
    $tabla = $tabla . '<th>Fech. Venc.</th>';
    $tabla = $tabla . '<th>Exa. Gen.</th>';
    $tabla = $tabla . '<th>Exa. Esp.</th>';
    $tabla = $tabla . '<th>Exa. Par.</th>';
    $tabla = $tabla . '<th>Examen</th>';
    $tabla = $tabla . '<th>Fecha Creación</th>';
    $tabla = $tabla . '<th>Ult Mod</th>';
    $tabla = $tabla . '<th>Acciones</th>';
    $tabla = $tabla . '</tr></thead>';

    foreach ($cliente_certificados as $indice => $valor):
        $tabla = $tabla . '<tr><td>' . $valor->IdRegistro_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->MiCliente_->Apellidos_ . ' ' . $valor->MiCliente_->Nombres_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->MiCertificado_->Codigo_ . '</td>';
        //$tabla = $tabla . '<td>' .	$valor->Edicion_ .'</td>';
        $tabla = $tabla . '<td>' . $valor->FechaCertificacion_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->FechaVencimiento_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->ExaGeneral_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->ExaEspecifica_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->ExaParcial_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Examen_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->FechaCreacion_ . '</td>';
        $tabla = $tabla . '<td>' . $valor->Modificacion_ . '</td>';
        $tabla = $tabla . '<td><a href="adminclicertificados.php?accion=delete&id=' . $valor->IdRegistro_ . '&cert='.$valor->MiCertificado_->Codigo_ .'" class="btn btn-danger btn-mini pull-right"><i class="icon-trash icon-white"></i>Delete</a>';
        $tabla = $tabla . '<a href="adminclicertificados.php?accion=edit&id=' . $valor->IdRegistro_ . '&cert=' .$valor->MiCertificado_->Codigo_ . '" class="btn btn-info btn-mini pull-left"><i class="icon-edit icon-white"></i>Edit</a> ';
        $tabla = $tabla . '</td></tr>';
    endforeach;
    return $tabla;
}

##################################################Administrar Render formulario para nuevo y editar -----#################################

function RenderFormulario($form, $id_certificado) {        
    if ($id_certificado == '0'):
        $diccionario = array(
            'á' => '&aacute;',
            'é' => '&eacute;',
            'í' => '&iacute;',
            'ó' => '&oacute;',
            'ú' => '&uacute;',
            'ñ' => '&ntilde;',
            '{id_registro}' => '',
            '{id_cliente}' => '',
            '{id_certificado}' => '',
            '{edicion}' => '',
            '{fecha_certificacion}' => '',
            '{fecha_vencimiento}' => '',
            '{exa_general}' => '',
            '{exa_especifica}' => '',
            '{exa_parcial}' => '',
            '{examen}' => '',
            // '{exa_parcial}'=>'',	
            '{codigo_certificado}' => '',
            '{nombre_certificado}' => '',
            '{nombres_cliente}' => '',
            '{ci_cliente}' => ''
        );
        foreach ($diccionario as $indice => $valor):
            $form = str_replace($indice, $valor, $form);
        endforeach;
        return $form;
    elseif (strlen($id_certificado) > 9):        
        $cat_cli_certificados = new CatalogoClientesCertificados();
        $cliente_certificado = $cat_cli_certificados->ListarClientesCertificados('Id_Registro', " = '" . $id_certificado . "'", false, 1);        
        $diccionario = array(
            'á' => '&aacute;',
            'é' => '&eacute;',
            'í' => '&iacute;',
            'ó' => '&oacute;',
            'ú' => '&uacute;',
            'ñ' => '&ntilde;',
            '{id_registro}' => $cliente_certificado->IdRegistro_,
            '{id_cliente}' => $cliente_certificado->MiCliente_->IdCliente_,
            '{id_certificado}' => $cliente_certificado->MiCertificado_->IdCertificado_,
            '{edicion}' => $cliente_certificado->Edicion_,
            '{fecha_certificacion}' => $cliente_certificado->FechaCertificacion_,
            '{fecha_vencimiento}' => $cliente_certificado->FechaVencimiento_,
            '{exa_general}' => $cliente_certificado->ExaGeneral_,
            '{exa_especifica}' => $cliente_certificado->ExaEspecifica_,
            '{exa_parcial}' => $cliente_certificado->ExaParcial_,
            '{examen}' => $cliente_certificado->Examen_,
            '{codigo_certificado}' => $cliente_certificado->MiCertificado_->Codigo_,
            '{nombre_certificado}' => $cliente_certificado->MiCertificado_->Metodo_,
            '{nombres_cliente}' => '',
            '{ci_cliente}' => $cliente_certificado->MiCliente_->Cedula_,
            '{nombres_cliente}' => $cliente_certificado->MiCliente_->Apellidos_ . ' ' . $cliente_certificado->MiCliente_->Nombres_
        );
        foreach ($diccionario as $indice => $valor):
            $form = str_replace($indice, $valor, $form);
        endforeach;
        return $form;
    endif;
}

?>
