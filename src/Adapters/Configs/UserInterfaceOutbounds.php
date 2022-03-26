<?php

namespace FluxEco\UiTransformer\Adapters\Configs;

use FluxEco\UiTransformer\{Adapters, Core\Ports};

class UserInterfaceOutbounds implements Ports\Configs\UserInterfaceOutbounds
{
    private function __construct()
    {
    }

    public static function new(): self
    {
        return new self();
    }

    public function getLanguageHandlerClient(string $languageKey): Ports\LanguageHandler\LanguageHandlerClient
    {
        $languageFilesDirectoryPath = getenv(UserInterfaceEnv::TRANSLATION_FILES_DIRECTORY);
        return Adapters\LanguageHandler\LanguageHandlerClient::new($languageFilesDirectoryPath, $languageKey);
    }

    public function getUserInterfaceDefinitionClient(): Ports\UiDefinition\UserInterfaceDefinitionClient
    {
        $uiSchemaDirectoryPath = getenv(UserInterfaceEnv::UI_DEFINITIONS_DIRECTORY);
        return Adapters\UiDefinition\UserInterfaceDefinitionClient::new($uiSchemaDirectoryPath);
    }
}