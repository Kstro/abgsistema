<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgRespuestaPregunta
 *
 * @ORM\Table(name="abg_respuesta_pregunta", indexes={@ORM\Index(name="fk_abg_respuesta_pregunta_usuario_idx", columns={"ctl_usuario_id"})}, indexes={@ORM\Index(name="fk_ctl_respuesta_pregunta_1_idx", columns={"abg_pregunta"})})
 * @ORM\Entity
 */

class AbgRespuestaPregunta {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
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
     * @var \AbgPregunta
     *
     * @ORM\ManyToOne(targetEntity="AbgPregunta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abg_pregunta", referencedColumnName="id")
     * })
     */
    private $abgPregunta;
     /**
     * @var string
     *
     * @ORM\Column(name="respuesta", type="text", length=65535, nullable=false)
     */
    
    
    private $respuesta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_respuesta", type="datetime", nullable=true)
     */
    private $fechaRespuesta;

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
     * Set abgPregunta
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPregunta $abgPregunta
     * @return AbgRespuestaPregunta
     */
    public function setPregunta(\DGAbgSistemaBundle\Entity\AbgPregunta $abgPregunta = null)
    {
        $this->abgPregunta= $abgPregunta;

        return $this;
    }

    /**
     * Get abgPregunta
     *
     * @return \DGAbgSistemaBundle\Entity\AbgPregunta 
     */
    public function getPregunta()
    {
        return $this->abgPregunta;
    }
  /**
     * Set ctlUsuario
     *
     * @param \DGAbgSistemaBundle\Entity\CtlUsuario $ctlUsuario
     * @return AbgRespuestaPregunta
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
