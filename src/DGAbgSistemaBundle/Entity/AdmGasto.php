<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdmGasto
 *
 * @ORM\Table(name="adm_gasto", indexes={@ORM\Index(name="fk_adm_gasto_ctl_tipo_gasto1_idx", columns={"ctl_tipo_gasto_id"}), @ORM\Index(name="fk_adm_gasto_ctl_usuario1_idx", columns={"ctl_usuario_id"})})
 * @ORM\Entity
 */
class AdmGasto
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
     * @ORM\Column(name="fecha_registro", type="date", nullable=false)
     */
    private $fechaRegistro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_gasto", type="date", nullable=false)
     */
    private $fechaGasto;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="float", precision=10, scale=0, nullable=false)
     */
    private $monto;

    /**
     * @var \CtlTipoGasto
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoGasto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_tipo_gasto_id", referencedColumnName="id")
     * })
     */
    private $ctlTipoGasto;

    /**
     * @var \CtlUsuario
     *
     * @ORM\ManyToOne(targetEntity="CtlUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_usuario_id", referencedColumnName="id")
     * })
     */
    private $ctlUsuario;



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
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return AdmGasto
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime 
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set fechaGasto
     *
     * @param \DateTime $fechaGasto
     * @return AdmGasto
     */
    public function setFechaGasto($fechaGasto)
    {
        $this->fechaGasto = $fechaGasto;

        return $this;
    }

    /**
     * Get fechaGasto
     *
     * @return \DateTime 
     */
    public function getFechaGasto()
    {
        return $this->fechaGasto;
    }

    /**
     * Set monto
     *
     * @param float $monto
     * @return AdmGasto
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
     * Set ctlTipoGasto
     *
     * @param \DGAbgSistemaBundle\Entity\CtlTipoGasto $ctlTipoGasto
     * @return AdmGasto
     */
    public function setCtlTipoGasto(\DGAbgSistemaBundle\Entity\CtlTipoGasto $ctlTipoGasto = null)
    {
        $this->ctlTipoGasto = $ctlTipoGasto;

        return $this;
    }

    /**
     * Get ctlTipoGasto
     *
     * @return \DGAbgSistemaBundle\Entity\CtlTipoGasto 
     */
    public function getCtlTipoGasto()
    {
        return $this->ctlTipoGasto;
    }

    /**
     * Set ctlUsuario
     *
     * @param \DGAbgSistemaBundle\Entity\CtlUsuario $ctlUsuario
     * @return AdmGasto
     */
    public function setCtlUsuario(\DGAbgSistemaBundle\Entity\CtlUsuario $ctlUsuario = null)
    {
        $this->ctlUsuario = $ctlUsuario;

        return $this;
    }

    /**
     * Get ctlUsuario
     *
     * @return \DGAbgSistemaBundle\Entity\CtlUsuario 
     */
    public function getCtlUsuario()
    {
        return $this->ctlUsuario;
    }
}
