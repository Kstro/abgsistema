<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgPersonaSubespecialidad
 *
 * @ORM\Table(name="abg_persona_especialidad", indexes={@ORM\Index(name="fk_abg_persona_has_abg_subespecialidad_abg_especialidad", columns={"ctl_especialidad_id"}), @ORM\Index(name="fk_abg_persona_has_abg_subespecialidad_abg_persona1_idx", columns={"abg_persona_id"}), @ORM\Index(name="ctl_empresa_id", columns={"ctl_empresa_id"})})
 * @ORM\Entity
 */
class AbgPersonaEspecialida {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=500, nullable=true)
     */
    private $descripcion;

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
     * @var \CtlEspecialidad
     *
     * @ORM\ManyToOne(targetEntity="CtlEspecialidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_especialidad_id", referencedColumnName="id")
     * })
     */
    private $ctlEspecialidad;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return AbgPersonaEspecialidad
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgPersonaEspecialidad
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
     * Set ctlEspecialidad
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEspecialidad $ctlEspecialidad
     * @return AbgPersonaEspecialidad
     */
    public function setCtlEspecialidad(\DGAbgSistemaBundle\Entity\CtlEspecialidad $ctlEspecialidad = null)
    {
        $this->ctlEspecialidad = $ctlEspecialidad;

        return $this;
    }

    /**
     * Get ctlEspecialidad
     *
     * @return \DGAbgSistemaBundle\Entity\CtlEspecialidad 
     */
    public function getCtlEspecialidad()
    {
        return $this->ctlEspecialidad;
    }

    /**
     * Set ctlEmpresa
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa
     * @return AbgPersonaEspecialidad
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
}
