<?php

namespace Err404\Controller;

use Err404\Model\IndexModel;
use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        header("HTTP/1.1 404 Not Found");
        $view = new View('Err404');
        $view->setContent('content');
        $view->setData($data);
        $view->generate();
    }
}