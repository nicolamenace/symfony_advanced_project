<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientType;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'clients_list')]
    public function index(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authChecker): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_CLIENTS');

        $clients = $entityManager->getRepository(Clients::class)->findAll();

        return $this->render('clients/list.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/clients/create', name: 'clients_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, ClientsRepository $clientsRepository): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_CLIENTS');

        $client = new Clients();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if email already exists
            $existingClient = $clientsRepository->findOneBy(['email' => $client->getEmail()]);
            if ($existingClient) {
                $this->addFlash('error', 'This email is already in use.');
                return $this->render('clients/create.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client created successfully.');
            return $this->redirectToRoute('clients_list');
        }

        return $this->render('clients/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/client/edit/{id}', name: 'client_edit')]
    public function edit(Clients $client, Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ACCESS_CLIENTS');

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'Client updated successfully.');

            return $this->redirectToRoute('clients_list');
        }

        return $this->render('clients/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete-client/{id}', name: 'delete_client', methods: ['POST'])]
    public function deleteClient($id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ACCESS_CLIENTS');

        $client = $em->getRepository(Clients::class)->find($id);

        if (!$client) {
            return $this->json(['success' => false, 'message' => 'Utilisateur non trouvé']);
        }

        try {
            $em->remove($client);
            $em->flush();
            return $this->json(['success' => true]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
}
