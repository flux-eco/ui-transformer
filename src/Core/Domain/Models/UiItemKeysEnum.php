<?php
/**/

declare(strict_types=1);

namespace FluxEco\UiTransformer\Core\Domain\Models;

class UiItemKeysEnum {

    public string $lngKey = 'key';
    public string $refKey = '$ref';


    private function construct() {}

    public static function new(): self
    {
        return new self();
    }
}