<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgNotificasion
 *
 * @ORM\Table(name="abg_notificasion", indexes={@ORM\Index(name="fk_abg_notificasion_ctl_tipo_notificacion1_idx", columns={"ctl_tipo_notificacion_id"})})
 * @ORM\Entity
 */
class AbgNotificasion
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
     * @ORM\Column(name="nombre_notificacion", type="text", length=65535, nullable=false)
     */
    private $nombreNotificacion;

    /**
     * @var \CtlTipoNotificacion
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoNotificacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_tipo_notificacion_id", referencedColumnName="id")
     * })
     */
    private $ctlTipoNotificacion;



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
     * Set nombreNotificacion
     *
     * @param string $nombreNotificacion
     * @return AbgNotificasion
     */
    public function setNombreNotificacion($nombreNotificacion)
    {
        $this->nombreNotificacion = $nombreNotificacion;

        return $this;
    }

    /**
     * Get nombreNotificacion
     *
     * @return string 
     */
    public function getNombreNotificacion()
    {
        return $this->nombreNotificacion;
    }

    /**
     * Set ctlTipoNotificacion
     *
     * @param \DGAbgSistemaBundle\Entity\CtlTipoNotificacion $ctlTipoNotificacion
     * @return AbgNotificasion
     */
    public function setCtlTipoNotificacion(\DGAbgSistemaBundle\Entity\CtlTipoNotificacion $ctlTipoNotificacion = null)
    {
        $this->ctlTipoNotificacion = $ctlTipoNotificacion;

        return $this;
    }

    /**
     * Get ctlTipoNotificacion
     *
     * @return \DGAbgSistemaBundle\Entity\CtlTipoNotificacion 
     */
    public function getCtlTipoNotificacion()
    {
        return $this->ctlTipoNotificacion;
    }
}
