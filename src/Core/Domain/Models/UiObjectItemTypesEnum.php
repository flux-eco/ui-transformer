<?php
/**/

declare(strict_types=1);

namespace FluxEco\UiTransformer\Core\Domain\Models;

class UiObjectItemTypesEnum {

    public string $typeLngKey = 'lngKey';

    private function construct() {}

    public static function new(): self
    {
        return new self();
    }
}