<?php
/**/

declare(strict_types=1);

namespace FluxEco\UiTransformer\Core\Domain\Models;

class UiTable implements \JsonSerializable
{
    private array $columns;
    private int $total;
    private bool $success;

    private function __construct(
        array $columns,
        int   $total,
        bool  $success
    )
    {
        $this->columns = $columns;
        $this->total = $total;
        $this->success = $success;
    }

    public static function new(
        UiFormColumns $columns
    ): self
    {
        $total = \count($columns->jsonSerialize());
        $success = true;

        return new self(
            $columns->jsonSerialize(),
            $total,
            $success
        );
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }


    public function jsonSerialize(): array
    {
        return [
            'columns' => $this->columns,
            'total' => $this->total,
            'success' => $this->success
        ];
    }
}