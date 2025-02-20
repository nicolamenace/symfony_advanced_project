<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/create", name="create")
     */
    #[Route('/create', name: 'create')]
    public function createUser(Request $request, EntityManagerInterface $entityManager)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully!');

            return $this->redirectToRoute('users');
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
            'is_role_management' => false,
        ]);
    }

    /**
     * @Route("/users", name="users")
     */
    #[Route('/users', name: 'users')]
    public function list(UserRepository $userRepository)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('home');
        }

        $users = $userRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        $user = $this->getUser();
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    #[Route('/user/edit/{id}', name: 'user_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('users');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delete-use/{id}", name="delete_user", methods={"POST"})
     */
    #[Route('/delete-user/{id}', name: 'delete_user', methods: ['POST'])]
    public function deleteUser($id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['success' => false, 'message' => 'Utilisateur non trouvé']);
        }

        try {
            $em->remove($user);
            $em->flush();
            return $this->json(['success' => true]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }

}
