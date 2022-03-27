<?php

namespace FluxEco\UiTransformer\Core\Application\CommandHandlers;

use FluxEco\UiTransformer\Core\{Domain\Models, Application\Processes, Ports};

class TransformReferencesHandler implements TransformHandler
{

    private Ports\Configs\Outbounds $outbounds;

    private function __construct(Ports\Configs\Outbounds $outbounds)
    {
        $this->outbounds = $outbounds;
    }

    public static function new(Ports\Configs\Outbounds $outbounds)
    {
        return new self($outbounds);
    }

    public function handle(TransformCommand $command, array $nextHandlers) : array
    {
        $transformedItem = $command->getUiItem();
        print_r($command->getUiItem());
        foreach ($command->getUiItem() as $key => $value) {
            if (is_array($value) && key_exists(Models\UiItemKeysEnum::new()->refKey, $value)) {
                $filePath = $command->getSchemaFileDirectoryPath() . '/' . $value[Models\UiItemKeysEnum::new()->refKey];
                $items = yaml_parse(file_get_contents($filePath));
                $processCommand = TransformCommand::new($items, pathinfo($filePath, PATHINFO_DIRNAME));
                $transformUserInterfaceItemsProcess = Processes\TransformUserInterfaceItemsProcess::new($this->outbounds);

                $transformedItem[$key] = $transformUserInterfaceItemsProcess->process($processCommand);
            }
        }

        return $this->process(TransformCommand::new($transformedItem, $command->getSchemaFileDirectoryPath()),
            $nextHandlers);
    }

    /**
     * @param TransformHandler[] $nextHandlers
     */
    public function process(TransformCommand $command, array $nextHandlers) : array
    {
        $nextHandler = $nextHandlers[0];

        unset($nextHandlers[0]); // remove item at index 0
        $nextHandlers = array_values($nextHandlers); // 'reindex' array

        return $nextHandler->handle($command, $nextHandlers);
    }

}