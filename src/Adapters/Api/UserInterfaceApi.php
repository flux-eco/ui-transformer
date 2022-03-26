<?php
namespace FluxEco\UiTransformer\Adapters\Api;

use FluxEco\UiTransformer\Adapters\{Configs};
use FluxEco\UiTransformer\Core\{Ports};

class UserInterfaceApi
{
    private Ports\UserInterfaceService $userInterfaceService;

    private function __construct(Ports\UserInterfaceService $userInterfaceService)
    {
        $this->userInterfaceService = $userInterfaceService;
    }

    public static function new(): self
    {
        $userInterfaceOutbounds = Configs\UserInterfaceOutbounds::new();
        $userInterfaceService = Ports\UserInterfaceService::new($userInterfaceOutbounds);
        return new self($userInterfaceService);
    }

    public function getPages(): array
    {
        $data = $this->userInterfaceService->getPages();
        $total = count($data);
        $result = ['data' => $data, 'status' => 'success', 'total' => $total];
        return $result;
    }


    public function getPageDefinition(string $projectionName): array
    {
        $uiPage = $this->userInterfaceService->getUiPage($projectionName);
        return $uiPage->toArray();
    }


    public function getTablePageDefinition(string $projectionName): array
    {
        $uiTablePage = $this->userInterfaceService->getUiTablePage($projectionName);
        return $uiTablePage->toArray();
    }
}