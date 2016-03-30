<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlOrganizacion
 *
 * @ORM\Table(name="ctl_organizacion")
 * @ORM\Entity
 */
class CtlOrganizacion
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
     * @ORM\Column(name="nombre_organizacion", type="string", length=45, nullable=false)
     */
    private $nombreOrganizacion;



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
     * Set nombreOrganizacion
     *
     * @param string $nombreOrganizacion
     * @return CtlOrganizacion
     */
    public function setNombreOrganizacion($nombreOrganizacion)
    {
        $this->nombreOrganizacion = $nombreOrganizacion;

        return $this;
    }

    /**
     * Get nombreOrganizacion
     *
     * @return string 
     */
    public function getNombreOrganizacion()
    {
        return $this->nombreOrganizacion;
    }
}
