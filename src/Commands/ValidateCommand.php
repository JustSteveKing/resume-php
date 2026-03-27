<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Commands;

use JustSteveKing\Resume\Factories\ResumeFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'validate',
    description: 'Validate a résumé file against the official schema.',
)]
final class ValidateCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The path to the résumé file (JSON or YAML)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var string $filePath */
        $filePath = $input->getArgument('file');

        if ( ! file_exists($filePath)) {
            $io->error("File not found: {$filePath}");
            return Command::FAILURE;
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $content = file_get_contents($filePath);

        if (false === $content) {
            $io->error("Failed to read file: {$filePath}");
            return Command::FAILURE;
        }

        try {
            $resume = match ($extension) {
                'json' => ResumeFactory::fromJson($content),
                'yaml', 'yml' => ResumeFactory::fromYaml($content),
                default => throw new \InvalidArgumentException("Unsupported file extension: {$extension}"),
            };

            $resume->validate();
            $io->success('The résumé is valid!');
            
            return Command::SUCCESS;
        } catch (Throwable $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
