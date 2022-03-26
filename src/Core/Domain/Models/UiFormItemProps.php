<?php
/**/

declare(strict_types=1);

namespace FluxEco\UiTransformer\Core\Domain\Models;

use JsonSerializable;

class UiFormItemProps implements JsonSerializable
{

    private array $rules = [];

    private function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public static function newFromBuilder(UiFormItemPropsBuilder $builder): self
    {
        $rules = $builder->getRules();
        return new self($rules);
    }

    final public function getRules(): array
    {
        return $this->rules;
    }

    final public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}