<?php

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/src/Core/Router.php';

$log = new \Monolog\Logger('name');
$log->pushHandler(new Monolog\Handler\StreamHandler('/tmp/app.log', Monolog\Logger::WARNING));
$log->warning('Foo');