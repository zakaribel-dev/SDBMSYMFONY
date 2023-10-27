<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="IDX_23A0E66F7270350", columns={"ID_MARQUE"}), @ORM\Index(name="IDX_23A0E66F97A9A35", columns={"ID_TYPE"}), @ORM\Index(name="IDX_23A0E66107951FC", columns={"ID_COULEUR"})})
 * @ORM\Entity
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ARTICLE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArticle;

    /**
     * @var float
     *
     * @ORM\Column(name="PRIX_ACHAT", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixAchat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="VOLUME", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $volume = NULL;

    /**
     * @var float|null
     *
     * @ORM\Column(name="TITRAGE", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     */
    private $titrage = NULL;

    /**
     * @var \Marque|null
     *
     * @ORM\ManyToOne(targetEntity="Marque")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_MARQUE", referencedColumnName="ID_MARQUE")
     * })
     */
    private $idMarque;

    /**
     * @var \Typebiere|null
     *
     * @ORM\ManyToOne(targetEntity="Typebiere")
     * @ORM\JoinColumn(name="ID_TYPE", referencedColumnName="ID_TYPE", nullable=true)
     */
    private $idType;

    /**
     * @var \Couleur|null
     *
     * @ORM\ManyToOne(targetEntity="Couleur")
     * @ORM\JoinColumn(name="ID_COULEUR", referencedColumnName="ID_COULEUR", nullable=true)
     */
    private $idCouleur;

    public function getIdType(): ?Typebiere
    {
        return $this->idType;
    }

    public function setIdType(?Typebiere $idType): self
    {
        $this->idType = $idType;

        return $this;
    }

    public function getIdCouleur(): ?Couleur
    {
        return $this->idCouleur;
    }

    public function setIdCouleur(?Couleur $idCouleur): self // self pour qu'il fasse reference à la classe Couleur quand il set la value
    {
        $this->idCouleur = $idCouleur;

        return $this;
    }

    public function getIdMarque(): ?Marque // ?Marque veut dire que la methode doit se referer à l'entité marque
    {
        return $this->idMarque; // de ce fait, idMarque donne accès aux methodes de l'entité MArque
    }

    public function setIdMarque(?Marque $idMarque): self
    {
        $this->idMarque = $idMarque;
    
        return $this;
    }

  
    public function getNomCouleur(): ?string
    {
        if ($this->idCouleur !== null) { 
            return $this->idCouleur->getNomCouleur();
        } else {
            return "non renseigné";
        }
    }
    
    public function getNomType(): ?string
    {
        if ($this->idType !== null) {
            return $this->idType->getNomType();
        } else {
            return "non renseigné";
        }
    }

 
    public function getNomMarque(): ?string
    {
        if ($this->idMarque !== null) {
            return $this->idMarque->getNomMarque();
        } else {
            return "non renseigné";
        }
    }

    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function setIdArticle(int $idArticle): self
    {
        $this->idArticle = $idArticle;

        return $this;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="NOM_ARTICLE", type="string", length=60, nullable=false)
     */
    private $nomArticle;

    /**
     * @return string
     */
    public function getNomArticle(): ?string
    {
        return $this->nomArticle;
    }

    /**
     * @param string 
     * @return self
     */
    public function setNomArticle(string $nomArticle): self
    {
        $this->nomArticle = $nomArticle;

        return $this;
    }


    /**
     * @return float
     */
    public function getPrixAchat(): float
    {
        return $this->prixAchat;
    }

    /* 
 * @param float $prixAchat
 * @return self
 */
    public function setPrixAchat(float $prixAchat): self
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }


    /**
     * @return float
     */
    public function getVolume(): float
    {
       
            return $this->volume;
        
    }

    /* 
 * @param float 
 * @return self
 */
    public function setVolume(float $Volume): self
    {
        $this->volume = $Volume;

        return $this;
    }


    /**
     * @return float|null
     */
    public function getTitrage(): ?float
    {
        return $this->titrage;
    }

/* 
 * @param float 
 * @return self
 */
    public function setTitrage(float $Titrage): self
    {
        $this->titrage = $Titrage;

        return $this;
    }
}
