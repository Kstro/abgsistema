<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgVisitas
 *
 * @ORM\Table(name="abg_visitas", indexes={@ORM\Index(name="fk_abg_visitas_abg_persona1_idx", columns={"abg_persona_id"}), @ORM\Index(name="fk_abg_visitas_ctl_empresa1_idx", columns={"ctl_empresa_id"})})
 * @ORM\Entity
 */
class AbgVisitas
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
     * @ORM\Column(name="visita_unica", type="text", length=16777215, nullable=false)
     */
    private $visitaUnica;

    /**
     * @var string
     *
     * @ORM\Column(name="visita", type="text", length=16777215, nullable=false)
     */
    private $visita;

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
     * Set visitaUnica
     *
     * @param string $visitaUnica
     * @return AbgVisitas
     */
    public function setVisitaUnica($visitaUnica)
    {
        $this->visitaUnica = $visitaUnica;

        return $this;
    }

    /**
     * Get visitaUnica
     *
     * @return string 
     */
    public function getVisitaUnica()
    {
        return $this->visitaUnica;
    }

    /**
     * Set visita
     *
     * @param string $visita
     * @return AbgVisitas
     */
    public function setVisita($visita)
    {
        $this->visita = $visita;

        return $this;
    }

    /**
     * Get visita
     *
     * @return string 
     */
    public function getVisita()
    {
        return $this->visita;
    }

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgVisitas
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
     * @return AbgVisitas
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
