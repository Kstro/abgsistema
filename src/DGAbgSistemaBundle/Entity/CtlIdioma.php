<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlIdioma
 *
 * @ORM\Table(name="ctl_idioma")
 * @ORM\Entity
 */
class CtlIdioma
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
     * @ORM\Column(name="idioma", type="string", length=45, nullable=false)
     */
    private $idioma;

    /**
     * @var string
     *
     * @ORM\Column(name="nivel", type="string", length=45, nullable=false)
     */
    private $nivel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AbgPersona", mappedBy="abgioma")
     */
    private $abgPersona;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->abgPersona = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set idioma
     *
     * @param string $idioma
     * @return CtlIdioma
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;

        return $this;
    }

    /**
     * Get idioma
     *
     * @return string 
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set nivel
     *
     * @param string $nivel
     * @return CtlIdioma
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
     * Add abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return CtlIdioma
     */
    public function addAbgPersona(\DGAbgSistemaBundle\Entity\AbgPersona $abgPersona)
    {
        $this->abgPersona[] = $abgPersona;

        return $this;
    }

    /**
     * Remove abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     */
    public function removeAbgPersona(\DGAbgSistemaBundle\Entity\AbgPersona $abgPersona)
    {
        $this->abgPersona->removeElement($abgPersona);
    }

    /**
     * Get abgPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAbgPersona()
    {
        return $this->abgPersona;
    }
}
