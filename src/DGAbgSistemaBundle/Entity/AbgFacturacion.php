<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgFacturacion
 *
 * @ORM\Table(name="abg_facturacion", indexes={@ORM\Index(name="fk_abg_cargos_abg_persona1_idx", columns={"abg_persona_id"}), @ORM\Index(name="fk_abg_cargos_ctl_empresa1_idx", columns={"ctl_empresa_id"}), @ORM\Index(name="fk_abg_cargos_abg_tipo_pago1_idx", columns={"abg_tipo_pago_id"}), @ORM\Index(name="fk_abg_facturacion_ctl_promociones1_idx", columns={"ctl_promociones_id"})})
 * @ORM\Entity
 */
class AbgFacturacion
{
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
     * @var float
     *
     * @ORM\Column(name="monto", type="float", precision=10, scale=0, nullable=false)
     */
    private $monto;

    /**
     * @var string
     *
     * @ORM\Column(name="servicio", type="string", length=60, nullable=false)
     */
    private $servicio;

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
     * @var \CtlEmpresa
     *
     * @ORM\ManyToOne(targetEntity="CtlEmpresa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_empresa_id", referencedColumnName="id")
     * })
     */
    private $ctlEmpresa;

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
     * @var \AdmPromociones
     *
     * @ORM\ManyToOne(targetEntity="AdmPromociones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_promociones_id", referencedColumnName="id")
     * })
     */
    private $ctlPromociones;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     * @return AbgFacturacion
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime 
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Set monto
     *
     * @param float $monto
     * @return AbgFacturacion
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set servicio
     *
     * @param string $servicio
     * @return AbgFacturacion
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return string 
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgFacturacion
     */
    public function setAbgPersona(\DGAbgSistemaBundle\Entity\AbgPersona $abgPersona = null)
    {
        $this->abgPersona = $abgPersona;

        return $this;
    }

    /**
     * Get abgPersona
     *
     * @return \DGAbgSistemaBundle\Entity\AbgPersona 
     */
    public function getAbgPersona()
    {
        return $this->abgPersona;
    }

    /**
     * Set ctlEmpresa
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa
     * @return AbgFacturacion
     */
    public function setCtlEmpresa(\DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa = null)
    {
        $this->ctlEmpresa = $ctlEmpresa;

        return $this;
    }

    /**
     * Get ctlEmpresa
     *
     * @return \DGAbgSistemaBundle\Entity\CtlEmpresa 
     */
    public function getCtlEmpresa()
    {
        return $this->ctlEmpresa;
    }

    /**
     * Set abgTipoPago
     *
     * @param \DGAbgSistemaBundle\Entity\CtlTipoPago $abgTipoPago
     * @return AbgFacturacion
     */
    public function setAbgTipoPago(\DGAbgSistemaBundle\Entity\CtlTipoPago $abgTipoPago = null)
    {
        $this->abgTipoPago = $abgTipoPago;

        return $this;
    }

    /**
     * Get abgTipoPago
     *
     * @return \DGAbgSistemaBundle\Entity\CtlTipoPago 
     */
    public function getAbgTipoPago()
    {
        return $this->abgTipoPago;
    }

    /**
     * Set ctlPromociones
     *
     * @param \DGAbgSistemaBundle\Entity\AdmPromociones $ctlPromociones
     * @return AbgFacturacion
     */
    public function setCtlPromociones(\DGAbgSistemaBundle\Entity\AdmPromociones $ctlPromociones = null)
    {
        $this->ctlPromociones = $ctlPromociones;

        return $this;
    }

    /**
     * Get ctlPromociones
     *
     * @return \DGAbgSistemaBundle\Entity\AdmPromociones 
     */
    public function getCtlPromociones()
    {
        return $this->ctlPromociones;
    }
}
