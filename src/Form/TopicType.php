<?php

namespace App\Form;

use App\Entity\Topic;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopicType extends AbstractType {

    private CategoryRepository $repoCategory;

    public function __construct(CategoryRepository $_repoCategory) {
        $this->repoCategory = $_repoCategory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tbaCategories = $this->repoCategory->findAll();
        $tbaCheckbox   = [];
        $checked       = [];

        foreach($tbaCategories as $repo)
            $tbaCheckbox[$repo->getLibelle()] = $repo->getID();

        if (!empty($options['data']->categoriesTopic)) {
            foreach ($options['data']->categoriesTopic as $category) {
                $checked[$category['libelle']] = $category['id'];
            }
        }

        $builder
            ->add('title')
            ->add('content')
            ->add('topicCategory', ChoiceType::class, array(
                'label'       => 'Catégorie',
                'mapped'      => false,
                'multiple'    => true,
                'expanded'    => true,
                'choices'     => $tbaCheckbox,
                'choice_attr' => function ($choice, $key, $value) use ($checked) {

                    if (!empty($checked)) {
                        if (array_key_exists($key, $checked)) {
                            return ['checked' => 'checked'];
                        } else {
                            return ['checked' => false];
                        }
                    } else {
                        return ['checked' => false];
                    }
                }))
            ->add('save', SubmitType::class, ['label' => 'Valider'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
