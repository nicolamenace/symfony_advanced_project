<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporterService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function exportProducts(): StreamedResponse
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($handle, ['ID', 'Nom', 'Prix', 'Description', 'Image']);

            // Fetch products from DB
            foreach ($this->productRepository->findAll() as $product) {
                fputcsv($handle, [
                    $product->getId(),
                    $product->getName(),
                    $product->getPrice(),
                    $product->getDescription(),
                    $product->getImage(),
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="products.csv"');

        return $response;
    }
}
