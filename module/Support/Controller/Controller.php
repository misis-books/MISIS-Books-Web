<?php

namespace Support\Controller;

use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $view = new View('Support');
        $view->setContent('content');
        $view->generate();
    }
}