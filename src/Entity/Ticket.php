<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="ANNEE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $annee;

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="NUMERO_TICKET", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */

    private $numeroTicket;

    public function getNumeroTicket(): ?int
    {
        return $this->numeroTicket;
    }

    public function setNumeroTicket(int $numeroTicket): self
    {
        $this->numeroTicket = $numeroTicket;

        return $this;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_VENTE", type="datetime", nullable=false)
     */
    private $dateVente;

    public function __construct()
    {
        $this->dateVente = new \DateTime();
        // dès l'initialisation de l'entité je spécifie que la prop vendres est uen collection
        $this->vendres = new ArrayCollection(); // c'est vendres qui est inversed dans mon annotation de relation dans l'entité Vendre

    }

    /**
     * @return \DateTime|null
     */
    public function getDateVente(): ?\DateTime
    {
        return $this->dateVente;
    }

    public function setDateVente(\DateTime $dateVente): self
    {
        $this->dateVente = $dateVente;

        return $this;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Vendre", mappedBy="ticket")
     */
    private $vendres;

    /**
     * @return Collection|Vendre[]
     */


// MA COLLECTION GETVENDRE ME PERMET DE RECUPERER TOUTE MES VENTES LIE A MON TICKET PAR LE BIAIS DE LA PROP VENDRES
    public function getVendres(): Collection 
    {
        return $this->vendres;
    }
}