<?php

namespace Index\Controller;

use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $view = new View('Index');
        $view->setContent('content');
        $view->generate();
    }
}