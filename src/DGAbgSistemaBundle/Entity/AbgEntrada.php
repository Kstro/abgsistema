<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgEntrada
 *
 * @ORM\Table(name="abg_entrada", indexes={@ORM\Index(name="fk_abg_entrada_ctl_usuario1_idx", columns={"ctl_usuario_id"}), @ORM\Index(name="fk_abg_entrada_abg_subespecialidad1_idx", columns={"abg_subespecialidad_id"})})
 * @ORM\Entity
 */
class AbgEntrada
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
     * @ORM\Column(name="titulo_entrada", type="string", length=200, nullable=false)
     */
    private $tituloEntrada;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha", type="string", length=45, nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text", length=65535, nullable=false)
     */
    private $contenido;

    /**
     * @var integer
     *
     * @ORM\Column(name="abg_categoria_entrada_id", type="integer", nullable=false)
     */
    private $abgCategoriaEntradaId;

    /**
     * @var \CtlSubespecialidad
     *
     * @ORM\ManyToOne(targetEntity="CtlSubespecialidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abg_subespecialidad_id", referencedColumnName="id")
     * })
     */
    private $abgSubespecialidad;

    /**
     * @var \CtlUsuario
     *
     * @ORM\ManyToOne(targetEntity="CtlUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_usuario_id", referencedColumnName="id")
     * })
     */
    private $ctlUsuario;



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
     * Set tituloEntrada
     *
     * @param string $tituloEntrada
     * @return AbgEntrada
     */
    public function setTituloEntrada($tituloEntrada)
    {
        $this->tituloEntrada = $tituloEntrada;

        return $this;
    }

    /**
     * Get tituloEntrada
     *
     * @return string 
     */
    public function getTituloEntrada()
    {
        return $this->tituloEntrada;
    }

    /**
     * Set fecha
     *
     * @param string $fecha
     * @return AbgEntrada
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return string 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return AbgEntrada
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set abgCategoriaEntradaId
     *
     * @param integer $abgCategoriaEntradaId
     * @return AbgEntrada
     */
    public function setAbgCategoriaEntradaId($abgCategoriaEntradaId)
    {
        $this->abgCategoriaEntradaId = $abgCategoriaEntradaId;

        return $this;
    }

    /**
     * Get abgCategoriaEntradaId
     *
     * @return integer 
     */
    public function getAbgCategoriaEntradaId()
    {
        return $this->abgCategoriaEntradaId;
    }

    /**
     * Set abgSubespecialidad
     *
     * @param \DGAbgSistemaBundle\Entity\CtlSubespecialidad $abgSubespecialidad
     * @return AbgEntrada
     */
    public function setAbgSubespecialidad(\DGAbgSistemaBundle\Entity\CtlSubespecialidad $abgSubespecialidad = null)
    {
        $this->abgSubespecialidad = $abgSubespecialidad;

        return $this;
    }

    /**
     * Get abgSubespecialidad
     *
     * @return \DGAbgSistemaBundle\Entity\CtlSubespecialidad 
     */
    public function getAbgSubespecialidad()
    {
        return $this->abgSubespecialidad;
    }

    /**
     * Set ctlUsuario
     *
     * @param \DGAbgSistemaBundle\Entity\CtlUsuario $ctlUsuario
     * @return AbgEntrada
     */
    public function setCtlUsuario(\DGAbgSistemaBundle\Entity\CtlUsuario $ctlUsuario = null)
    {
        $this->ctlUsuario = $ctlUsuario;

        return $this;
    }

    /**
     * Get ctlUsuario
     *
     * @return \DGAbgSistemaBundle\Entity\CtlUsuario 
     */
    public function getCtlUsuario()
    {
        return $this->ctlUsuario;
    }
}
