<?php
namespace FluxEco\UiTransformer;


class Env
{
    const UI_TRANSFORM_TRANSLATION_FILES_DIRECTORY = 'UI_TRANSFORM_TRANSLATION_FILES_DIRECTORY';
    const UI_TRANSFORM_UI_DEFINITION_DIRECTORY = 'UI_TRANSFORM_UI_DEFINITION_DIRECTORY';
    const UI_TRANSFORM_PAGE_LIST_DEFINITION_FILE_PATH = 'UI_TRANSFORM_PAGE_LIST_DEFINITION_FILE_PATH';
    const UI_TRANSFORM_MARKDOWN_TO_HTML_CONVERTER_REST_API_URL = 'UI_TRANSFORM_MARKDOWN_TO_HTML_CONVERTER_REST_API_URL';

    private function __construct()
    {

    }

    public static function new()
    {
        return new self();
    }

    public function getTranslationFileDirectory() : string
    {
        return getenv(self::UI_TRANSFORM_TRANSLATION_FILES_DIRECTORY);
    }

    public function getUiDefinitionDirectory() : string
    {
        return getenv(self::UI_TRANSFORM_UI_DEFINITION_DIRECTORY);
    }

    public function getPageListDefinitionFile() : string
    {
        return getenv(self::UI_TRANSFORM_PAGE_LIST_DEFINITION_FILE_PATH);
    }

    public function getMarkdownToHtmlConverterUrl() : string
    {
        return getenv(self::UI_TRANSFORM_MARKDOWN_TO_HTML_CONVERTER_REST_API_URL);
    }
}