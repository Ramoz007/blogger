<?php
namespace App\Form\Type;

use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [ 'attr' => ['class' => 'form-control'],])
            ->add('body', TextType::class, [ 'attr' => ['class' => 'form-control'],])
            ->add('rates', IntegerType::class, [ 'attr' => ['class' => 'form-control', 'min' => 1, 'max' => 5], 'label' => 'Must-Read Rating (1-5)'])
            ->add('submit', SubmitType::class, [ 'attr' => ['class' => 'form-control btn btn-primary mb-3'],]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}