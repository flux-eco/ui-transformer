<?php

namespace FluxEco\UiTransformer\Core\Application\CommandHandlers;

use FluxEco\UiTransformer\Core\Ports;

class TransformMarkdownHandler implements TransformHandler
{
    private function __construct(private Ports\Markdown\MarkdownClient $markdown_client) {

    }

    public static function new(Ports\Configs\Outbounds $outbounds) : static {
        return new static(
            $outbounds->getMarkdownClient()
        );
    }

    public function handle(TransformCommand $command, array $nextHandlers) : array
    {
        $item = $command->getUiItem();
        $markdowns = array_map(fn(array $item) : string => $item["TODO: Which key?"], $command->getUiItem());

        $htmls = $this->markdown_client->convertMultiple(
            $markdowns
        );

        $new_ui_item = $command->getUiItem();
        foreach ($htmls as $key => $html) {
            $new_ui_item[$key]["TODO: Which key?"] = $html;
        }

        $nextHandler = array_shift($nextHandlers);

        return $nextHandler->handle(
            TransformCommand::new(
                $new_ui_item,
                $command->getSchemaFileDirectoryPath()
            ),
            $nextHandlers
        );
    }
}