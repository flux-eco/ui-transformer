<?php

namespace fluxUiTransformer;

use FluxEco\UiTransformer;

function getPageDefinition(string $projectionName) : array
{
    return UiTransformer\Api::newFromEnv()->getPageDefinition($projectionName);
}