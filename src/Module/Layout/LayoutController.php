<?php

namespace Pamutlabor\Module\Layout;

use Pamutlabor\Core\AbstractController;
use Pamutlabor\Module\Layout\LayoutView;

abstract class LayoutController extends AbstractController
{
    protected $layout;

    protected $data = [];

    public function __construct() {
        $this->layout = new LayoutView();
    }

    public function process(array $variable = [], array $post = [], array $get = []) {
        $this->handleRequest($variable, $post, $get);
        echo $this->layout->render($this->data);
    }

    abstract protected function handleRequest(array $variable = [], array $post = [], array $get = []);

}