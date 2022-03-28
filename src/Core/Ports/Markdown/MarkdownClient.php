<?php

namespace FluxEco\UiTransformer\Core\Ports\Markdown;

interface MarkdownClient
{
    public function convert(string $markdown) : string;

    /**
     * @param string[] $markdowns
     *
     * @return string[]
     */
    public function convertMultiple(array $markdowns) : array;
}