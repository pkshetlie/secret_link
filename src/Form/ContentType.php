<?php

namespace App\Form;

use App\Entity\Content;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',null,[
                "attr"=>[
                    "style"=>"min-height:200px;"
                ]
            ])
//            ->add('expirationDate',DateTimeType::class, [
//                'label' => 'Expiration date (facultative)',
//                'required' => false,
//                'input_format' => 'dd/MM/yyyy hh:ii',
//                'widget' => 'single_text',
//            ])
            ->add('CreateLink',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}
