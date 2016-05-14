<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlImagenBlog
 *
 * @ORM\Table(name="ctl_imagen_blog")
 * @ORM\Entity
 */
class CtlImagenBlog
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
     * @ORM\Column(name="src", type="string", length=60, nullable=false)
     */
    private $src;



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
     * Set src
     *
     * @param string $src
     * @return CtlImagenBlog
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string 
     */
    public function getSrc()
    {
        return $this->src;
    }
}
