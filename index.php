<?php

require __DIR__ . '/vendor/autoload.php';

use Pamutlabor\Module\Example;

$example = new Example();
echo $example->getMessage();

$log = new \Monolog\Logger('name');
$log->pushHandler(new Monolog\Handler\StreamHandler('/tmp/app.log', Monolog\Logger::WARNING));
$log->warning('Foo');