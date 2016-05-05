<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlCategoriaEntrada
 *
 * @ORM\Table(name="ctl_categoria_entrada")
 * @ORM\Entity
 */
class CtlCategoriaEntrada
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
     * @ORM\Column(name="nombre_categoria", type="string", length=60, nullable=false)
     */
    private $nombreCategoria;



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
     * Set nombreCategoria
     *
     * @param string $nombreCategoria
     * @return CtlCategoriaEntrada
     */
    public function setNombreCategoria($nombreCategoria)
    {
        $this->nombreCategoria = $nombreCategoria;

        return $this;
    }

    /**
     * Get nombreCategoria
     *
     * @return string 
     */
    public function getNombreCategoria()
    {
        return $this->nombreCategoria;
    }
}
