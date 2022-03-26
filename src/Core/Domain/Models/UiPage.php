<?php
/**/

declare(strict_types=1);

namespace FluxEco\UiTransformer\Core\Domain\Models;


class UiPage implements \JsonSerializable
{

    private string $title;
    private string $avatar;
    private array $formCreate;
    private array $formEdit;
    private array $itemActions;

    private function __construct(
        string $title,
        string $avatar,
        array  $formCreate,
        array  $formItemsEdit,
        array  $itemActions,
    )
    {
        //todo assertions

        $this->title = $title;
        $this->avatar = $avatar;
        $this->formCreate = $formCreate;
        $this->formEdit = $formItemsEdit;
        $this->itemActions = $itemActions;
    }

    public static function new(
        string $title,
        string $avatar,
        array  $formCreate,
        array  $formItemsEdit,
        array  $itemActions,
    ): self
    {
        return new self(
            $title,
            $avatar,
            $formCreate,
            $formItemsEdit,
            $itemActions
        );
    }

    public function getItemActions() : array
    {
        return $this->itemActions;
    }

    final public function getFormCreate(): array
    {
        return $this->formCreate;
    }

    final public function getFormEdit(): array
    {
        return $this->formEdit;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    final public function getAvatar(): string
    {
        return $this->avatar;
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