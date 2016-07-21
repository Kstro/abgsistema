<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgPersonaPromocion
 *
 * @ORM\Table(name="abg_persona_promocion", indexes={@ORM\Index(name="persona", columns={"persona"}), @ORM\Index(name="codigo_promocional", columns={"codigo_promocional"})})
 * @ORM\Entity
 */
class AbgPersonaPromocion
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
     * @var \AbgPersona
     *
     * @ORM\ManyToOne(targetEntity="AbgPersona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona", referencedColumnName="id")
     * })
     */
    private $persona;

    /**
     * @var \AbgCodigoPromocional
     *
     * @ORM\ManyToOne(targetEntity="AbgCodigoPromocional")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="codigo_promocional", referencedColumnName="id")
     * })
     */
    private $codigoPromocional;


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
     * Set persona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $persona
     * @return AbgPersonaPromocion
     */
    public function setAbgPersona(\DGAbgSistemaBundle\Entity\AbgPersona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \DGAbgSistemaBundle\Entity\AbgPersona 
     */
    public function getAbgPersona()
    {
        return $this->persona;
    }

    /**
     * Set codigoPromocional
     *
     * @param \DGAbgSistemaBundle\Entity\AbgCodigoPromocional $codigoPromocional
     * @return AbgPersonaPromocion
     */
    public function setCodigoPromocional(\DGAbgSistemaBundle\Entity\AbgCodigoPromocional $codigoPromocional = null) {
        $this->codigoPromocional = $codigoPromocional;

        return $this;
    }

    /**
     * Get codigoPromocional
     *
     * @return \DGAbgSistemaBundle\Entity\AbgCodigoPromocional 
     */
    public function getCodigoPromocional() {
        return $this->codigoPromocional;
    }
}
