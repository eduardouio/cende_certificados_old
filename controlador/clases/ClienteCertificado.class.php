<?php

/**
 * 
 * Objeto tipo Cliente certificado
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.2
 *
 */
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoClientes.class.php';
require_once DIR_SYSTEM . 'controlador/catalogos/CatalogoCertificados.class.php';

class ClienteCertificado {

    public $IdRegistro_;
    public $MiCliente_;
    public $MiCertificado_;
    public $Edicion_;
    public $FechaCertificacion_;
    public $FechaVencimiento_;
    public $ExaGeneral_;
    public $ExaEspecifica_;
    public $ExaParcial_;
    public $Examen_;
    public $FechaCreacion_;
    public $Modificacion_;
    public $Vencimiento_;

    /**
     * 
     * Crea un objeto vacio
     */
    public function __construct() {
        
        $this->Clave_ = NULL;
        $this->IdRegistro_ = NULL;
        $this->MiCliente_ = NULL;
        $this->MiCertificado_ = NULL;
        $this->Edicion_ = NULL;
        $this->FechaCertificacion_ = NULL;
        $this->FechaVencimiento_ = NULL;
        $this->ExaGeneral_ = NULL;
        $this->ExaEspecifica_ = NULL;
        $this->ExaParcial_ = NULL;
        $this->Examen_ = NULL;
        $this->FechaCreacion_ = NULL;
        $this->Modificacion_ = NULL;
        $this->Vencimiento_ = NULL;
    }

    /**
     * crea un onjeto completo tipo cliente Certificados
     * @param int $id identificador del cliete
     * @param obj $micliente
     * @param obj $micertificado
     * @param string $edicion fecha de edicion del certifiado
     * @param datetime $fechaCertificado  fecha del certificado
     * @param datetime $fechaVenceimiento  
     * @param int $exaGeneral
     * @param int $exaEspecifica
     * @param int $exaParcial
     * @param string $examen
     * @param datetime $fechaCreacion
     * @param datetime $modificacion 
     */
    public function Nuevo($id, $micliente, $micertificado, $edicion, $fechaCertificado, $fechaVenceimiento, $exaGeneral, $exaEspecifica, $exaParcial, $examen, $fechaCreacion, $modificacion, $vencimiento) {        
        $this->IdRegistro_ = $id;
        $this->MiCliente_ = $this->AnadirCliente($micliente);
        $this->MiCertificado_ = $this->AnadirCertificado($micertificado);
        $this->Edicion_ = $edicion;
        $this->FechaCertificacion_ = $fechaCertificado;
        $this->FechaVencimiento_ = $fechaVenceimiento;
        $this->ExaGeneral_ = $exaGeneral;
        $this->ExaEspecifica_ = $exaEspecifica;
        $this->ExaParcial_ = $exaParcial;
        $this->Examen_ = $examen;
        $this->FechaCreacion_ = $fechaCreacion;
        $this->Modificacion_ = $modificacion;
        $this->Vencimiento_ = $vencimiento;
    }

    private function AnadirCliente($idCliente) {
        $catalogoClientes = new CatalogoClientes();
        $miCliente = $catalogoClientes->ObtenerCliente($idCliente);
        return $miCliente;
    }

    private function AnadirCertificado($idCertificado) {
        $catalgoCertificados = new CatalogoCertificado();
        $miCertificado = $catalgoCertificados->ObtenerCertificado($idCertificado);
        return $miCertificado;
    }

}

?>