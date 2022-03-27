<?php

namespace FluxEco\UiTransformer\Core\Application\CommandHandlers;

class TransformCommand
{

    private array $uiItems;
    private string $schemaFileDirectoryPath;

    private function __construct(array $uiItems, string $schemaFileDirectoryPath)
    {
        $this->uiItems = $uiItems;
        $this->schemaFileDirectoryPath = $schemaFileDirectoryPath;
    }

    public static function new(array $uiItems, string $schemaFileDirectoryPath)
    {
        return new self($uiItems, $schemaFileDirectoryPath);
    }

    public function getUiItem() : array
    {
        return $this->uiItems;
    }

    public function getSchemaFileDirectoryPath() : string
    {
        return $this->schemaFileDirectoryPath;
    }
}