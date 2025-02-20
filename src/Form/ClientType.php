<?php

namespace App\Form;

use App\Entity\Clients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'First name is required.']),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z]+$/",
                        'message' => 'First name should only contain letters.'
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Last name is required.']),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z]+$/",
                        'message' => 'Last name should only contain letters.'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Email is required.']),
                    new Assert\Email(['message' => 'Invalid email format.']),
                    new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/",
                        'message' => 'Email format must be xxx@xx.xx'
                    ])
                ]
            ])
            ->add('phonenumber', IntegerType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => "/^\d{10}$/",
                        'message' => 'Phone number should be 10 digits.'
                    ])
                ]
            ])
            ->add('adress', TextType::class, [
                'label' => 'Address',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Address is required.'])
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Client']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
