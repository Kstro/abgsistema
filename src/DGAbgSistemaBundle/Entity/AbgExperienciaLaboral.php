<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgExperienciaLaboral
 *
 * @ORM\Table(name="abg_experiencia_laboral", indexes={@ORM\Index(name="fk_experiencia_laboral_persona1_idx", columns={"abg_persona_id"})})
 * @ORM\Entity
 */
class AbgExperienciaLaboral
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
     * @ORM\Column(name="compania", type="string", length=45, nullable=false)
     */
    private $compania;

    /**
     * @var string
     *
     * @ORM\Column(name="puesto", type="string", length=45, nullable=false)
     */
    private $puesto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="facha_inicio", type="date", nullable=false)
     */
    private $fachaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechaFin;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_", type="string", length=45, nullable=true)
     */
    private $telefono;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;

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
     * @var string
     *
     * @ORM\Column(name="funcion", type="string", length=255, nullable=true)
     */
    private $funcion;

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
     * Set compania
     *
     * @param string $compania
     * @return AbgExperienciaLaboral
     */
    public function setCompania($compania)
    {
        $this->compania = $compania;

        return $this;
    }

    /**
     * Get compania
     *
     * @return string 
     */
    public function getCompania()
    {
        return $this->compania;
    }

    /**
     * Set puesto
     *
     * @param string $puesto
     * @return AbgExperienciaLaboral
     */
    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;

        return $this;
    }

    /**
     * Get puesto
     *
     * @return string 
     */
    public function getPuesto()
    {
        return $this->puesto;
    }

    /**
     * Set fachaInicio
     *
     * @param \DateTime $fachaInicio
     * @return AbgExperienciaLaboral
     */
    public function setFachaInicio($fachaInicio)
    {
        $this->fachaInicio = $fachaInicio;

        return $this;
    }

    /**
     * Get fachaInicio
     *
     * @return \DateTime 
     */
    public function getFachaInicio()
    {
        return $this->fachaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return AbgExperienciaLaboral
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
     * Set telefono
     *
     * @param string $telefono
     * @return AbgExperienciaLaboral
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }
   /**
     * Set telefono
     *
     * @param string $funcion
     * @return AbgExperienciaLaboral
     */
    public function setFuncion($funcion)
    {
        $this->funcion = $funcion;

        return $this;
    }
    /**
     * Get telefono
     *
     * @return string 
     */
    public function getFuncion()
    {
        return $this->funcion;
    }
    /**
     * Set orden
     *
     * @param integer $orden
     * @return AbgExperienciaLaboral
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer 
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgExperienciaLaboral
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
}
