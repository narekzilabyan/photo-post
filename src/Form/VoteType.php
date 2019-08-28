<?php
/**
 * Created by PhpStorm.
 * User: n
 * Date: 8/28/19
 * Time: 1:02 PM
 */

namespace App\Form;


use App\Entity\Vote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('post')
            ->add('vote')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vote::class,
            'csrf_protection' => false,
        ]);
    }

}