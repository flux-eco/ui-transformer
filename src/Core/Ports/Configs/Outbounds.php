<?php

namespace FluxEco\UiTransformer\Core\Ports\Configs;

use FluxEco\UiTransformer\Core\Ports;

interface Outbounds
{
    public function getTranslationFileDirectory() : string;

    public function getLanguageCode() : string;

    public function getPageListDefinitionFilePath() : string;

    public function getPageDefinitionFilePaths() : array;

    public function getMarkdownClient() : Ports\Markdown\MarkdownClient;
}