<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgPersonaSubespecialidad
 *
 * @ORM\Table(name="abg_persona_subespecialidad", indexes={@ORM\Index(name="fk_abg_persona_has_abg_subespecialidad_abg_subespecialidad1_idx", columns={"abg_subespecialidad_id"}), @ORM\Index(name="fk_abg_persona_has_abg_subespecialidad_abg_persona1_idx", columns={"abg_persona_id"})})
 * @ORM\Entity
 */
class AbgPersonaSubespecialidad
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
     * @var string
     *
     * @ORM\Column(name="fecha", type="string", length=45, nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="institucion", type="string", length=60, nullable=false)
     */
    private $institucion;

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
     * @var \CtlSubespecialidad
     *
     * @ORM\ManyToOne(targetEntity="CtlSubespecialidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abg_subespecialidad_id", referencedColumnName="id")
     * })
     */
    private $abgSubespecialidad;



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
     * Set fecha
     *
     * @param string $fecha
     * @return AbgPersonaSubespecialidad
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return string 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set institucion
     *
     * @param string $institucion
     * @return AbgPersonaSubespecialidad
     */
    public function setInstitucion($institucion)
    {
        $this->institucion = $institucion;

        return $this;
    }

    /**
     * Get institucion
     *
     * @return string 
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgPersonaSubespecialidad
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
     * Set abgSubespecialidad
     *
     * @param \DGAbgSistemaBundle\Entity\CtlSubespecialidad $abgSubespecialidad
     * @return AbgPersonaSubespecialidad
     */
    public function setAbgSubespecialidad(\DGAbgSistemaBundle\Entity\CtlSubespecialidad $abgSubespecialidad = null)
    {
        $this->abgSubespecialidad = $abgSubespecialidad;

        return $this;
    }

    /**
     * Get abgSubespecialidad
     *
     * @return \DGAbgSistemaBundle\Entity\CtlSubespecialidad 
     */
    public function getAbgSubespecialidad()
    {
        return $this->abgSubespecialidad;
    }
}
