<?php

namespace App\DataFixtures;

use App\Entity\Clients;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $client = new Clients();
            $client->setFirstname($faker->firstName);
            $client->setLastname($faker->lastName);
            $client->setEmail($faker->unique()->safeEmail);
            $client->setPhonenumber(0000000000);
            $client->setAdress($faker->address);
            $client->setCreatedAt($faker->date('Y-m-d H:i:s'));

            $manager->persist($client);
        }

        $manager->flush();
    }
}
