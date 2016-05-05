<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlTipoNotificacion
 *
 * @ORM\Table(name="ctl_tipo_notificacion")
 * @ORM\Entity
 */
class CtlTipoNotificacion
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
     * @ORM\Column(name="nombre_notificasion", type="string", length=45, nullable=false)
     */
    private $nombreNotificasion;



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
     * Set nombreNotificasion
     *
     * @param string $nombreNotificasion
     * @return CtlTipoNotificacion
     */
    public function setNombreNotificasion($nombreNotificasion)
    {
        $this->nombreNotificasion = $nombreNotificasion;

        return $this;
    }

    /**
     * Get nombreNotificasion
     *
     * @return string 
     */
    public function getNombreNotificasion()
    {
        return $this->nombreNotificasion;
    }
}
