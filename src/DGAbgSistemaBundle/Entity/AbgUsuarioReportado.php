<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgUsuarioReportado
 *
 * @ORM\Table(name="abg_usuario_reportado", indexes={@ORM\Index(name="fk_abg_usuario_reportado_ctl_tipo_reporte1_idx", columns={"ctl_tipo_reporte_id"}), @ORM\Index(name="fk_abg_usuario_reportado_ctl_usuario1_idx", columns={"usuario_reportado"})})
 * @ORM\Entity
 */
class AbgUsuarioReportado
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
     * @ORM\Column(name="descripcion", type="string", length=200, nullable=false)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="usuario_reporte", type="integer", nullable=false)
     */
    private $usuarioReporte;

    /**
     * @var \CtlTipoReporte
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoReporte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_tipo_reporte_id", referencedColumnName="id")
     * })
     */
    private $ctlTipoReporte;

    /**
     * @var \CtlUsuario
     *
     * @ORM\ManyToOne(targetEntity="CtlUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_reportado", referencedColumnName="id")
     * })
     */
    private $usuarioReportado;



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
     * Set descripcion
     *
     * @param string $descripcion
     * @return AbgUsuarioReportado
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return AbgUsuarioReportado
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set usuarioReporte
     *
     * @param integer $usuarioReporte
     * @return AbgUsuarioReportado
     */
    public function setUsuarioReporte($usuarioReporte)
    {
        $this->usuarioReporte = $usuarioReporte;

        return $this;
    }

    /**
     * Get usuarioReporte
     *
     * @return integer 
     */
    public function getUsuarioReporte()
    {
        return $this->usuarioReporte;
    }

    /**
     * Set ctlTipoReporte
     *
     * @param \DGAbgSistemaBundle\Entity\CtlTipoReporte $ctlTipoReporte
     * @return AbgUsuarioReportado
     */
    public function setCtlTipoReporte(\DGAbgSistemaBundle\Entity\CtlTipoReporte $ctlTipoReporte = null)
    {
        $this->ctlTipoReporte = $ctlTipoReporte;

        return $this;
    }

    /**
     * Get ctlTipoReporte
     *
     * @return \DGAbgSistemaBundle\Entity\CtlTipoReporte 
     */
    public function getCtlTipoReporte()
    {
        return $this->ctlTipoReporte;
    }

    /**
     * Set usuarioReportado
     *
     * @param \DGAbgSistemaBundle\Entity\CtlUsuario $usuarioReportado
     * @return AbgUsuarioReportado
     */
    public function setUsuarioReportado(\DGAbgSistemaBundle\Entity\CtlUsuario $usuarioReportado = null)
    {
        $this->usuarioReportado = $usuarioReportado;

        return $this;
    }

    /**
     * Get usuarioReportado
     *
     * @return \DGAbgSistemaBundle\Entity\CtlUsuario 
     */
    public function getUsuarioReportado()
    {
        return $this->usuarioReportado;
    }
}
