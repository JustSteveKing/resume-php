<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Commands;

use InvalidArgumentException;
use JustSteveKing\Resume\Exporters\JsonLdExporter;
use JustSteveKing\Resume\Exporters\MarkdownExporter;
use JustSteveKing\Resume\Exporters\YamlExporter;
use JustSteveKing\Resume\Factories\ResumeFactory;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'export',
    description: 'Export a résumé to a specific format.',
)]
final class ExportCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'The path to the source résumé file (JSON or YAML)')
            ->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'The output format (markdown, yaml, json-ld)', 'markdown')
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'The output file path')
            ->addOption('locale', 'l', InputOption::VALUE_REQUIRED, 'The locale for translation (only for markdown)', 'en');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var string $filePath */
        $filePath = $input->getArgument('file');
        /** @var string $format */
        $format = $input->getOption('format');
        /** @var string|null $outputPath */
        $outputPath = $input->getOption('output');
        /** @var string $locale */
        $locale = $input->getOption('locale');

        if ( ! file_exists($filePath)) {
            $io->error("Source file not found: {$filePath}");
            return Command::FAILURE;
        }

        try {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $content = file_get_contents($filePath);

            if (false === $content) {
                throw new RuntimeException("Failed to read file: {$filePath}");
            }

            $resume = match ($extension) {
                'json' => ResumeFactory::fromJson($content),
                'yaml', 'yml' => ResumeFactory::fromYaml($content),
                default => throw new InvalidArgumentException("Unsupported source file extension: {$extension}"),
            };

            $exporter = match (mb_strtolower($format)) {
                'markdown', 'md' => new MarkdownExporter(locale: $locale),
                'yaml', 'yml' => new YamlExporter(),
                'json-ld', 'jsonld' => new JsonLdExporter(),
                default => throw new InvalidArgumentException("Unsupported output format: {$format}"),
            };

            $result = $exporter->export($resume);

            if (is_array($result)) {
                $encoded = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (false === $encoded) {
                    throw new RuntimeException('Failed to encode result to JSON');
                }
                $result = $encoded;
            }

            if ($outputPath) {
                file_put_contents($outputPath, $result);
                $io->success("Résumé exported to: {$outputPath}");
            } else {
                $output->writeln($result);
            }

            return Command::SUCCESS;
        } catch (Throwable $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
