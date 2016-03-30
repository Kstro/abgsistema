<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgEstudio
 *
 * @ORM\Table(name="abg_estudio", indexes={@ORM\Index(name="fk_formacion_academica_persona1_idx", columns={"abg_persona_id"}), @ORM\Index(name="fk_abg_estudio_superior_abg_titulo_profesional1_idx", columns={"abg_titulo_profesional_id"})})
 * @ORM\Entity
 */
class AbgEstudio
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
     * @ORM\Column(name="institucion", type="string", length=45, nullable=false)
     */
    private $institucion;

    /**
     * @var string
     *
     * @ORM\Column(name="anio_graduacion", type="string", length=45, nullable=false)
     */
    private $anioGraduacion;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=45, nullable=false)
     */
    private $titulo;

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
     * @var \CtlTituloProfesional
     *
     * @ORM\ManyToOne(targetEntity="CtlTituloProfesional")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abg_titulo_profesional_id", referencedColumnName="id")
     * })
     */
    private $abgTituloProfesional;



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
     * Set institucion
     *
     * @param string $institucion
     * @return AbgEstudio
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
     * Set anioGraduacion
     *
     * @param string $anioGraduacion
     * @return AbgEstudio
     */
    public function setAnioGraduacion($anioGraduacion)
    {
        $this->anioGraduacion = $anioGraduacion;

        return $this;
    }

    /**
     * Get anioGraduacion
     *
     * @return string 
     */
    public function getAnioGraduacion()
    {
        return $this->anioGraduacion;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return AbgEstudio
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgEstudio
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
     * Set abgTituloProfesional
     *
     * @param \DGAbgSistemaBundle\Entity\CtlTituloProfesional $abgTituloProfesional
     * @return AbgEstudio
     */
    public function setAbgTituloProfesional(\DGAbgSistemaBundle\Entity\CtlTituloProfesional $abgTituloProfesional = null)
    {
        $this->abgTituloProfesional = $abgTituloProfesional;

        return $this;
    }

    /**
     * Get abgTituloProfesional
     *
     * @return \DGAbgSistemaBundle\Entity\CtlTituloProfesional 
     */
    public function getAbgTituloProfesional()
    {
        return $this->abgTituloProfesional;
    }
}
