<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Couleur
 *
 * @ORM\Table(name="couleur")
 * @ORM\Entity
 */
class Couleur
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_COULEUR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCouleur;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_COULEUR", type="string", length=55, nullable=false)
     */
    private $nomCouleur;
    
/**
 * @return string|null
 */
public function getNomCouleur(): ?string
{
    return $this->nomCouleur;
}

public function __toString(): string
{
    return $this->nomCouleur; 
}

}
