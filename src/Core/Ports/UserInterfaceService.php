<?php

namespace FluxEco\UiTransformer\Core\Ports;

use FluxEco\UiTransformer\Core\{Domain};

class UserInterfaceService
{
    private LanguageHandler\LanguageHandlerClient $languageHandlerClient;
    private UiDefinition\UserInterfaceDefinitionClient $userInterfaceSchemaClient;


    private function __construct(
        LanguageHandler\LanguageHandlerClient      $languageHandlerClient,
        UiDefinition\UserInterfaceDefinitionClient $userInterfaceSchemaClient
    )
    {
        $this->languageHandlerClient = $languageHandlerClient;
        $this->userInterfaceSchemaClient = $userInterfaceSchemaClient;
    }

    //todo parameter userSessionKey
    public static function new(Configs\UserInterfaceOutbounds $userInterfaceOutbounds)
    {
        //TODO
        $languageKey = 'de';
        $languageHandlerClient = $userInterfaceOutbounds->getLanguageHandlerClient($languageKey);
        $userInterfaceSchemaClient = $userInterfaceOutbounds->getUserInterfaceDefinitionClient();
        return new self(
            $languageHandlerClient,
            $userInterfaceSchemaClient
        );
    }


    public function getPages(): array
    {
        $userInterfaceClient = $this->userInterfaceSchemaClient;
        $pageDefinitionFiles = $this->userInterfaceSchemaClient->getPageDefinitionFilePaths();

        $pages = [];
        foreach ($pageDefinitionFiles as $pageDefinitionFile) {
            $data = yaml_parse(file_get_contents($pageDefinitionFile));
            $data['title'] = $this->languageHandlerClient->getTranslatedString($data['titleKey']);
            $pages[] = $data;
        }
        return $pages;
    }

    public function getUiPage(string $pageName): Domain\Models\UiPage
    {
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

        echo "********** formCreate *******".PHP_EOL;
        print_r($formCreate);
        echo PHP_EOL;

        $formItemsEdit = $userInterfaceClient->getEditFormDefinition($pageName);

        return Domain\Models\UiPage::new(
            $pageTitle,
            $avatar,
            $formCreate,
            $formItemsEdit,
            $itemActions
        );
    }

    public function getUiTablePage(string $pageName): Domain\Models\UiTablePage
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
            $tableColumns[$key] = $this->translateLangKey($value, 'titleKey', 'title');
        }

        return Domain\Models\UiTablePage::new(
            $pageTitle,
            $formItemsCreate,
            $formItemsEdit,
            $tableFilter,
            $tableColumns
        );
    }

    private function translateLangKey(array $item, string $langKey, string $itemKey): array
    {
        $languageHandlerClient = $this->languageHandlerClient;
        $item[$itemKey] = $languageHandlerClient->getTranslatedString($item[$langKey]);
        return $item;
    }

}
