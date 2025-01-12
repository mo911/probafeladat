<?php

namespace Pamutlabor\Core;

abstract class PamutlaborModel
{
    
    abstract public static function save(array $data);

    abstract public static function update(array $data);

    abstract public static function delete(int $id);

    abstract public static function load(int $id);
}