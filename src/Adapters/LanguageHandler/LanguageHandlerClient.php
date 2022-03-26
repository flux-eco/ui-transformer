<?php

namespace FluxEco\UiTransformer\Adapters\LanguageHandler;

use FluxEco\UiTransformer\Core\Ports;

class LanguageHandlerClient implements Ports\LanguageHandler\LanguageHandlerClient
{
    const FILE_SUFFIX = 'yaml';
    protected static array $instances = [];
    private string $languageKey;
    private array $translations;

    private function __construct(string $languageKey, array $translations)
    {
        $this->languageKey = $languageKey;
        $this->translations = $translations;
    }

    public static function new(string $languageFilesDirectoryPath, string $languageKey): self
    {
        if (array_key_exists($languageKey, self::$instances)) {
            return self::$instances[$languageKey];
        }

        $languageFileName = $languageKey . '.' . self::FILE_SUFFIX;
        self::assertTranslationFileExists($languageFilesDirectoryPath, $languageFileName);
        $languageFilePath = $languageFilesDirectoryPath . '/' . $languageFileName;
        $translations = yaml_parse(file_get_contents($languageFilePath));

        self::$instances[$languageKey] = new self($languageKey, $translations);
        return self::$instances[$languageKey];
    }

    final public function getLanguageKey(): string
    {
        return $this->languageKey;
    }

    private function assertTranslationExists(string $key): void
    {
        $translations = $this->translations;
        $keyParts = explode('.', $key);

        $translationsPath = $translations;
        foreach ($keyParts as $keyPart) {
            if (array_key_exists($keyPart, $translationsPath) === false) {
                throw new \RuntimeException('No Translation found for ' . $key . ' in ' . $this->getLanguageKey() . ' translation file');
            }
            $translationsPath = $translationsPath[$keyPart];
        }
    }

    private static function assertTranslationFileExists(string $languageFilesDirectoryPath, string $languageFileName): void
    {
        if (file_exists($languageFilesDirectoryPath) === false || is_dir($languageFilesDirectoryPath) === false) {
            throw new \RuntimeException('Direcotry no found: ' . $languageFilesDirectoryPath);
        }
        $result = scandir($languageFilesDirectoryPath);
        $directoryItems = array_diff($result, array('.', '..'));

        if (count($directoryItems) === 0) {
            throw new \RuntimeException('No Translations files found:  ' . $languageFilesDirectoryPath);
        }

        foreach ($directoryItems as $fileName) {
            if ($fileName === $languageFileName) {
                return;
            }
        }

        throw new \RuntimeException('Language File not found:  ' . $languageFileName . ' in ' . $languageFilesDirectoryPath);
    }

    final public function getTranslatedString(string $languageKey): string
    {
        $this->assertTranslationExists($languageKey);
        $keyParts = explode('.', $languageKey);
        $translations = $this->translations;

        $translationsPath = $translations;
        foreach ($keyParts as $keyPart) {
            $translationsPath = $translationsPath[$keyPart];
        }

        return $translationsPath;
    }
}