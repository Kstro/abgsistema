<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlEspecialidad
 *
 * @ORM\Table(name="ctl_especialidad")
 * @ORM\Entity
 */
class CtlEspecialidad
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
     * @ORM\Column(name="nombre_especialidad", type="string", length=45, nullable=false)
     */
    private $nombreEspecialidad;



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
     * Set nombreEspecialidad
     *
     * @param string $nombreEspecialidad
     * @return CtlEspecialidad
     */
    public function setNombreEspecialidad($nombreEspecialidad)
    {
        $this->nombreEspecialidad = $nombreEspecialidad;

        return $this;
    }

    /**
     * Get nombreEspecialidad
     *
     * @return string 
     */
    public function getNombreEspecialidad()
    {
        return $this->nombreEspecialidad;
    }
}
