<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * AdmImagenPromocion
 *
 * @ORM\Table(name="adm_imagen_promocion")
 * @ORM\Entity
 */
class AdmImagenPromocion
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
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;
    
     /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    
    /**
     * @var \AdmPromociones
     *
     * @ORM\ManyToOne(targetEntity="AdmPromociones", inversedBy="placas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="promocion", referencedColumnName="id")
     * })
     */
    private $promocion;
    
 
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
     * Set imagen
     *
     * @param string $imagen
     *
     * @return AdmImagenPromocion
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
     /**
     * Set promocion
     *
     * @param \DGAbgSistemaBundle\Entity\AdmPromociones $admPromociones
     *
     * @return AdmImagenPromocion
     */
    public function setPromocion(\DGAbgSistemaBundle\Entity\AdmPromociones $admPromociones = null)
    {
        $this->promocion = $admPromociones;

        return $this;
    }

    /**
     * Get promocion
     *
     * @return \DGAbgSistemaBundle\Entity\AdmPromociones
     */
    public function getPromocion()
    {
        return $this->promocion;
    }

}
