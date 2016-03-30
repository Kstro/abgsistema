<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgImagenBlog
 *
 * @ORM\Table(name="abg_imagen_blog", indexes={@ORM\Index(name="fk_ctl_imagen_blog_abg_entrada1_idx", columns={"abg_entrada_id"})})
 * @ORM\Entity
 */
class AbgImagenBlog
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
     * @var \AbgEntrada
     *
     * @ORM\ManyToOne(targetEntity="AbgEntrada")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abg_entrada_id", referencedColumnName="id")
     * })
     */
    private $abgEntrada;



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
     * @return AbgImagenBlog
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

    /**
     * Set abgEntrada
     *
     * @param \DGAbgSistemaBundle\Entity\AbgEntrada $abgEntrada
     * @return AbgImagenBlog
     */
    public function setAbgEntrada(\DGAbgSistemaBundle\Entity\AbgEntrada $abgEntrada = null)
    {
        $this->abgEntrada = $abgEntrada;

        return $this;
    }

    /**
     * Get abgEntrada
     *
     * @return \DGAbgSistemaBundle\Entity\AbgEntrada 
     */
    public function getAbgEntrada()
    {
        return $this->abgEntrada;
    }
}
