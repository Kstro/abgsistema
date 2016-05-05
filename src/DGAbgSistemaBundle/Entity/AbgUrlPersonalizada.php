<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgUrlPersonalizada
 *
 * @ORM\Table(name="abg_url_personalizada", indexes={@ORM\Index(name="ctl_empresa_id", columns={"ctl_empresa_id"}), @ORM\Index(name="abg_persona_id", columns={"abg_persona_id"})})
 * @ORM\Entity
 */
class AbgUrlPersonalizada
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
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return AbgUrlPersonalizada
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return AbgUrlPersonalizada
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgUrlPersonalizada
     */
    public function setAbgPersonas(\DGAbgSistemaBundle\Entity\AbgPersona $abgPersona = null)
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
     * @return AbgUrlPersonalizada
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
