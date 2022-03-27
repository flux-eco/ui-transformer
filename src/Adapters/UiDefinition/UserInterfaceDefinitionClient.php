<?php

namespace FluxEco\UiTransformer\Adapters\UiDefinition;

use FluxEco\UiTransformer\Core\Ports;

//todo split this in to an additional own api
class UserInterfaceDefinitionClient implements Ports\UiDefinition\UserInterfaceDefinitionClient
{
    //todo refactor

    const FILE_NAME_PAGE = 'Page.yaml';
    const FILE_NAME_CREATION_FORM = 'CreationForm.yaml';
    const FILE_NAME_EDIT_FIELDS = 'EditForm.yaml';
    const FILE_NAME_TABLE_COLUMNS = 'TableColumns.yaml';
    const FILE_NAME_TABLE_FILTER = 'TableFilter.yaml';

    private array $pageDefinitionDirectories;

    private function __construct(array $pageDefinitionDirectories)
    {
        $this->pageDefinitionDirectories = $pageDefinitionDirectories;
    }

    public static function new(string $uiDefinitionDirectory): self
    {
        $pageSchemaDirectoryPaths = [];
        if (file_exists($uiDefinitionDirectory) === false || is_dir($uiDefinitionDirectory) === false) {
            throw new \RuntimeException('Direcotry no found: ' . $uiDefinitionDirectory);
        }
        $result = scandir($uiDefinitionDirectory);
        $directoryItems = array_diff($result, array('.', '..'));

        if (count($directoryItems) === 0) {
            throw new \RuntimeException('No Subdirectories found:  ' . $uiDefinitionDirectory);
        }

        foreach ($directoryItems as $directoryName) {
            $path = $uiDefinitionDirectory . "/" . $directoryName;
            if (is_dir($path)) {
                $pageSchemaDirectoryPaths[$directoryName] = $path;
            }
        }
        return new self($pageSchemaDirectoryPaths);
    }

    private function getSchemaFilePath(string $schemaFileName, ?string $pageName = null): string
    {
        $this->assertPageSchemasExists($pageName);
        $pageSchemasDirectoryPath = $this->pageDefinitionDirectories[$pageName];

        $schemaFilePath = $pageSchemasDirectoryPath . '/' . $schemaFileName;

        if (is_file($schemaFilePath) === false) {
            throw new \RuntimeException('No Form Schema found in:  ' . $pageSchemasDirectoryPath . ' for Schemafilepath: ' . $schemaFilePath . '/' . $schemaFileName);
        }
        return $schemaFilePath;
    }


    public function getCreationFormDefinition(string $pageName): array
    {
        $schemaFilePath = $this->getSchemaFilePath(self::FILE_NAME_CREATION_FORM, $pageName);
        return yaml_parse(file_get_contents($schemaFilePath));
    }

    public function getEditFormDefinition(string $pageName): array
    {
        $schemaFilePath = $this->getSchemaFilePath(self::FILE_NAME_EDIT_FIELDS, $pageName);
        return yaml_parse(file_get_contents($schemaFilePath));
    }

    public function getTableFilterDefinition(string $pageName): array
    {
        $schemaFilePath = $this->getSchemaFilePath(self::FILE_NAME_TABLE_FILTER, $pageName);
        return yaml_parse(file_get_contents($schemaFilePath));
    }

    public function getTableDefinition(string $pageName): array
    {
        $schemaFilePath = $this->getSchemaFilePath(self::FILE_NAME_TABLE_COLUMNS, $pageName);
        return yaml_parse(file_get_contents($schemaFilePath));
    }

    private function assertPageSchemasExists(string $pageName)
    {
        if (array_key_exists($pageName, $this->pageDefinitionDirectories) === false) {
            throw new \RuntimeException('No Schemas found for page:  ' . $pageName);
        }
    }

    public function getPageTitleKey(string $pageName): string
    {
        $schemaFilePath = $this->getSchemaFilePath(self::FILE_NAME_PAGE, $pageName);
        $tablePageDefinition = yaml_parse(file_get_contents($schemaFilePath));
        //ToDo replace param by const / config
        return $tablePageDefinition['titleKey'];
    }

    public function getItemActions(string $pageName): array
    {
        $schemaFilePath = $this->getSchemaFilePath(self::FILE_NAME_PAGE, $pageName);
        $pageDefinition = yaml_parse(file_get_contents($schemaFilePath));
        //ToDo replace param by const / config
        return $pageDefinition['itemActions'];
    }

    public function getPageDefinitionFilePaths(): array
    {
        $pageDefinitionPaths = $this->pageDefinitionDirectories;

        $pageDefinitionFilePaths = [];
        foreach ($pageDefinitionPaths as $pageDefinitionPath) {
            $pageDefinitionFilePath = $pageDefinitionPath . '/' . self::FILE_NAME_PAGE;
            if (file_exists($pageDefinitionFilePath) === true) {
                $pageDefinitionFilePaths[] = $pageDefinitionFilePath;
            }
        }
        return $pageDefinitionFilePaths;
    }

    public function getPageAvatar(string $pageName): string
    {
        $schemaFilePath = $this->getSchemaFilePath(self::FILE_NAME_PAGE, $pageName);
        $tablePageDefinition = yaml_parse(file_get_contents($schemaFilePath));
        //ToDo replace param by const / config
        return $tablePageDefinition['avatar'];
    }
}