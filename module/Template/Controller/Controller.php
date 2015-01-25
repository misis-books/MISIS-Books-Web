<?php

namespace Template\Controller;

use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;
use Template\Model\GetModel;
use Template\Model\IndexModel;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $model = new IndexModel();
        $data = $model->main()->getData()['keys'];

        foreach ($data as $value) {
            echo ' | '.$value.' | ';
        }
    }

    public function getAction() {
        $model = new GetModel();
        $data = $model->main()->getData();

        $view = new View('Template');
        $view->includeModuleView($data['template_name']);
    }
}