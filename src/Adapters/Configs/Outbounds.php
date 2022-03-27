<?php

namespace FluxEco\UiTransformer\Adapters\Configs;

use FluxEco\UiTransformer\{Adapters, Core\Ports};

class Outbounds implements Ports\Configs\Outbounds
{
    const PAGE_SCHEMA_FILE_NAME = 'page.yaml';
    const PAGE_LIST_SCHEMA_FILE_NAME = 'pages.yaml';

    private array $pageSchemaFiles;

    private function __construct()
    {
        $this->loadPageDefinitionFilePaths();
    }

    public static function new() : self
    {
        return new self();
    }

    public function getTranslationFileDirectory() : string
    {
        return getenv(UserInterfaceEnv::TRANSLATION_FILES_DIRECTORY);
    }

    public function getLanguageCode() : string
    {
        return Adapters\Actor\ActorClient::new()->getLanguageCode();
    }

    public function getPageDefinitionFilePaths() : array
    {
        return $this->pageSchemaFiles;
    }

    private function loadPageDefinitionFilePaths() : void
    {
        $uiDefinitionDirectory = getenv(UserInterfaceEnv::UI_DEFINITIONS_DIRECTORY);

        if (file_exists($uiDefinitionDirectory) === false || is_dir($uiDefinitionDirectory) === false) {
            throw new \RuntimeException('Direcotry no found: ' . $uiDefinitionDirectory);
        }

        $this->pageSchemaFiles = $this->getPageSchemas($uiDefinitionDirectory);
    }

    public function getPageListSchemaFile()
    {
        return getenv(UserInterfaceEnv::UI_DEFINITIONS_DIRECTORY) . "/" . self::PAGE_LIST_SCHEMA_FILE_NAME;
    }

    private function getPageSchemas(string $path)
    {
        $result = scandir($path);
        $directoryItems = array_diff($result, array('.', '..'));
        $pageSchemaFiles = [];
        foreach ($directoryItems as $directoryNameItem) {
            $itemPath = $path . "/" . $directoryNameItem;
            if ($directoryNameItem === self::PAGE_SCHEMA_FILE_NAME) {
                $projectionName = yaml_parse(file_get_contents($itemPath))['projectionName'];
                $pageSchemaFiles[$projectionName] = $itemPath;
                continue;
            }
            if (is_dir($itemPath)) {
                $pageSchemaFiles += $this->getPageSchemas($itemPath);
            }
        }

        return $pageSchemaFiles;
    }
}