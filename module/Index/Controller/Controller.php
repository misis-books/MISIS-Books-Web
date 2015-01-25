<?php

namespace Index\Controller;

use Index\Model\IndexModel;
use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Index');
        $view->setContent('main');
        $view->setData($data);
        $view->generate();
    }
}