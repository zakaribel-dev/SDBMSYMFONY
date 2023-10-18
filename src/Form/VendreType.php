<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class VendreType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $articleChoices = $this->getArticleChoices();

        $builder
            ->add('article', ChoiceType::class, [
                'choices' => $articleChoices,
                'placeholder' => 'Sélectionner un article',
                'choice_label' => 'nomArticle',
            ])
            ->add('quantite', NumberType::class, [
                'html5' => true,
            ])
            ->add('prixVente', NumberType::class, [
                'html5' => true,
            ]);
    }

    private function getArticleChoices()
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();
        $choices = [];

        foreach ($articles as $article) {
            $choices[$article->getIdArticle()] = $article; 
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entityManager' => null, // Ajoutez cette ligne pour déclarer l'option
        ]);
    }
}