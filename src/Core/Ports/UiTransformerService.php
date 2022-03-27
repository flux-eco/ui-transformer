<?php

namespace FluxEco\UiTransformer\Core\Ports;

use FluxEco\UiTransformer\Core\{Application\CommandHandlers,
    Application\Processes,
    Domain,
    Ports\Configs\Outbounds
};

class UiTransformerService
{
    private Outbounds $outbounds;
    private Processes\TransformUserInterfaceItemsProcess $transformUserInterfaceItemsProcess;

    private function __construct(
        Outbounds $outbounds
    ) {
        $this->outbounds = $outbounds;
        $this->transformUserInterfaceItemsProcess = Processes\TransformUserInterfaceItemsProcess::new($outbounds);
    }

    public static function new(Outbounds $outbounds) : UiTransformerService
    {
        return new self(
            $outbounds
        );
    }

    public function getPages() : array
    {
        $pageListSchemaFile = $this->outbounds->getPageListSchemaFile();

        $uiPageItems = yaml_parse(file_get_contents($pageListSchemaFile));
        $command = CommandHandlers\TransformCommand::new($uiPageItems, pathinfo($pageListSchemaFile, PATHINFO_DIRNAME));
        return $this->transformUserInterfaceItemsProcess->process($command);
    }

    public function getUiPage(string $pageName) : array
    {
        $pageDefinitionFiles = $this->outbounds->getPageDefinitionFilePaths();
        $pageDefinitionFile = $pageDefinitionFiles[$pageName];
        $uiPageItems = yaml_parse(file_get_contents($pageDefinitionFile));

        $command = CommandHandlers\TransformCommand::new($uiPageItems, pathinfo($pageDefinitionFile, PATHINFO_DIRNAME));
        return $this->transformUserInterfaceItemsProcess->process($command);
    }
}
