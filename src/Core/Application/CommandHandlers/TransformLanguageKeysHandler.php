<?php

namespace FluxEco\UiTransformer\Core\Application\CommandHandlers;

use FluxEco\UiTransformer\Core\{Domain\Models, Application\Processes, Ports};

class TransformLanguageKeysHandler implements TransformHandler
{
    const YAML_FILE_SUFFIX = 'yaml';
    private Ports\Configs\Outbounds $outbounds;
    private array $translations;

    private function __construct(array $translations, Ports\Configs\Outbounds $outbounds)
    {
        $this->translations = $translations;
        $this->outbounds = $outbounds;
    }

    public static function new(
        Ports\Configs\Outbounds $outbounds
    ) {
        $translationFilesDirectoryPath = $outbounds->getTranslationFileDirectory();
        $languageCode  = $outbounds->getLanguageCode();

        $languageFileName = $languageCode . '.' . self::YAML_FILE_SUFFIX;
        self::assertTranslationFileExists($translationFilesDirectoryPath, $languageFileName);
        $languageFilePath = $translationFilesDirectoryPath . '/' . $languageFileName;
        return new self(yaml_parse(file_get_contents($languageFilePath)), $outbounds);
    }

    public function handle(TransformCommand $command, array $nextHandlers) : array
    {
        $transformedItem = $command->getUiItem();

        foreach ($command->getUiItem() as $key => $value) {
            if (is_array($value)) {
                if (key_exists('type', $value) &&
                    $value['type'] == Models\UiObjectItemTypesEnum::new()->typeLngKey) {
                    $lngKey = $value[Models\UiItemKeysEnum::new()->lngKey];
                    $transformedItem[$key] = $this->translate($lngKey);
                } else {
                    $processCommand = TransformCommand::new($value,
                        pathinfo($command->getSchemaFileDirectoryPath(), PATHINFO_DIRNAME));
                    $transformUserInterfaceItemsProcess = Processes\TransformUserInterfaceItemsProcess::new($this->outbounds);
                    $transformedItem[$key] = $transformUserInterfaceItemsProcess->process($processCommand);
                }
            }
        }

        return $this->process(TransformCommand::new($transformedItem, $command->getSchemaFileDirectoryPath()),
            $nextHandlers);
    }

    /**
     * @param TransformHandler[] $nextHandlers
     */
    public function process(TransformCommand $command, array $nextHandlers) : array
    {
        $nextHandler = $nextHandlers[0];

        unset($nextHandlers[0]); // remove item at index 0
        $nextHandlers = array_values($nextHandlers); // 'reindex' array

        return $nextHandler->handle($command, $nextHandlers);
    }

    final public function translate(string $languageKey) : string
    {
        $this->assertTranslationExists($languageKey);
        $keyParts = explode('.', $languageKey);
        $translations = $this->translations;

        $translationsPath = $translations;
        foreach ($keyParts as $keyPart) {
            $translationsPath = $translationsPath[$keyPart];
        }
        $translatedString = $translationsPath;
        return $translatedString;
    }

    private function assertTranslationExists(string $key) : void
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

    private static function assertTranslationFileExists(
        string $languageFilesDirectoryPath,
        string $languageFileName
    ) : void {
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
}