<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgSugerencia
 *
 * @ORM\Table(name="abg_sugerencia", indexes={@ORM\Index(name="fk_abg_sugerencia_ctl_usuario1_idx", columns={"ctl_usuario_id"})})
 * @ORM\Entity
 */
class AbgSugerencia
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
     * @ORM\Column(name="sugerencia", type="string", length=45, nullable=false)
     */
    private $sugerencia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

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
     * Set sugerencia
     *
     * @param string $sugerencia
     * @return AbgSugerencia
     */
    public function setSugerencia($sugerencia)
    {
        $this->sugerencia = $sugerencia;

        return $this;
    }

    /**
     * Get sugerencia
     *
     * @return string 
     */
    public function getSugerencia()
    {
        return $this->sugerencia;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return AbgSugerencia
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
     * Set ctlUsuario
     *
     * @param \DGAbgSistemaBundle\Entity\CtlUsuario $ctlUsuario
     * @return AbgSugerencia
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
