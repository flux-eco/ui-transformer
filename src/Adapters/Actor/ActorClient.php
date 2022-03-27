<?php

namespace FluxEco\UiTransformer\Adapters\Actor;

class ActorClient
{
    private function __construct() {

    }

    public static function new() {
        return new self();
    }

    public function getLanguageCode(): string {
        //todo
        return 'en';
    }
}