<?php

namespace FluxEco\UiTransformer\Core\Ports\LanguageHandler;

interface LanguageHandlerClient
{
    public function getTranslatedString(string $languageKey): string;
}