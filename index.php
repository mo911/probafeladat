<?php

try {
    require __DIR__ . '/vendor/autoload.php';

    require __DIR__ . '/src/Core/Router.php';
} catch(\Exception $ex) {
    $log = new \Monolog\Logger('name');
    $log->pushHandler(new Monolog\Handler\StreamHandler('/tmp/error.log', Monolog\Logger::ERROR));
    $log->error($ex);
    throw $ex;
}