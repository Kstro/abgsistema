<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlCategoriaBlog
 *
 * @ORM\Table(name="ctl_categoria_blog", indexes={@ORM\Index(name="fk_abg_entrada_ctl_categoria_blog_idx", columns={"abg_categoria_entrada_id"})})
 * @ORM\Entity
 */
class CtlCategoriaBlog
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
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)
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
     * @return CtlCategoriaBlog
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
    
    public function __toString() {
    return $this->nombreCategoria;
    }

}
