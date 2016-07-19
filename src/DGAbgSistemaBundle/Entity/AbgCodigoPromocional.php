<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgCodigoPromocional
 *
 * @ORM\Table(name="abg_codigo_promocional", indexes={@ORM\Index(name="persona", columns={"persona"})})
 * @ORM\Entity
 */
class AbgCodigoPromocional
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
     * @ORM\Column(name="codigo", type="string", length=20, nullable=false)
     */
    private $codigo;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo_promocion", type="integer", nullable=false)
     */
    private $tipoPromocion;

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
     *   @ORM\JoinColumn(name="persona", referencedColumnName="id")
     * })
     */
    private $persona;


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
     * Set codigo
     *
     * @param string $codigo
     * @return AbgCodigoPromocional
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set tipoPromocion
     *
     * @param integer $tipoPromocion
     * @return AbgCodigoPromocional
     */
    public function setTipoPromocion($tipoPromocion)
    {
        $this->tipoPromocion = $tipoPromocion;

        return $this;
    }

    /**
     * Get tipoPromocion
     *
     * @return integer 
     */
    public function getTipoPromocion()
    {
        return $this->tipoPromocion;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return AbgCodigoPromocional
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
     * Set persona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $persona
     * @return AbgCodigoPromocional
     */
    public function setAbgPersona(\DGAbgSistemaBundle\Entity\AbgPersona $persona = null)
    {
        $this->abgPersona = $persona;

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
    
    public function __toString() 
    {
        return $this->codigo;
    }
}
