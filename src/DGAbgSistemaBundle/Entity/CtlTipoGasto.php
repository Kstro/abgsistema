<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlTipoGasto
 *
 * @ORM\Table(name="ctl_tipo_gasto")
 * @ORM\Entity
 */
class CtlTipoGasto
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
     * @ORM\Column(name="nombre_gasto", type="string", length=60, nullable=false)
     */
    private $nombreGasto;



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
     * Set nombreGasto
     *
     * @param string $nombreGasto
     * @return CtlTipoGasto
     */
    public function setNombreGasto($nombreGasto)
    {
        $this->nombreGasto = $nombreGasto;

        return $this;
    }

    /**
     * Get nombreGasto
     *
     * @return string 
     */
    public function getNombreGasto()
    {
        return $this->nombreGasto;
    }
}
