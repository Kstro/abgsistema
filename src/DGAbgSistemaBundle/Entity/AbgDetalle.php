<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgDetalle
 *
 * @ORM\Table(name="abg_detalle", indexes={@ORM\Index(name="fk_abg_detalle_abg_persona1_idx", columns={"abg_persona_id"})})
 * @ORM\Entity
 */
class AbgDetalle
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
     * @ORM\Column(name="sello", type="string", length=45, nullable=false)
     */
    private $sello;

    /**
     * @var string
     *
     * @ORM\Column(name="tarjeta", type="string", length=45, nullable=false)
     */
    private $tarjeta;

    /**
     * @var string
     *
     * @ORM\Column(name="firma", type="string", length=45, nullable=false)
     */
    private $firma;

    /**
     * @var boolean
     *
     * @ORM\Column(name="badge", type="boolean", nullable=false)
     */
    private $badge;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sello
     *
     * @param string $sello
     * @return AbgDetalle
     */
    public function setSello($sello)
    {
        $this->sello = $sello;

        return $this;
    }

    /**
     * Get sello
     *
     * @return string 
     */
    public function getSello()
    {
        return $this->sello;
    }

    /**
     * Set tarjeta
     *
     * @param string $tarjeta
     * @return AbgDetalle
     */
    public function setTarjeta($tarjeta)
    {
        $this->tarjeta = $tarjeta;

        return $this;
    }

    /**
     * Get tarjeta
     *
     * @return string 
     */
    public function getTarjeta()
    {
        return $this->tarjeta;
    }

    /**
     * Set firma
     *
     * @param string $firma
     * @return AbgDetalle
     */
    public function setFirma($firma)
    {
        $this->firma = $firma;

        return $this;
    }

    /**
     * Get firma
     *
     * @return string 
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * Set badge
     *
     * @param boolean $badge
     * @return AbgDetalle
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get badge
     *
     * @return boolean 
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgDetalle
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
}
