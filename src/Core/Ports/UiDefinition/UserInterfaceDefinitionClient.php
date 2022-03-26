<?php

namespace FluxEco\UiTransformer\Core\Ports\UiDefinition;

interface UserInterfaceDefinitionClient
{
    public function getPageDefinitionFilePaths(): array;

    public function getPageTitleKey(string $pageName): string;

    public function getItemActions(string $pageName): array;

    public function getPageAvatar(string $pageName): string;

    public function getCreationFormDefinition(string $pageName): array;

    public function getEditFormDefinition(string $pageName): array;

    public function getTableFilterDefinition(string $pageName): array;

    public function getTableDefinition(string $pageName): array;
}