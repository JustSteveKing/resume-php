<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Services;

use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

final class Translator
{
    private static ?self $instance = null;
    private SymfonyTranslator $translator;

    private function __construct(string $locale = 'en')
    {
        $this->translator = new SymfonyTranslator($locale);
        $this->translator->addLoader('php', new PhpFileLoader());
        
        // Load default translations
        $langDir = __DIR__ . '/../../resources/lang';
        if (is_dir($langDir)) {
            $files = glob($langDir . '/*.php');
            if (is_array($files)) {
                foreach ($files as $file) {
                    $fileLocale = basename($file, '.php');
                    $this->translator->addResource('php', $file, $fileLocale);
                }
            }
        }
    }

    public static function getInstance(string $locale = 'en'): self
    {
        if (self::$instance === null) {
            self::$instance = new self($locale);
        }

        return self::$instance;
    }

    public function setLocale(string $locale): void
    {
        $this->translator->setLocale($locale);
    }

    public function getLocale(): string
    {
        return $this->translator->getLocale();
    }

    /**
     * @param string $id
     * @param array<string, string> $parameters
     * @param string|null $domain
     * @param string|null $locale
     * @return string
     */
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
