<?php

namespace Pamutlabor\Core;

abstract class AbstractController {

    abstract protected function handleRequest(array $variable = [], array $post = [], array $get = []);
}