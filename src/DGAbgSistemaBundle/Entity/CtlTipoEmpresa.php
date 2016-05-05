<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlTipoEmpresa
 *
 * @ORM\Table(name="ctl_tipo_empresa")
 * @ORM\Entity
 */
class CtlTipoEmpresa
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
     * @ORM\Column(name="tipo_empresa", type="string", length=45, nullable=false)
     */
    private $tipoEmpresa;



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
     * Set tipoEmpresa
     *
     * @param string $tipoEmpresa
     * @return CtlTipoEmpresa
     */
    public function setTipoEmpresa($tipoEmpresa)
    {
        $this->tipoEmpresa = $tipoEmpresa;

        return $this;
    }

    /**
     * Get tipoEmpresa
     *
     * @return string 
     */
    public function getTipoEmpresa()
    {
        return $this->tipoEmpresa;
    }
}
