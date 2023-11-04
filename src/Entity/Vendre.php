<?php

namespace App\Entity;

use App\Entity\Ticket;
use App\Entity\Article;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Vendre
 *
 * @ORM\Table(name="vendre", indexes={
 *     @ORM\Index(name="FK_VENDRE_ARTICLE", columns={"ID_ARTICLE"}),
 *     @ORM\Index(name="FK_VENDRE_TICKET", columns={"NUMERO_TICKET", "ANNEE"})
 * })
 * @ORM\Entity
 */
class Vendre
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
     * @var Article|null
     *
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumn(name="ID_ARTICLE", referencedColumnName="ID_ARTICLE")
     */
    private $article;

    /**
     * @var int|null
     *
     * @ORM\Column(name="QUANTITE", type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PRIX_VENTE", type="decimal", nullable=true)
     */
    private $prixVente;

    /**
     * @var Ticket|null
     *
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="vendres")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="NUMERO_TICKET", referencedColumnName="NUMERO_TICKET"),
     *   @ORM\JoinColumn(name="ANNEE", referencedColumnName="ANNEE")
     * })
     */
    private $ticket;
    
    public function getNomArticle(): ?string
    {
        return $this->article->getNomArticle();
    }
    
    public function getIdArticle(): ?int
    {
        return $this->article->getIdArticle();
    }
    
    public function getNumeroTicket(): ?int
    {
        return $this->ticket->getNumeroTicket() ;
    }
    
    public function getAnnee(): ?int
    {
        return $this->ticket->getAnnee();
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setArticle(?Article $article): self // objet de l'instance Article
    {
        $this->article = $article; // prop article de Vendre devient l'objet de l'instance Article

        return $this; // en faisant return this, symfony va pouvoir chainer des methodes genre setArticle()->setPrix()
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(?float $prixVente): self
    {
        $this->prixVente = $prixVente;

        return $this;
    }
}
