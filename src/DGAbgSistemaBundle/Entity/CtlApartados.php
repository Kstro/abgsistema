<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlApartados
 *
 * @ORM\Table(name="ctl_apartados")
 * @ORM\Entity
 */
class CtlApartados
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
     * @ORM\Column(name="apartado", type="string", length=60, nullable=false)
     */
    private $apartado;



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
     * Set apartado
     *
     * @param string $apartado
     * @return CtlApartados
     */
    public function setApartado($apartado)
    {
        $this->apartado = $apartado;

        return $this;
    }

    /**
     * Get apartado
     *
     * @return string 
     */
    public function getApartado()
    {
        return $this->apartado;
    }
}
