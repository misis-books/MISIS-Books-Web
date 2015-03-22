<?php

namespace Stats\Controller;

use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;
use Stats\Model\IndexModel;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Stats');
        $view->setContent('main');
        $view->setData($data);
        $view->generate();
    }
}