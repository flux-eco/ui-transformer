<?php

namespace FluxEco\UiTransformer\Core\Application\Processes;

use FluxEco\UiTransformer\Core\{
    Ports,
    Application\CommandHandlers
};

class TransformUserInterfaceItemsProcess implements CommandHandlers\TransformHandler
{

    /**
     * @var CommandHandlers\TransformHandler[]
     */
    private array $handlerQueue;

    private function __construct(Ports\Configs\Outbounds $outbounds)
    {
        $this->handlerQueue = [
            CommandHandlers\TransformReferencesHandler::new(
                $outbounds
            ),
            CommandHandlers\TransformLanguageKeysHandler::new(
                $outbounds
            ),
            $this
        ];
    }

    public static function new(Ports\Configs\Outbounds $outbounds) : self
    {
        return new self($outbounds);
    }

    public function process(CommandHandlers\TransformCommand $command) : array
    {
        $nextHandler = $this->handlerQueue[0];
        unset($this->handlerQueue[0]); // remove item at index 0
        $nextHandlers = array_values($this->handlerQueue); // 'reindex' array

        return $nextHandler->handle($command, $nextHandlers);
    }

    public function handle(CommandHandlers\TransformCommand $command, array $nextHandlers) : array
    {
        return $command->getUiItem();
    }
}