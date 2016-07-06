<?php

namespace DGAbgSistemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbgPagadito
 *
 * @ORM\Table(name="abg_pagadito", indexes={@ORM\Index(name="fk_pagadito_facturacion", columns={"idfacturacion"})})
 * @ORM\Entity
 */
class AbgPagadito
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
     * @ORM\Column(name="ern", type="string", length=100, nullable=false)
     */
    private $ern;
    
    /**
     * @var string
     *
     * @ORM\Column(name="pg", type="string", length=100, nullable=false)
     */
    private $pg;
    
    /**
     * @var string
     *
     * @ORM\Column(name="token_auto", type="string", length=100, nullable=false)
     */
    private $token_auto;
    
    /**
     * @var string
     *
     * @ORM\Column(name="token_cobro", type="string", length=100, nullable=false)
     */
    private $token_cobro;
    
    /**
     * @var date
     *
     * @ORM\Column(name="fechacobro", type="date", length=100, nullable=false)
     */
    private $fechacobro;
    
    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=100, nullable=false)
     */
    private $estado;

    /**
     * @var \AbgFacturacion
     *
     * @ORM\ManyToOne(targetEntity="AbgFacturacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idfacturacion", referencedColumnName="id")
     * })
     */
    private $idFacturacion;



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
     * Set ern
     * @param string $ern
     * @return AbgPagadito
     */
    public function setErn($ern)
    {
        $this->ern = $ern;

        return $this;
    }

    /**
     * Get ern
     *
     * @return ern 
     */
    public function getErn()
    {
        return $this->ern;
    }
    
    /**
     * Set pg
     * @param string $pg
     * @return AbgPagadito
     */
    public function setPg($pg) {
        $this->pg = $pg;

        return $this;
    }

    /**
     * Get pg
     *
     * @return pg 
     */
    public function getPg() {
        return $this->pg;
    }
    
    /**
     * Set token_auto
     * @param string $token_auto
     * @return AbgPagadito
     */
    public function setToken_auto($token_auto) {
        $this->token_auto = $token_auto;

        return $this;
    }

    /**
     * Get token_auto
     *
     * @return token_auto 
     */
    public function getToken_auto() {
        return $this->token_auto;
    }
    
    /**
     * Set token_cobro
     * @param string $token_cobro
     * @return AbgPagadito
     */
    public function setToken_cobro($token_cobro) {
        $this->token_cobro = $token_cobro;

        return $this;
    }

    /**
     * Get token_cobro
     *
     * @return token_cobro 
     */
    public function getToken_cobro() {
        return $this->token_cobro;
    }
    
    /**
     * Set fechacobro
     * @param string $fechacobro
     * @return AbgPagadito
     */
    public function setFechacobro($fechacobro) {
        $this->fechacobro = $fechacobro;

        return $this;
    }

    /**
     * Get fechacobro
     *
     * @return fechacobro
     */
    public function getFechacobro() {
        return $this->fechacobro;
    }
    
    /**
     * Set estado
     * @param string $estado
     * @return AbgPagadito
     */
    public function setEstado($estado) {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return estado
     */
    public function getEstado() {
        return $this->estado;
    }
    
    /**
     * Set primercobro
     * @param string $primercobro
     * @return AbgPagadito
     */
    public function setPrimercobro($primercobro) {
        $this->primercobro = $primercobro;

        return $this;
    }

    /**
     * Get primercobro
     *
     * @return primercobro
     */
    public function getPrimercobro() {
        return $this->primercobro;
    }

    /**
     * Set idFacturacion
     *
     * @param \DGAbgSistemaBundle\Entity\AbgFacturacion $idFacturacion
     * @return AbgFacturacion
     */
    public function setAbgFacturacion(\DGAbgSistemaBundle\Entity\AbgFacturacion $idFacturacion = null)
    {
        $this->idFacturacion = $idFacturacion;

        return $this;
    }

    /**
     * Get idFacturacion
     *
     * @return \DGAbgSistemaBundle\Entity\AbgFacturacion 
     */
    public function getAbgFacturacion()
    {
        return $this->idFacturacion;
    }
}
