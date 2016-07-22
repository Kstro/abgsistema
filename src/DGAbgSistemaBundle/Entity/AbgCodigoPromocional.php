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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechaFin;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="plazo", type="integer", nullable=true)
     */
    private $plazo;
    
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return AbgCodigoPromocional
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return AbgCodigoPromocional
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }
    
    /**
     * Set plazo
     *
     * @param integer $plazo
     * @return AbgCodigoPromocional
     */
    public function setPlazo($plazo) {
        $this->plazo = $plazo;

        return $this;
    }

    /**
     * Get plazo
     *
     * @return integer 
     */
    public function getPlazo() {
        return $this->plazo;
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
    
    public function __toString() 
    {
        return $this->codigo;
    }
}
