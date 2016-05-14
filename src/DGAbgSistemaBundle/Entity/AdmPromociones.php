<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AdmPromociones
 *
 * @ORM\Table(name="adm_promociones", indexes={@ORM\Index(name="fk_ctl_ventas_ctl_prod_servicio_admin1_idx", columns={"ctl_prod_servicio_admin_id"})})
 * @ORM\Entity
 */
class AdmPromociones
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
     * @var float
     *
     * @ORM\Column(name="monto", type="float", precision=10, scale=0, nullable=false)
     */
    private $monto;

    /**
     * @var float
     *
     * @ORM\Column(name="descuento", type="float", precision=10, scale=0, nullable=true)
     */
    private $descuento;

    /**
     * @var integer
     *
     * @ORM\Column(name="posicion", type="integer", nullable=true)
     */
    private $posicion;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;
    
    /**
     * @var \CtlProdServicioAdmin
     *
     * @ORM\ManyToOne(targetEntity="CtlProdServicioAdmin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_prod_servicio_admin_id", referencedColumnName="id")
     * })
     */
    private $ctlProdServicioAdmin;



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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return AdmPromociones
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
     * @return AdmPromociones
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
     * Set monto
     *
     * @param float $monto
     * @return AdmPromociones
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set descuento
     *
     * @param float $descuento
     * @return AdmPromociones
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return float 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set plazo
     *
     * @param float $posicion
     * @return AdmPromociones
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }
    
    /**
     * Get plazo
     *
     * @return integer 
     */
    public function getPosicion()
    {
        return $this->posicion;
    }
    
    /**
     * Set estado
     *
     * @param boolean $estado
     * @return AdmPromociones
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
     * Set ctlProdServicioAdmin
     *
     * @param \DGAbgSistemaBundle\Entity\CtlProdServicioAdmin $ctlProdServicioAdmin
     * @return AdmPromociones
     */
    public function setCtlProdServicioAdmin(\DGAbgSistemaBundle\Entity\CtlProdServicioAdmin $ctlProdServicioAdmin = null)
    {
        $this->ctlProdServicioAdmin = $ctlProdServicioAdmin;

        return $this;
    }

    /**
     * Get ctlProdServicioAdmin
     *
     * @return \DGAbgSistemaBundle\Entity\CtlProdServicioAdmin 
     */
    public function getCtlProdServicioAdmin()
    {
        return $this->ctlProdServicioAdmin;
    }
    
     /**
     * @ORM\OneToMany(targetEntity="AdmImagenPromocion", mappedBy="promocion", cascade={"persist", "remove"})
     */
    protected $placas;
    
    public function __construct()
    {
        $this->placas = new ArrayCollection();
    }           
    
    public function getPlacas()
    {
        return $this->placas;
    }
    
    public function setPlacas(\Doctrine\Common\Collections\Collection $placas)
    {
        $this->placas = $placas;
        
        foreach ($placas as $placa) {
            $placa->setPromocion($this);
        }
    }
    
    public function removePlaca(AdmImagenPromocion $placa)
    {
        $this->placas->removeElement($placa);
    }
    
    public function __toString() 
    {
        return $this->descuento.'%';
    }
}
