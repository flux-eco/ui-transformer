<?php

namespace FluxEco\UiTransformer\Core\Ports\Configs;

use FluxEco\UiTransformer\Core\Ports;

interface Outbounds
{
    public function getTranslationFileDirectory() : string;

    public function getLanguageCode() : string;

    public function getPageDefinitionFilePaths() : array;
}