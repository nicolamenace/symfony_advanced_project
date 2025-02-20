<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CsvExporterService;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list')]
    public function list(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        
        return $this->render('products/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/create', name: 'product_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Directly storing the image URL instead of handling file upload
            $imageUrl = $form->get('image')->getData();
            if ($imageUrl) {
                $product->setImage($imageUrl); // Assuming a setImage() method exists
            }

            $entityManager->persist($product);
            $entityManager->flush();
            
            return $this->redirectToRoute('product_list');
        }

        return $this->render('products/create.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, 422));
    }

    #[Route('/delete-product/{id}', name: 'delete_product', methods: ['POST'])]
    public function deleteProduct($id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(Product::class)->find($id);

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

    #[Route('/export-products', name: 'product_export_csv')]
    public function exportProducts(CsvExporterService $csvExporterService)
    {
        return $csvExporterService->exportProducts();
    }

    #[Route('/product/edit/{id}', name: 'product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product updated successfully.');

            return $this->redirectToRoute('product_list');
        }

        return $this->render('products/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
