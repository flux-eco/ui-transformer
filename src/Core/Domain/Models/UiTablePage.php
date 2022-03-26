<?php
/**/

declare(strict_types=1);

namespace FluxEco\UiTransformer\Core\Domain\Models;


class UiTablePage implements \JsonSerializable
{

    private string $title;
    private array $formCreate;
    private array $formEdit;
    private array $tableFilter;
    private array $table;

    private function __construct(
        string            $title,
        array             $formItemsCreate,
        array             $formItemsEdit,
        array             $tableFilter,
        array             $table
    )
    {
        //todo assertions

        $this->title = $title;
        $this->formCreate = $formItemsCreate;
        $this->formEdit = $formItemsEdit;
        $this->tableFilter = $tableFilter;
        $this->table = $table;

    }

    public static function new(
        string    $title,
        array     $formItemsCreate,
        array     $formItemsEdit,
        array     $tableFilter,
        array     $table
    ): self
    {
        return new self(
            $title,
            $formItemsCreate,
            $formItemsEdit,
            $tableFilter,
            $table
        );
    }

    final public function getFormCreate(): array
    {
        return $this->formCreate;
    }

    final public function getFormEdit(): array
    {
        return $this->formEdit;
    }

    public function getTableFilter(): array
    {
        return $this->tableFilter;
    }

    public function getTable(): array
    {
        return $this->table;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }


    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}