<?php

namespace FluxEco\UiTransformer;

class Api
{
    private Core\Ports\UiTransformerService $userInterfaceService;

    private function __construct(Adapters\Outbounds $outbounds)
    {
        $this->userInterfaceService = Core\Ports\UiTransformerService::new($outbounds);
    }

    public function new(
        string $translationFileDirectory,
        string $uiDefinitionDirectory,
        string $pageListDefinitionFilePath,
        string $markdownToHtmlConverterUrl
    ) {
        $outbounds = Adapters\Outbounds::new($translationFileDirectory, $uiDefinitionDirectory,
            $pageListDefinitionFilePath, $markdownToHtmlConverterUrl);
        return new self($outbounds);
    }

    public static function newFromEnv() : self
    {
        $env = Env::new();
        $outbounds = Adapters\Outbounds::new(
            $env->getTranslationFileDirectory(),
            $env->getUiDefinitionDirectory(),
            $env->getPageListDefinitionFile(),
            $env->getMarkdownToHtmlConverterUrl(),
        );
        return new self($outbounds);
    }

    public function getPages() : array
    {
        $data = $this->userInterfaceService->getPages();
        $total = count($data);
        $result = ['data' => $data, 'success' => true, 'total' => $total];
        return $result;
    }

    public function getPageDefinition(string $projectionName) : array
    {
        return $this->userInterfaceService->getUiPage($projectionName);
    }
}