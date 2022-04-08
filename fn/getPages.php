<?php

namespace fluxUiTransformer;

use FluxEco\UiTransformer;

function getPages() : array
{
    return UiTransformer\Api::newFromEnv()->getPages();
}