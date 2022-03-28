<?php

namespace FluxEco\UiTransformer\Adapters\Markdown;

use Exception;
use FluxEco\UiTransformer\Core\Ports;

class MarkdownToHtmlConverterRestApiClient implements Ports\Markdown\MarkdownClient
{
    private function __construct(private string $url) {

    }

    public static function new(?string $url = null) : static {
        return new static(
            $url ?? "http://markdown-to-html-converter-rest-api:9501"
        );
    }

    public function convert(string $markdown) : string
    {
        return $this->request("convert", [
            "markdown" => $markdown
        ])["html"];
    }

    public function convertMultiple(array $markdowns) : array
    {
        return array_map(fn(array $html) : string => $html["html"], $this->request("convert-multiple", array_map(fn(string $markdown) : array => [
            "markdown" => $markdown
        ], $markdowns)));
    }

    private function request(string $route, array $markdowns) : array
    {
        $curl = null;

        try {
            $curl = curl_init($this->url . "/" . $route);

            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($markdowns));

            $headers = [
                "Content-Type" => "application/json"
            ];
            curl_setopt($curl, CURLOPT_HTTPHEADER, array_map(fn(string $key, string $value) : string => $key . ": " . $value, array_keys($headers), $headers));

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);

            $return = curl_exec($curl);
            if (curl_errno($curl) !== 0) {
                throw new Exception(curl_error($curl));
            }

            return json_decode($return, true);
        } finally {
            if ($curl !== null) {
                curl_close($curl);
            }
        }
    }
}