<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgFoto
 *
 * @ORM\Table(name="abg_foto", indexes={@ORM\Index(name="fk_arg_foto_persona_abg_persona1_idx", columns={"abg_persona_id"}), @ORM\Index(name="fk_abg_foto_ctl_empresa1_idx", columns={"ctl_empresa_id"})})
 * @ORM\Entity
 */
class AbgFoto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idarg_foto", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idargFoto;

    /**
     * @var string
     *
     * @ORM\Column(name="src", type="string", length=60, nullable=true)
     */
    private $src;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo_foto", type="integer", nullable=false)
     */
    private $tipoFoto;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="date", nullable=true)
     */
    private $fechaRegistro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_expiracion", type="date", nullable=true)
     */
    private $fechaExpiracion;

    /**
     * @var \AdmPromociones
     *
     * @ORM\ManyToOne(targetEntity="AdmPromociones", inversedBy="placas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="promocion", referencedColumnName="id")
     * })
     */
    private $promocion;
    
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
     * @var \AbgPersona
     *
     * @ORM\ManyToOne(targetEntity="AbgPersona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abg_persona_id", referencedColumnName="id")
     * })
     */
    private $abgPersona;



    /**
     * Get idargFoto
     *
     * @return integer 
     */
    public function getIdargFoto()
    {
        return $this->idargFoto;
    }

    /**
     * Set src
     *
     * @param string $src
     * @return AbgFoto
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string 
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set tipoFoto
     *
     * @param integer $tipoFoto
     * @return AbgFoto
     */
    public function setTipoFoto($tipoFoto)
    {
        $this->tipoFoto = $tipoFoto;

        return $this;
    }

    /**
     * Get tipoFoto
     *
     * @return integer 
     */
    public function getTipoFoto()
    {
        return $this->tipoFoto;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return AbgFoto
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
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return AbgFoto
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
     * Set fechaExpiracion
     *
     * @param \DateTime $fechaExpiracion
     * @return AbgFoto
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;

        return $this;
    }

    /**
     * Get fechaExpiracion
     *
     * @return \DateTime 
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }

     /**
     * Set promocion
     *
     * @param \DGAbgSistemaBundle\Entity\AdmPromociones $admPromociones
     *
     * @return AbgFoto
     */
    public function setPromocion(\DGAbgSistemaBundle\Entity\AdmPromociones $admPromociones = null)
    {
        $this->promocion = $admPromociones;

        return $this;
    }

    /**
     * Get promocion
     *
     * @return \DGAbgSistemaBundle\Entity\AdmPromociones
     */
    public function getPromocion()
    {
        return $this->promocion;
    }
    
    /**
     * Set ctlEmpresa
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa
     * @return AbgFoto
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

    /**
     * Set abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return AbgFoto
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
