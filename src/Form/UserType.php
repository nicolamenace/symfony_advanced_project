<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType; 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'w-full px-4 py-2 rounded-lg border'],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'attr' => ['class' => 'w-full px-4 py-2 rounded-lg border'],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['class' => 'w-full px-4 py-2 rounded-lg border'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg'],
            ]);
        if ($options['is_edit'] === false) {
            $builder->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => ['class' => 'w-full px-4 py-2 rounded-lg border'],
            ]);
        }
        // Only add password field when creating a new user
        if ($options['is_edit'] === true) {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'space-y-2'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false// Default to false (meaning it's a new user)
        ]);
    }
    

}
