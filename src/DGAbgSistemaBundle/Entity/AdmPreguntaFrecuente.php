<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdmPreguntaFrecuente
 *
 * @ORM\Table(name="adm_pregunta_frecuente", indexes={@ORM\Index(name="fk_ctl_pregunta_frecuente_ctl_apartados1_idx", columns={"ctl_apartados_id"})})
 * @ORM\Entity
 */
class AdmPreguntaFrecuente
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
     * @ORM\Column(name="pregunta", type="string", length=200, nullable=false)
     */
    private $pregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="respuesta", type="text", length=65535, nullable=false)
     */
    private $respuesta;

    /**
     * @var \CtlApartados
     *
     * @ORM\ManyToOne(targetEntity="CtlApartados")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_apartados_id", referencedColumnName="id")
     * })
     */
    private $ctlApartados;



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
     * Set pregunta
     *
     * @param string $pregunta
     * @return AdmPreguntaFrecuente
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
     * @return AdmPreguntaFrecuente
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
     * Set ctlApartados
     *
     * @param \DGAbgSistemaBundle\Entity\CtlApartados $ctlApartados
     * @return AdmPreguntaFrecuente
     */
    public function setCtlApartados(\DGAbgSistemaBundle\Entity\CtlApartados $ctlApartados = null)
    {
        $this->ctlApartados = $ctlApartados;

        return $this;
    }

    /**
     * Get ctlApartados
     *
     * @return \DGAbgSistemaBundle\Entity\CtlApartados 
     */
    public function getCtlApartados()
    {
        return $this->ctlApartados;
    }
}
