<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgPersonaIdioma
 *
 * @ORM\Table(name="abg_persona_idioma", indexes={@ORM\Index(name="ctl_idioma_id", columns={"ctl_idioma_id"}), @ORM\Index(name="abg_persona_id", columns={"abg_persona_id"})})
 * @ORM\Entity
 */
class AbgPersonaIdioma
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
     * @ORM\Column(name="nivel", type="string", length=45, nullable=false)
     */
    private $nivel;

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
     * @var \CtlIdioma
     *
     * @ORM\ManyToOne(targetEntity="CtlIdioma")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_idioma_id", referencedColumnName="id")
     * })
     */
    private $ctlioma;



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
     * Set nivel
     *
     * @param string $nivel
     * @return AbgPersonaIdioma
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return string 
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgPersonaIdioma
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
     * Set ctlioma
     *
     * @param \DGAbgSistemaBundle\Entity\CtlIdioma $ctlioma
     * @return AbgPersonaIdioma
     */
    public function setCtlioma(\DGAbgSistemaBundle\Entity\CtlIdioma $ctlioma = null)
    {
        $this->ctlioma = $ctlioma;

        return $this;
    }

    /**
     * Get ctlioma
     *
     * @return \DGAbgSistemaBundle\Entity\CtlIdioma 
     */
    public function getCtlioma()
    {
        return $this->ctlioma;
    }
}
