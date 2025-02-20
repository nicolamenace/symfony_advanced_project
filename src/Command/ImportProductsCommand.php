<?php

namespace App\Command;

use App\Service\CsvImporterService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-products',
    description: 'Import products from a CSV file',
)]
class ImportProductsCommand extends Command
{
    private CsvImporterService $csvImporterService;

    public function __construct(CsvImporterService $csvImporterService)
    {
        parent::__construct();
        $this->csvImporterService = $csvImporterService;
    }

    protected function configure()
    {
        $this
            ->addArgument('filePath', InputArgument::REQUIRED, 'Path to the CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('filePath');

        try {
            $this->csvImporterService->importProducts($filePath);
            $output->writeln('<info>Produits importés avec succès!</info>');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Erreur: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
