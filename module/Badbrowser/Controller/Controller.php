<?php

namespace Badbrowser\Controller;

use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $view = new View('Badbrowser');
        $view->setContent('badbrowser');
        $view->generate();
    }
}