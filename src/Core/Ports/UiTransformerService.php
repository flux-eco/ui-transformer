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
        print_r($uiPageItems);
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

        /*
        $languageHandlerClient = $this->languageHandlerClient;
        $userInterfaceClient = $this->userInterfaceSchemaClient;

        $pageTitleKey = $userInterfaceClient->getPageTitleKey($pageName);
        $pageTitle = $languageHandlerClient->getTranslatedString($pageTitleKey);
        $avatar = $userInterfaceClient->getPageAvatar($pageName);
        $itemActions = $userInterfaceClient->getItemActions($pageName);

        $formCreate = $userInterfaceClient->getCreationFormDefinition($pageName);

        $formItemsCreate = $formCreate['properties'];
        foreach ($formItemsCreate as $key => $formItem) {
            if (array_key_exists('titleKey', $formItem)) {
                $formItem['title'] = $languageHandlerClient->getTranslatedString($formItem['titleKey']);
            }
            $formItemsCreate[$key] = $formItem;
        }
        $formCreate['properties'] = $formItemsCreate;

        echo "********** formCreate *******" . PHP_EOL;
        print_r($formCreate);
        echo PHP_EOL;

        $formItemsEdit = $userInterfaceClient->getEditFormDefinition($pageName);

        return Domain\Models\UiPage::new(
            $pageTitle,
            $avatar,
            $userInterfaceClient->getProjectionName($pageName),
            $formCreate,
            $formItemsEdit,
            $itemActions
        );*/
    }

    /*
    public function getUiTablePage(string $pageName) : Domain\Models\UiTablePage
    {
        $languageHandlerClient = $this->languageHandlerClient;
        $userInterfaceClient = $this->userInterfaceSchemaClient;

        $pageTitleKey = $userInterfaceClient->getPageTitleKey($pageName);
        $pageTitle = $languageHandlerClient->getTranslatedString($pageTitleKey);

        $formItemsCreate = $userInterfaceClient->getCreationFormDefinition($pageName);
        $formItemsEdit = $userInterfaceClient->getEditFormDefinition($pageName);
        $tableFilter = $userInterfaceClient->getTableFilterDefinition($pageName);

        $tableColumns = $userInterfaceClient->getTableDefinition($pageName);
        foreach ($tableColumns as $key => $value) {
            //$tableColumns[$key] = $this->translateLangKey($value, 'titleKey', 'title');
        }

        return Domain\Models\UiTablePage::new(
            $pageTitle,
            $formItemsCreate,
            $formItemsEdit,
            $tableFilter,
            $tableColumns
        );
    }
    */

}
