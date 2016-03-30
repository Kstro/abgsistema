<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgNotificasionUsuario
 *
 * @ORM\Table(name="abg_notificasion_usuario", indexes={@ORM\Index(name="fk_abg_notificasion_usuario_abg_persona1_idx", columns={"abg_persona_id"}), @ORM\Index(name="fk_abg_notificasion_usuario_ctl_empresa1_idx", columns={"ctl_empresa_id"}), @ORM\Index(name="fk_abg_notificasion_usuario_ctl_tipo_notificacion1_idx", columns={"ctl_tipo_notificacion_id"})})
 * @ORM\Entity
 */
class AbgNotificasionUsuario
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
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

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
     * Set estado
     *
     * @param boolean $estado
     * @return AbgNotificasionUsuario
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgNotificasionUsuario
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
     * @return AbgNotificasionUsuario
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
     * Set ctlTipoNotificacion
     *
     * @param \DGAbgSistemaBundle\Entity\CtlTipoNotificacion $ctlTipoNotificacion
     * @return AbgNotificasionUsuario
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
