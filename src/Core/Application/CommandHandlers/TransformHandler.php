<?php

namespace FluxEco\UiTransformer\Core\Application\CommandHandlers;
/**
 * @author martin@fluxlabs.ch
 */
interface TransformHandler
{
    /**
     * @param TransformHandler[] $nextHandlers
     */
    public function handle(TransformCommand $command, array $nextHandlers) : array;
}