<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtlEmpresa
 *
 * @ORM\Table(name="ctl_empresa", indexes={@ORM\Index(name="fk_ctl_empresa_ctl_ciudad1_idx", columns={"ctl_ciudad_id"}), @ORM\Index(name="fk_ctl_empresa_ctl_tipo_empresa1_idx", columns={"ctl_tipo_empresa_id"})})
 * @ORM\Entity
 */
class CtlEmpresa
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
     * @ORM\Column(name="nombre_empresa", type="string", length=45, nullable=true)
     */
    private $nombreEmpresa;

    /**
     * @var string
     *
     * @ORM\Column(name="nit", type="string", length=45, nullable=true)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="servicios", type="string", length=60, nullable=true)
     */
    private $servicios;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fundacion", type="date", nullable=true)
     */
    private $fechaFundacion;

    /**
     * @var string
     *
     * @ORM\Column(name="foto_perfil", type="string", length=60, nullable=true)
     */
    private $fotoPerfil;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=75, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="sitio_web", type="string", length=60, nullable=true)
     */
    private $sitioWeb;

    /**
     * @var string
     *
     * @ORM\Column(name="correoelectronico", type="string", length=45, nullable=true)
     */
    private $correoelectronico;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_", type="string", length=9, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="movil", type="string", length=9, nullable=true)
     */
    private $movil;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=20, nullable=true)
     */
    private $fax;


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
     * @var \CtlTipoEmpresa
     *
     * @ORM\ManyToOne(targetEntity="CtlTipoEmpresa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctl_tipo_empresa_id", referencedColumnName="id")
     * })
     */
    private $ctlTipoEmpresa;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AbgPersona", mappedBy="ctlEmpresa")
     */
    private $abgPersona;
    
     /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=60, nullable=false)
     */
    private $color;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cantidad_empleados", type="string", length=60, nullable=false)
     */
    private $cantidadEmpleados;
    
    
    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=false)
     */
    private $longitude;
    
     /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", nullable=false)
     */
    private $latitude;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="listaEmpleado", type="integer", nullable=false)
     */
    private $listaEmpleado;
    
    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->abgPersona = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombreEmpresa
     *
     * @param string $nombreEmpresa
     * @return CtlEmpresa
     */
    public function setNombreEmpresa($nombreEmpresa)
    {
        $this->nombreEmpresa = $nombreEmpresa;

        return $this;
    }

    /**
     * Get nombreEmpresa
     *
     * @return string 
     */
    public function getNombreEmpresa()
    {
        return $this->nombreEmpresa;
    }

    /**
     * Set nit
     *
     * @param string $nit
     * @return CtlEmpresa
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
     * Set servicios
     *
     * @param string $servicios
     * @return CtlEmpresa
     */
    public function setServicios($servicios)
    {
        $this->servicios = $servicios;

        return $this;
    }

    /**
     * Get servicios
     *
     * @return string 
     */
    public function getServicios()
    {
        return $this->servicios;
    }

    /**
     * Set fechaFundacion
     *
     * @param \DateTime $fechaFundacion
     * @return CtlEmpresa
     */
    public function setFechaFundacion($fechaFundacion)
    {
        $this->fechaFundacion = $fechaFundacion;

        return $this;
    }
    
    /**
     * Get fechaFundacion
     *
     * @return \DateTime 
     */
    public function getFechaFundacion()
    {
        return $this->fechaFundacion;
    }

    /**
     * Set fotoPerfil
     *
     * @param string $fotoPerfil
     * @return CtlEmpresa
     */
    public function setFotoPerfil($fotoPerfil)
    {
        $this->fotoPerfil = $fotoPerfil;

        return $this;
    }

    /**
     * Get fotoPerfil
     *
     * @return string 
     */
    public function getFotoPerfil()
    {
        return $this->fotoPerfil;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return CtlEmpresa
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
     * Set direccion
     *
     * @param string $direccion
     * @return CtlEmpresa
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
     * Set sitioWeb
     *
     * @param string $sitioWeb
     * @return CtlEmpresa
     */
    public function setSitioWeb($sitioWeb)
    {
        $this->sitioWeb = $sitioWeb;

        return $this;
    }

    /**
     * Get sitioWeb
     *
     * @return string 
     */
    public function getSitioWeb()
    {
        return $this->sitioWeb;
    }

    /**
     * Set correoelectronico
     *
     * @param string $correoelectronico
     * @return CtlEmpresa
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
     * Set telefono
     *
     * @param string $telefono
     * @return CtlEmpresa
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set movil
     *
     * @param string $movil
     * @return CtlEmpresa
     */
    public function setMovil($movil)
    {
        $this->movil = $movil;

        return $this;
    }

    /**
     * Get movil
     *
     * @return string 
     */
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return CtlEmpresa
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }



    /**
     * Set cantidadEmpleados
     *
     * @param string $cantidadEmpleados
     * @return CtlEmpresa
     */
    public function setCantidadEmpleados($cantidadEmpleados)
    {
        $this->cantidadEmpleados = $cantidadEmpleados;

        return $this;
    }

    /**
     * Get cantidadEmpleados
     *
     * @return string 
     */
    public function getCantidadEmpleados()
    {
        return $this->cantidadEmpleados;
    }

    /**
     * Set ctlCiudad
     *
     * @param \DGAbgSistemaBundle\Entity\CtlCiudad $ctlCiudad
     * @return CtlEmpresa
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
     * Set ctlTipoEmpresa
     *
     * @param \DGAbgSistemaBundle\Entity\CtlTipoEmpresa $ctlTipoEmpresa
     * @return CtlEmpresa
     */
    public function setCtlTipoEmpresa(\DGAbgSistemaBundle\Entity\CtlTipoEmpresa $ctlTipoEmpresa = null)
    {
        $this->ctlTipoEmpresa = $ctlTipoEmpresa;

        return $this;
    }

    /**
     * Get ctlTipoEmpresa
     *
     * @return \DGAbgSistemaBundle\Entity\CtlTipoEmpresa 
     */
    public function getCtlTipoEmpresa()
    {
        return $this->ctlTipoEmpresa;
    }

    /**
     * Add abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     * @return CtlEmpresa
     */
    public function addAbgPersona(\DGAbgSistemaBundle\Entity\AbgPersona $abgPersona)
    {
        $this->abgPersona[] = $abgPersona;

        return $this;
    }

    /**
     * Remove abgPersona
     *
     * @param \DGAbgSistemaBundle\Entity\AbgPersona $abgPersona
     */
    public function removeAbgPersona(\DGAbgSistemaBundle\Entity\AbgPersona $abgPersona)
    {
        $this->abgPersona->removeElement($abgPersona);
    }

    /**
     * Get abgPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAbgPersona()
    {
        return $this->abgPersona;
    }
    
    
    
     /**
     * Set color
     *
     * @param string $color
     * @return CtlEmpresa
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }
     
    
    /**
     * Set latitude
     *
     * @param float $latitude
     * @return CtlEmpresa
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    
      /**
     * Set longitude
     *
     * @param float $longitude
     * @return CtlEmpresa
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    
    
    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    
    
    /**
     * Set listaEmpleado
     *
     * @param integer $listaEmpleado
     * @return CtlEmpresa
     */
    public function setListaEmpleado($listaEmpleado)
    {
        $this->listaEmpleado = $listaEmpleado;

        return $this;
    }

    /**
     * Get listaEmpleado
     *
     * @return integer
     */
    public function getListaEmpleado()
    {
        return $this->listaEmpleado;
    }

    
    
    
    
    
    
    
}
