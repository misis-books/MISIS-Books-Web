<?php

namespace Fave\Controller;

use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $view = new View('Fave');
        $view->setContent('content');
        $view->generate();
    }
}