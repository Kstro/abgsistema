<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlCiudad
 *
 * @ORM\Table(name="ctl_ciudad", indexes={@ORM\Index(name="fk_ctgl_ciudad_ctl_estado1_idx", columns={"ctl_estado_id"})})
 * @ORM\Entity
 */
class CtlCiudad
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
     * @ORM\Column(name="nombre_ciudad", type="string", length=45, nullable=false)
     */
    private $nombreCiudad;

    /**
     * @var \CtlEstado
     *
     * @ORM\ManyToOne(targetEntity="CtlEstado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_estado_id", referencedColumnName="id")
     * })
     */
    private $ctlEstado;



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
     * Set nombreCiudad
     *
     * @param string $nombreCiudad
     * @return CtlCiudad
     */
    public function setNombreCiudad($nombreCiudad)
    {
        $this->nombreCiudad = $nombreCiudad;

        return $this;
    }

    /**
     * Get nombreCiudad
     *
     * @return string 
     */
    public function getNombreCiudad()
    {
        return $this->nombreCiudad;
    }

    /**
     * Set ctlEstado
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEstado $ctlEstado
     * @return CtlCiudad
     */
    public function setCtlEstado(\DGAbgSistemaBundle\Entity\CtlEstado $ctlEstado = null)
    {
        $this->ctlEstado = $ctlEstado;

        return $this;
    }

    /**
     * Get ctlEstado
     *
     * @return \DGAbgSistemaBundle\Entity\CtlEstado 
     */
    public function getCtlEstado()
    {
        return $this->ctlEstado;
    }
}
