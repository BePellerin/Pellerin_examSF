<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;



class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', null, ['label' => 'Nom', "attr" => ["placeholder" => "Ajouter un Nom"]])
            ->add('username', null, ['label' => 'Prénom', "attr" => ["placeholder" => "Ajouter un Prénom"]])
            ->add('roles', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Rôle',
                'choices' => [
                    'USER' => 'ROLE_USER',
                    'RH' => 'ROLE_RH'

                ],
            ])
            ->add('secteur', ChoiceType::class, [
                'choices' => [
                    'RH' => 'RH',
                    'Informatique' => 'Informatique',
                    'Comptabilité' => 'Comptabilité',
                    'Direction' => 'Direction',
                ]
            ])
            ->add('typecontrat', ChoiceType::class, [
                'label' => 'Type de contrat',
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Interim' => 'Interim',
                ]
            ])
            ->add('datesortie', DateType::class, [
                'label' => 'Date de sortie',
                'widget' => 'single_text',
                // 'input'  => 'datetime_immutable',
                'format' => 'dd-MM-yyyy',
                'required' => false,
                'html5' => false,
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => $options["requiredImg"],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Merci de télécharger une image',
                    ])
                ],
            ])
            // ...

            ->add('password', null, ["attr" => ["placeholder" => "Ajouter un mot de passe"]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "requiredImg" => false,
        ]);
    }
}
