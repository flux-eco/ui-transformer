<?php

require_once __DIR__ . '/../vendor/autoload.php';

fluxDotEnv\loadDotEnv(__DIR__);

//get page list
$pages = fluxUiTransformer\getPages();

echo "getPages(): ".PHP_EOL;
print_r($pages).PHP_EOL.PHP_EOL;


//get page
$pageTopic = fluxUiTransformer\getPageDefinition('topic');

echo "getPageDefinition('topic'): ".PHP_EOL;
print_r($pageTopic).PHP_EOL;


