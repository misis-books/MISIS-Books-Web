<?php

namespace Err404\Controller;

use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $view = new View('Err404');
        $view->setContent('content');
        $view->generate();
    }
}