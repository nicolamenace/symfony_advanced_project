<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {   
        $users = [
            'user1'=>['email'=>'garenmainm7@test.com', 'password'=>'password1', 'roles'=>['ROLE_USER'], 'firstname'=>'Garen', 'lastname'=>'Demacia'],
            'user2'=>['email'=>'donerkebabnacho@test.com', 'password'=>'password2', 'roles'=>['ROLE_ADMIN'],'firstname'=>'Faker', 'lastname'=>'DaGoat'],
            'user3'=>['email'=>'brainrotenjoyer@test.com', 'password'=>'password3', 'roles'=>['ROLE_USER'],'firstname'=>'Tahm', 'lastname'=>'Kench'],
            'user4'=>['email'=>'armpitsniffer@test.com', 'password'=>'password4', 'roles'=>['ROLE_MANAGER'],'firstname'=>'Homme', 'lastname'=>'Génial'],
        ];

        foreach ($users as $user) {
            $newuser = new User();
            $newuser->setEmail($user['email']);
            $hashedPassword = $this->passwordHasher->hashPassword($newuser,$user["password"]);
            $newuser->setPassword($hashedPassword);
            $newuser->setRoles($user['roles']);
            $newuser->setLastName($user['lastname']);
            $newuser->setFirstName($user['firstname']);
            $manager->persist($newuser);
        }

        $manager->flush();
    }
}
