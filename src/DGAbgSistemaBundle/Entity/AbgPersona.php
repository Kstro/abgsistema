<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgPersona
 *
 * @ORM\Table(name="abg_persona", indexes={@ORM\Index(name="fk_rh_persona_ctl_ciudad1_idx", columns={"ctl_ciudad_id"})})
 * @ORM\Entity
 */
class AbgPersona
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
     * @ORM\Column(name="nombres", type="string", length=45, nullable=false)
     */
    private $nombres;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=45, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="genero", type="string", length=15, nullable=true)
     */
    private $genero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ingreso", type="date", nullable=false)
     */
    private $fechaIngreso;

    /**
     * @var string
     *
     * @ORM\Column(name="dui", type="string", length=11, nullable=true)
     */
    private $dui;

    /**
     * @var string
     *
     * @ORM\Column(name="nit", type="string", length=17, nullable=true)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="correoelectronico", type="string", length=45, nullable=true)
     */
    private $correoelectronico;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=45, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_fijo", type="string", length=10, nullable=true)
     */
    private $telefonoFijo;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_movil", type="string", length=10, nullable=true)
     */
    private $telefonoMovil;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", nullable=true)
     */
    private $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var integer
     *
     * @ORM\Column(name="verificado", type="integer", nullable=false)
     */
    private $verificado;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=15, nullable=false)
     */
    private $codigo;

    /**
     * @var \CtlCiudad
     *
     * @ORM\ManyToOne(targetEntity="CtlCiudad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_ciudad_id", referencedColumnName="id")
     * })
     */
    private $ctlCiudad;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CtlEmpresa", inversedBy="abgPersona")
     * @ORM\JoinTable(name="abg_persona_empresa",
     *   joinColumns={
     *     @ORM\JoinColumn(name="abg_persona_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ctl_empresa_id", referencedColumnName="id")
     *   }
     * )
     */
    private $ctlEmpresa;

        /**
     * @var string
     *
     * @ORM\Column(name="titulo_profesional", type="string", length=100, nullable=true)
     */
    private $tituloProfesional;
    
     /**
     * @var string
     *
     * @ORM\Column(name="titulo_puesto", type="string", length=100, nullable=true)
     */
   private $tituloPuesto;
   
     /**
     * @var integer
     *
     * @ORM\Column(name="estado_metodo_pago", type="integer", nullable=false)
     */
   private $estadoMetodoPago;
   
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ctlEmpresa = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set estadoMetodoPago
     *
     * @param integer $estadoMetodoPago
     * @return AbgPersona
     */
    public function setEstadoMetodoPago($estadoMetodoPago)
    {
        $this->estadoMetodoPago = $estadoMetodoPago;

        return $this;
    }

    /**
     * Get estado_metodo_pago
     *
     * @return boolean 
     */
    public function getEstadoMetodoPago()
    {
        return $this->estadoMetodoPago;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     * @return AbgPersona
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string 
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return AbgPersona
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set genero
     *
     * @param string $genero
     * @return AbgPersona
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get genero
     *
     * @return string 
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     * @return AbgPersona
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime 
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set dui
     *
     * @param string $dui
     * @return AbgPersona
     */
    public function setDui($dui)
    {
        $this->dui = $dui;

        return $this;
    }

    /**
     * Get dui
     *
     * @return string 
     */
    public function getDui()
    {
        return $this->dui;
    }

    /**
     * Set nit
     *
     * @param string $nit
     * @return AbgPersona
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string 
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set correoelectronico
     *
     * @param string $correoelectronico
     * @return AbgPersona
     */
    public function setCorreoelectronico($correoelectronico)
    {
        $this->correoelectronico = $correoelectronico;

        return $this;
    }

    /**
     * Get correoelectronico
     *
     * @return string 
     */
    public function getCorreoelectronico()
    {
        return $this->correoelectronico;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return AbgPersona
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefonoFijo
     *
     * @param string $telefonoFijo
     * @return AbgPersona
     */
    public function setTelefonoFijo($telefonoFijo)
    {
        $this->telefonoFijo = $telefonoFijo;

        return $this;
    }

    /**
     * Get telefonoFijo
     *
     * @return string 
     */
    public function getTelefonoFijo()
    {
        return $this->telefonoFijo;
    }

    /**
     * Set telefonoMovil
     *
     * @param string $telefonoMovil
     * @return AbgPersona
     */
    public function setTelefonoMovil($telefonoMovil)
    {
        $this->telefonoMovil = $telefonoMovil;

        return $this;
    }

    /**
     * Get telefonoMovil
     *
     * @return string 
     */
    public function getTelefonoMovil()
    {
        return $this->telefonoMovil;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return AbgPersona
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return AbgPersona
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set verificado
     *
     * @param integer $verificado
     * @return AbgPersona
     */
    public function setAbgPersonacol($verificado)
    {
        $this->verificado = $verificado;

        return $this;
    }

    /**
     * Get verificado
     *
     * @return integer 
     */
    public function getVerificado()
    {
        return $this->verificado;
    }
 /**
     * Set verificado
     *
     * @param string $verificado
     * @return AbgPersona
     */
    public function setVerificado($verificado)
    {
        $this->verificado = $verificado;

        return $this;
    }
    /**
     * Set codigo
     *
     * @param string $codigo
     * @return AbgPersona
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set ctlCiudad
     *
     * @param \DGAbgSistemaBundle\Entity\CtlCiudad $ctlCiudad
     * @return AbgPersona
     */
    public function setCtlCiudad(\DGAbgSistemaBundle\Entity\CtlCiudad $ctlCiudad = null)
    {
        $this->ctlCiudad = $ctlCiudad;

        return $this;
    }

    /**
     * Get ctlCiudad
     *
     * @return \DGAbgSistemaBundle\Entity\CtlCiudad 
     */
    public function getCtlCiudad()
    {
        return $this->ctlCiudad;
    }

    /**
     * Add ctlEmpresa
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa
     * @return AbgPersona
     */
    public function addCtlEmpresa(\DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa)
    {
        $this->ctlEmpresa[] = $ctlEmpresa;

        return $this;
    }

    /**
     * Remove ctlEmpresa
     *
     * @param \DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa
     */
    public function removeCtlEmpresa(\DGAbgSistemaBundle\Entity\CtlEmpresa $ctlEmpresa)
    {
        $this->ctlEmpresa->removeElement($ctlEmpresa);
    }

    /**
     * Get ctlEmpresa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCtlEmpresa()
    {
        return $this->ctlEmpresa;
    }
    
     /**
     * Set tituloProfesional
     *
     * @param string  $tituloProfesional
     * @return AbgPersona
     */
    public function setTituloProfesional($tituloProfesional)
    {
        $this->tituloProfesional =  $tituloProfesional;

        return $this;
    }

    /**
     * Get tituloProfesional
     *
     * @return string
     */
    public function getTituloProfesional()
    {
        return $this->tituloProfesional;
    }
    
    
     /**
     * Set tituloPuesto
     *
     * @param string  $tituloPuesto
     * @return AbgPersona
     */
    public function setTituloPuesto($tituloPuesto)
    {
        $this->tituloPuesto =  $tituloPuesto;

        return $this;
    }

    /**
     * Get tituloPuesto
     *
     * @return string
     */
    public function getTituloPuesto()
    {
        return $this->tituloPuesto;
    }
    
     public function __toString() {
            return $this->nombres;
        }
}
