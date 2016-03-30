<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgOraganizacionUsuario
 *
 * @ORM\Table(name="abg_oraganizacion_usuario", indexes={@ORM\Index(name="fk_abg_oraganizacion_usuario_abg_persona1_idx", columns={"abg_persona_id"}), @ORM\Index(name="fk_abg_oraganizacion_usuario_ctl_empresa1_idx", columns={"ctl_empresa_id"}), @ORM\Index(name="fk_abg_oraganizacion_usuario_agb_organizacion1_idx", columns={"agb_organizacion_id"})})
 * @ORM\Entity
 */
class AbgOraganizacionUsuario
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
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=false)
     */
    private $fechaFin;

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
     * @var \CtlOrganizacion
     *
     * @ORM\ManyToOne(targetEntity="CtlOrganizacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agb_organizacion_id", referencedColumnName="id")
     * })
     */
    private $agbOrganizacion;



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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return AbgOraganizacionUsuario
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return AbgOraganizacionUsuario
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgOraganizacionUsuario
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
     * @return AbgOraganizacionUsuario
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
     * Set agbOrganizacion
     *
     * @param \DGAbgSistemaBundle\Entity\CtlOrganizacion $agbOrganizacion
     * @return AbgOraganizacionUsuario
     */
    public function setAgbOrganizacion(\DGAbgSistemaBundle\Entity\CtlOrganizacion $agbOrganizacion = null)
    {
        $this->agbOrganizacion = $agbOrganizacion;

        return $this;
    }

    /**
     * Get agbOrganizacion
     *
     * @return \DGAbgSistemaBundle\Entity\CtlOrganizacion 
     */
    public function getAgbOrganizacion()
    {
        return $this->agbOrganizacion;
    }
}
