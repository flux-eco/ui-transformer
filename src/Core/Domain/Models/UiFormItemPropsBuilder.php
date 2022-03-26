<?php
/**/

declare(strict_types=1);

namespace FluxEco\UiTransformer\Core\Domain\Models;

class UiFormItemPropsBuilder {

    private array $rules = [];


    private function __construct() { }

    public static function create(): self {
        return new self();
    }

    public function withRequiredRule(string $message): self {
        $rule['required'] = true;
        $rule['message'] = $message;

        $this->rules[] = $rule;
        return $this;
    }

    public function getRules(): array {
        return $this->rules;
    }

    public function build(): array {
        return $this->rules;
    }
}