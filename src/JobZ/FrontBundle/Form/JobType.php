<?php

namespace JobZ\FrontBundle\Form;

use JobZ\FrontBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'Full-time' => 'full-time',
                    'Part-time' => 'part-time',
                    'Freelance' => 'freelance',
                ),
            ))
            ->add('location')
            ->add('position')
            ->add('company')
            ->add('description', TextareaType::class)
            ->add('apply', TextareaType::class)
            ->add('email')
            ->add('url', UrlType::class)
            ->add('category', EntityType::class, array(
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'name'
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JobZ\FrontBundle\Entity\Job'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'jobz_frontbundle_job';
    }


}
