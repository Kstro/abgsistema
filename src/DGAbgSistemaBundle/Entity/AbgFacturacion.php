<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgFacturacion
 *
 * @ORM\Table(name="abg_facturacion", indexes={@ORM\Index(name="fk_abg_cargos_abg_persona1_idx", columns={"abg_persona_id"}), @ORM\Index(name="fk_abg_cargos_ctl_empresa1_idx", columns={"ctl_empresa_id"}), @ORM\Index(name="fk_abg_cargos_abg_tipo_pago1_idx", columns={"abg_tipo_pago_id"}), @ORM\Index(name="fk_abg_facturacion_ctl_promociones1_idx", columns={"ctl_promociones_id"})})
 * @ORM\Entity
 */
class AbgFacturacion {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_pago", type="date", nullable=false)
     */
    private $fechaPago;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="date", nullable=false)
     */
    private $fechaRegistro;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="date", nullable=false)
     */
    private $fechaAprobado;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="float", precision=10, scale=0, nullable=false)
     */
    private $monto;

    /**
     * @var string
     *
     * @ORM\Column(name="servicio", type="string", length=100, nullable=false)
     */
    private $servicio;

    /**
     * @var string
     *
     * @ORM\Column(name="referencia", type="string", length=200, nullable=true)
     */
    private $referencia;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=1500, nullable=false)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="plazo", type="integer", nullable=false)
     */
    private $plazo;

    /**
     * @var float
     *
     * @ORM\Column(name="descuento", type="float", precision=10, scale=0, nullable=true)
     */
    private $descuento;

    /**
     * @var \AbgPersona
     *
     * @ORM\ManyToOne(targetEntity="AbgPersona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abg_persona_id", referencedColumnName="id")
     * })
     */
    private $abgPersona;

    /**
     * @var \CtlTipoPago
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoPago")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abg_tipo_pago_id", referencedColumnName="id")
     * })
     */
    private $abgTipoPago;

    /**
     * @var \CtlEmpresa
     *
     * @ORM\ManyToOne(targetEntity="CtlEmpresa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_empresa_id", referencedColumnName="id")
     * })
     */
    private $ctlEmpresa;

    /**
     * @var \AdmPromociones
     *
     * @ORM\ManyToOne(targetEntity="AdmPromociones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_promociones_id", referencedColumnName="id")
     * })
     */
    private $ctlPromociones;
    
    /**
     * @var \AbgCodigoPromocional
     *
     * @ORM\ManyToOne(targetEntity="AbgCodigoPromocional")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="codigo_promocional", referencedColumnName="id")
     * })
     */
    private $codigoPromocional;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="comprobante", type="string", length=100, nullable=true)
     */
    private $comprobante;

    /**
     * Get id
     *
     * @return integer 
     */

    /**
     * @var integer
     *
     * @ORM\Column(name="aprobado", type="integer", nullable=true)
     */
    private $aprobado;

    public function getId() {
        return $this->id;
    }

    /**
     * Set servicio
     *
     * @param string $comprobante
     * @return AbgFacturacion
     */
    public function setComprobante($comprobante) {
        $this->comprobante = $comprobante;

        return $this;
    }

    /**
     * Get comprobante
     *
     * @return string 
     */
    public function getComprobante() {
        return $this->comprobante;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     * @return AbgFacturacion
     */
    public function setFechaPago($fechaPago) {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime 
     */
    public function getFechaPago() {
        return $this->fechaPago;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return AbgFacturacion
     */
    public function setFechaRegistro($fechaRegistro) {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime 
     */
    public function getFechaRegistro() {
        return $this->fechaRegistro;
    }
    
    /**
     * Set fechaAprobado
     *
     * @param \DateTime $fechaAprobado
     * @return AbgFacturacion
     */
    public function setFechaAprobado($fechaAprobado) {
        $this->fechaAprobado = $fechaAprobado;

        return $this;
    }

    /**
     * Get fechaAprobado
     *
     * @return \DateTime 
     */
    public function getFechaAprobado() {
        return $this->fechaAprobado;
    }

    /**
     * Set monto
     *
     * @param float $monto
     * @return AbgFacturacion
     */
    public function setMonto($monto) {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto() {
        return $this->monto;
    }

    /**
     * Set servicio
     *
     * @param string $servicio
     * @return AbgFacturacion
     */
    public function setServicio($servicio) {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return string 
     */
    public function getServicio() {
        return $this->servicio;
    }

    /**
     * Set referencia
     *
     * @param string $referencia
     * @return AbgFacturacion
     */
    public function setReferencia($referencia) {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Get referencia
     *
     * @return string 
     */
    public function getReferencia() {
        return $this->referencia;
    }

    /**
     * Set servicio
     *
     * @param string $descripcion
     * @return AbgFacturacion
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set plazo
     *
     * @param float $plazo
     * @return AbgFacturacion
     */
    public function setPlazo($plazo) {
        $this->plazo = $plazo;

        return $this;
    }

    /**
     * Get plazo
     *
     * @return integer 
     */
    public function getPlazo() {
        return $this->plazo;
    }

    /**
     * Set descuento
     *
     * @param float $descuento
     * @return AbgFacturacion
     */
    public function setDescuento($descuento) {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return float 
     */
    public function getDescuento() {
        return $this->descuento;
    }

    /**
     * Set idUser
     *
     * @param float $idUser
     * @return AbgFacturacion
     */
    public function setIdUser($idUser) {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer 
     */
    public function getIdUser() {
        return $this->idUser;
    }

    /**
     * Set aprobado
     *
     * @param float $aprobado
     * @return AbgFacturacion
     */
    public function setAprobado($aprobado) {
        $this->aprobado = $aprobado;

        return $this;
    }

    /**
     * Get aprobado
     *
     * @return integer 
     */
    public function getAprobado() {
        return $this->aprobado;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgFacturacion
     */
    public function setAbgPersona(\DGAbgSistemaBundle\Entity\AbgPersona $abgPersona = null) {
        $this->abgPersona = $abgPersona;

        return $this;
    }

    /**
     * Get abgPersona
     *
     * @return \DGAbgSistemaBundle\Entity\AbgPersona 
     */
    public function getAbgPersona() {
        return $this->abgPersona;
    }

    /**
     * Set abgTipoPago
     *
     * @param \DGAbgSistemaBundle\Entity\CtlTipoPago $abgTipoPago
     * @return AbgFacturacion
     */
    public function setAbgTipoPago(\DGAbgSistemaBundle\Entity\CtlTipoPago $abgTipoPago = null) {
        $this->abgTipoPago = $abgTipoPago;

        return $this;
    }

    /**
     * Get abgTipoPago
     *
     * @return \DGAbgSistemaBundle\Entity\CtlTipoPago 
     */
    public function getAbgTipoPago() {
        return $this->abgTipoPago;
    }

    /**
     * Set ctlEmpresa
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa
     * @return AbgFacturacion
     */
    public function setCtlEmpresa(\DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa = null) {
        $this->ctlEmpresa = $ctlEmpresa;

        return $this;
    }

    /**
     * Get ctlEmpresa
     *
     * @return \DGAbgSistemaBundle\Entity\CtlEmpresa 
     */
    public function getCtlEmpresa() {
        return $this->ctlEmpresa;
    }

    /**
     * Set ctlPromociones
     *
     * @param \DGAbgSistemaBundle\Entity\AdmPromociones $ctlPromociones
     * @return AbgFacturacion
     */
    public function setCtlPromociones(\DGAbgSistemaBundle\Entity\AdmPromociones $ctlPromociones = null) {
        $this->ctlPromociones = $ctlPromociones;

        return $this;
    }

    /**
     * Get ctlPromociones
     *
     * @return \DGAbgSistemaBundle\Entity\AdmPromociones 
     */
    public function getCtlPromociones() {
        return $this->ctlPromociones;
    }
    
    /**
     * Set codigoPromocional
     *
     * @param \DGAbgSistemaBundle\Entity\AbgCodigoPromocional $codigoPromocional
     * @return AbgFacturacion
     */
    public function setCodigoPromocional(\DGAbgSistemaBundle\Entity\AbgCodigoPromocional $codigoPromocional = null) {
        $this->codigoPromocional = $codigoPromocional;

        return $this;
    }

    /**
     * Get codigoPromocional
     *
     * @return \DGAbgSistemaBundle\Entity\AbgCodigoPromocional 
     */
    public function getCodigoPromocional() {
        return $this->codigoPromocional;
    }

}
