<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CsvImporterService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function importProducts(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception('Fichier introuvable: ' . $filePath);
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception('Impossible d\'ouvrir le fichier: ' . $filePath);
        }

        $firstRow = true;
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if ($firstRow) { // Skip header row
                $firstRow = false;
                continue;
            }

            $product = new Product();
            $product->setName($data[1]);
            $product->setPrice((float)$data[2]);
            $product->setDescription($data[3]);
            $product->setImage($data[4] ?? null);

            $this->entityManager->persist($product);
        }

        fclose($handle);
        $this->entityManager->flush();
    }
}
