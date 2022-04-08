<?php

namespace FluxEco\UiTransformer\Adapters;

use FluxEco\UiTransformer\{Adapters, Adapters\Configs\UserInterfaceEnv, Core\Ports};

class Outbounds implements Ports\Configs\Outbounds
{
    private string $translationFileDirectory;
    private string $uiDefinitionDirectory;
    private string $pageListDefinitionFilePath;
    private string $markdownToHtmlConverterUrl;
    const PAGE_SCHEMA_FILE_NAME = 'page.yaml';

    private array $pageDefinitionFiles;

    private function __construct(
        string $translationFileDirectory,
        string $uiDefinitionDirectory,
        string $pageListDefinitionFilePath,
        string $markdownToHtmlConverterUrl
    ) {
        $this->translationFileDirectory = $translationFileDirectory;
        $this->uiDefinitionDirectory = $uiDefinitionDirectory;
        $this->pageListDefinitionFilePath = $pageListDefinitionFilePath;
        $this->markdownToHtmlConverterUrl = $markdownToHtmlConverterUrl;

        $this->loadPageDefinitionFilePaths();
    }

    public static function new(
        string $translationFileDirectory,
        string $uiDefinitionDirectory,
        string $pageListDefinitionFilePath,
        string $markdownToHtmlConverterUrl
    ) : self {
        return new self($translationFileDirectory, $uiDefinitionDirectory, $pageListDefinitionFilePath, $markdownToHtmlConverterUrl);
    }

    public function getTranslationFileDirectory() : string
    {
        return $this->translationFileDirectory;
    }

    public function getLanguageCode() : string
    {
        return Adapters\Actor\ActorClient::new()->getLanguageCode();
    }

    public function getPageDefinitionFilePaths() : array
    {
        return $this->pageDefinitionFiles;
    }

    private function loadPageDefinitionFilePaths() : void
    {
        $uiDefinitionDirectory = $this->uiDefinitionDirectory;

        if (file_exists($uiDefinitionDirectory) === false || is_dir($uiDefinitionDirectory) === false) {
            throw new \RuntimeException('Direcotry no found: ' . $uiDefinitionDirectory);
        }

        $this->pageDefinitionFiles = $this->getPageDefinitions($uiDefinitionDirectory);
    }

    public function getUiDefinitionDirectory() : string
    {
        return $this->uiDefinitionDirectory;
    }

    public function getPageListDefinitionFilePath() : string
    {
        return $this->pageListDefinitionFilePath;
    }

    private function getPageDefinitions(string $path) : array
    {
        $result = scandir($path);
        $directoryItems = array_diff($result, array('.', '..'));
        $pageDefinitionFiles = [];
        foreach ($directoryItems as $directoryNameItem) {
            $itemPath = $path . "/" . $directoryNameItem;
            if ($directoryNameItem === self::PAGE_SCHEMA_FILE_NAME) {
                $projectionName = yaml_parse(file_get_contents($itemPath))['projectionName'];
                $pageDefinitionFiles[$projectionName] = $itemPath;
                continue;
            }
            if (is_dir($itemPath)) {
                $pageDefinitionFiles += $this->getPageDefinitions($itemPath);
            }
        }

        return $pageDefinitionFiles;
    }

    public function getMarkdownClient() : Ports\Markdown\MarkdownClient
    {
        return Adapters\Markdown\MarkdownToHtmlConverterRestApiClient::new(
            $this->markdownToHtmlConverterUrl
        );
    }
}