<?php
namespace FluxEco\UiTransformer\Adapters\Api;

use FluxEco\UiTransformer\Adapters\{Configs};
use FluxEco\UiTransformer\Core\{Ports};

class UserInterfaceApi
{
    private Ports\UiTransformerService $userInterfaceService;

    private function __construct(Ports\UiTransformerService $userInterfaceService)
    {
        $this->userInterfaceService = $userInterfaceService;
    }

    public static function new(): self
    {
        $userInterfaceOutbounds = Configs\Outbounds::new();
        $userInterfaceService = Ports\UiTransformerService::new($userInterfaceOutbounds);
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
        return $this->userInterfaceService->getUiPage($projectionName);
    }
}