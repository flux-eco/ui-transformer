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
            //CommandHandlers\TransformMarkdownHandler::new(
            //    $outbounds
            //),
            $this
        ];
    }

    public static function new(Ports\Configs\Outbounds $outbounds) : self
    {
        return new self($outbounds);
    }

    public function process(CommandHandlers\TransformCommand $command) : array
    {
        $nextHandler = array_shift($this->handlerQueue);
        return $nextHandler->handle($command, $this->handlerQueue);
    }

    public function handle(CommandHandlers\TransformCommand $command, array $nextHandlers) : array
    {
        return $command->getUiItem();
    }
}