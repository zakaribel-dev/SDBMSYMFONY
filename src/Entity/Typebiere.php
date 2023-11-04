<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Typebiere
 *
 * @ORM\Table(name="typebiere")
 * @ORM\Entity
 */
class Typebiere
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_TYPE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idType;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_TYPE", type="string", length=25, nullable=false)
     */
    private $nomType;
    
    public function __toString(): string
    {
        return $this->nomType; 
    }
    public function getNomType(): ?string
    {
        return $this->nomType;
    }

    public function getIdType(): ?int
    {
        return $this->idType;
    }

    public function setNomType(string $nomType): static
    {
        $this->nomType = $nomType;

        return $this;
    }
    
  
}
