<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlSubespecialidad
 *
 * @ORM\Table(name="ctl_subespecialidad", indexes={@ORM\Index(name="fk_abg_subespecialidad_abg_especialidad1_idx", columns={"abg_especialidad_id"})})
 * @ORM\Entity
 */
class CtlSubespecialidad
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
     * @ORM\Column(name="abg_subespecialidadcol", type="string", length=45, nullable=false)
     */
    private $abgSubespecialidadcol;

    /**
     * @var \CtlEspecialidad
     *
     * @ORM\ManyToOne(targetEntity="CtlEspecialidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abg_especialidad_id", referencedColumnName="id")
     * })
     */
    private $abgEspecialidad;



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
     * Set abgSubespecialidadcol
     *
     * @param string $abgSubespecialidadcol
     * @return CtlSubespecialidad
     */
    public function setAbgSubespecialidadcol($abgSubespecialidadcol)
    {
        $this->abgSubespecialidadcol = $abgSubespecialidadcol;

        return $this;
    }

    /**
     * Get abgSubespecialidadcol
     *
     * @return string 
     */
    public function getAbgSubespecialidadcol()
    {
        return $this->abgSubespecialidadcol;
    }

    /**
     * Set abgEspecialidad
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEspecialidad $abgEspecialidad
     * @return CtlSubespecialidad
     */
    public function setAbgEspecialidad(\DGAbgSistemaBundle\Entity\CtlEspecialidad $abgEspecialidad = null)
    {
        $this->abgEspecialidad = $abgEspecialidad;

        return $this;
    }

    /**
     * Get abgEspecialidad
     *
     * @return \DGAbgSistemaBundle\Entity\CtlEspecialidad 
     */
    public function getAbgEspecialidad()
    {
        return $this->abgEspecialidad;
    }
}
