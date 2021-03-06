<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgPregunta
 *
 * @ORM\Table(name="abg_pregunta", indexes={@ORM\Index(name="fk_abg_pregunta_ctl_usuario1_idx", columns={"ctl_usuario_id"}), @ORM\Index(name="fk_abg_pregunta_ctl_tipo_reporte1_idx", columns={"ctl_tipo_reporte_id"}), @ORM\Index(name="fk_abg_pregunta_abg_subespecialidad1_idx", columns={"abg_subespecialidad_id"})})
 * @ORM\Entity
 */
class AbgPregunta
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
     * @ORM\Column(name="pregunta", type="text", length=65535, nullable=false)
     */
    private $pregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="respuesta", type="text", length=65535, nullable=false)
     */
    private $respuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=45, nullable=false)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="correoelectronico", type="string", length=60, nullable=false)
     */
    private $correoelectronico;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="string", length=1000, nullable=false)
     */
    private $detalle;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechapregunta", type="datetime", nullable=true)
     */
    private $fechapregunta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_respuesta", type="datetime", nullable=true)
     */
    private $fechaRespuesta;
    
    /**
     * @var \CtlEspecialidad
     *
     * @ORM\ManyToOne(targetEntity="CtlEspecialidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_especialidad", referencedColumnName="id")
     * })
     */
    private $abgEspecialidad;

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
     *   @ORM\JoinColumn(name="ctl_usuario_id", referencedColumnName="id")
     * })
     */
    private $ctlUsuario;

    /**
     * @var integer
     * @ORM\Column(name="contador", type="integer", nullable=false)
     */
    private $contador;

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
     * Set estado
     *
     * @param string $contador
     * @return AbgPregunta
     */
    public function setContador($contador)
    {
        $this->contador= $contador;

        return $this;
    }

    /**
     * Get contador
     *
     * @return integer 
     */
    public function getContador()
    {
        return $this->contador;
    }
    /**
     * Set pregunta
     *
     * @param string $pregunta
     * @return AbgPregunta
     */
    public function setPregunta($pregunta)
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    /**
     * Get pregunta
     *
     * @return string 
     */
    public function getPregunta()
    {
        return $this->pregunta;
    }

    /**
     * Set respuesta
     *
     * @param string $respuesta
     * @return AbgPregunta
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return AbgPregunta
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set correoelectronico
     *
     * @param string $correoelectronico
     * @return AbgPregunta
     */
    public function setCorreoelectronico($correoelectronico)
    {
        $this->correoelectronico = $correoelectronico;

        return $this;
    }
    
    /**
     * Get correoelectronico
     *
     * @return string 
     */
    public function getCorreoelectronico()
    {
        return $this->correoelectronico;
    }
    
    /**
     * Set correoelectronico
     *
     * @param string $detalle
     * @return AbgPregunta
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }
    
    /**
     * Get detalle
     *
     * @return string 
     */
    public function getDetalle() {
        return $this->detalle;
    }
    
    /**
     * Set fechapregunta
     *
     * @param \DateTime $fechapregunta
     * @return AbgPregunta
     */
    public function setFechaPregunta($fechapregunta) {
        $this->fechapregunta = $fechapregunta;

        return $this;
    }

    /**
     * Get fechapregunta
     *
     * @return \DateTime
     */
    public function getFechaPregunta() {
        return $this->fechapregunta;
    }
    
    /**
     * Set fechaRespuesta
     *
     * @param \DateTime $fechaRespuesta
     *
     * @return AbgPregunta
     */
    public function setFechaRespuesta($fechaRespuesta)
    {
        $this->fechaRespuesta = $fechaRespuesta;

        return $this;
    }

    /**
     * Get fechaRespuesta
     *
     * @return \DateTime
     */
    public function getFechaRespuesta()
    {
        return $this->fechaRespuesta;
    }

    /**
     * Set abgEspecialidad
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEspecialidad $abgEspecialidad
     * @return AbgPregunta
     */
    public function setAbgEspecialidad(\DGAbgSistemaBundle\Entity\CtlEspecialidad $abgEspecialidad = null)
    {
        $this->abgEspecialidad = $abgEspecialidad;

        return $this;
    }

    /**
     * Get abgSubespecialidad
     *
     * @return \DGAbgSistemaBundle\Entity\CtlEspecialidad 
     */
    public function getAbgEspecialidad()
    {
        return $this->abgEspecialidad;
    }

    /**
     * Set ctlTipoReporte
     *
     * @param \DGAbgSistemaBundle\Entity\CtlTipoReporte $ctlTipoReporte
     * @return AbgPregunta
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
     * Set ctlUsuario
     *
     * @param \DGAbgSistemaBundle\Entity\CtlUsuario $ctlUsuario
     * @return AbgPregunta
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
