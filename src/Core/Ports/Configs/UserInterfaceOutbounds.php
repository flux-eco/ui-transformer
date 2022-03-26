<?php

namespace FluxEco\UiTransformer\Core\Ports\Configs;

use FluxEco\UiTransformer\Core\Ports;

interface UserInterfaceOutbounds
{
    public function getLanguageHandlerClient(string $languageKey): Ports\LanguageHandler\LanguageHandlerClient;

    public function getUserInterfaceDefinitionClient(): Ports\UiDefinition\UserInterfaceDefinitionClient;
}