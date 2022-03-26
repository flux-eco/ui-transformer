<?php
/**/

declare(strict_types=1);

namespace FluxEco\UiTransformer\Core\Domain\Models;


class UiFormColumns implements \JsonSerializable
{
    private array $columns = [];

    private function __construct() { }

    public static function new(
    ): self
    {
        return new self();
    }

    public function append(
        string          $title,
        string          $dataIndex,
        string          $key,
        string          $valueType,
        UiFormItemProps $formItemProps,
        bool            $required = false,
        string          $width = 'm'
    ) {
        $column = get_defined_vars();
        $column['formItemProps'] = $formItemProps->jsonSerialize();
        $this->columns[] = $column;
    }


    public function jsonSerialize(): array
    {
        return $this->columns;
    }
}