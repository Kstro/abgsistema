<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgComentario
 *
 * @ORM\Table(name="abg_comentario", indexes={@ORM\Index(name="fk_abg_comentario_abg_entrada1_idx", columns={"abg_entrada_id"})})
 * @ORM\Entity
 */
class AbgComentario
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
     * @ORM\Column(name="comentario", type="text", length=65535, nullable=false)
     */
    private $comentario;

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
     * Set comentario
     *
     * @param string $comentario
     * @return AbgComentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set abgEntrada
     *
     * @param \DGAbgSistemaBundle\Entity\AbgEntrada $abgEntrada
     * @return AbgComentario
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
