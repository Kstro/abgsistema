<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlTituloProfesional
 *
 * @ORM\Table(name="ctl_titulo_profesional")
 * @ORM\Entity
 */
class CtlTituloProfesional
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
     * @ORM\Column(name="abg_titulocol", type="string", length=45, nullable=false)
     */
    private $abgTitulocol;



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
     * Set abgTitulocol
     *
     * @param string $abgTitulocol
     * @return CtlTituloProfesional
     */
    public function setAbgTitulocol($abgTitulocol)
    {
        $this->abgTitulocol = $abgTitulocol;

        return $this;
    }

    /**
     * Get abgTitulocol
     *
     * @return string 
     */
    public function getAbgTitulocol()
    {
        return $this->abgTitulocol;
    }
}
