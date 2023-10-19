<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'placeholder' => 'Sélectionnez',
                'choice_label' => 'nomArticle',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('quantite', NumberType::class, [
                'html5' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prixVente', NumberType::class, [
                'html5' => true,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    private function getArticleChoices()  // FONCTION ME PERMETTANT DE RECUPERER TOUS MES ARTICLES
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll(); 
        $choices = []; // JE STOCKE LES ARTICLES DANS CE PETIT TABLEAU DE CHOIX

        foreach ($articles as $article) {
            $choices[$article->getIdArticle()] = $article;   // LES INDEX SONT LES ID DES ARTICLES leurs valeurs leurs nom
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([  
            'entityManager' => null, // JE SUIS OBLIGé DE DECLARER L OPTION ENTITY MANAGER SINAN CA FONCTIONNE PAS
        ]);

      // PAS BESOIN DE RETOURNER QUOI QUE CE SOIT PARCE QUE LE PARAMETRE OPTIONSRESOLVER FAIT SON JOB..
    }
}