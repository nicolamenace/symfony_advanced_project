<?php

namespace App\Command;

use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:create-client', description: 'Creates a new client.')] 
class CreateClientCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        
        $output->writeln('<info>Creating a new client...</info>');
        
        $firstnameQuestion = new Question('Enter first name: ');
        $lastnameQuestion = new Question('Enter last name: ');
        $emailQuestion = new Question('Enter email: ');
        $phonenumberQuestion = new Question('Enter phone number (optional): ', null);
        $adressQuestion = new Question('Enter address: ');

        $firstname = $helper->ask($input, $output, $firstnameQuestion);
        $lastname = $helper->ask($input, $output, $lastnameQuestion);
        $email = $helper->ask($input, $output, $emailQuestion);
        $phonenumber = $helper->ask($input, $output, $phonenumberQuestion);
        $adress = $helper->ask($input, $output, $adressQuestion);
        
        $client = new Clients();
        $client->setFirstname($firstname);
        $client->setLastname($lastname);
        $client->setEmail($email);
        $client->setPhonenumber($phonenumber ? (int)$phonenumber : null);
        $client->setAdress($adress);
        $client->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
        
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $output->writeln('<info>Client successfully created!</info>');
        
        return Command::SUCCESS;
    }
}
