<?php

namespace Admin\Controller;

use Admin\Model\IndexModel;
use Admin\Model\UpdModel;
use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Admin');
        $view->setContent('content');
        $view->setData($data);
        $view->generate();
    }

    public function updAction() {
        $model = new UpdModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }
}