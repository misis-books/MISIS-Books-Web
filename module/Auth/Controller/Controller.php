<?php

namespace Auth\Controller;

use Auth\Model\LoginModel;
use Auth\Model\LogoutModel;
use Qemy\Core\Application;
use Qemy\Core\Controller\AbstractController;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {}

    public function loginAction() {
        $model = new LoginModel();
        $data = $model->main()->getData();
        Application::setContentType('json');
        echo json_encode($data);
    }

    public function logoutAction() {
        $model = new LogoutModel();
        $data = $model->main()->getData();
        Application::setContentType('json');
        echo json_encode($data);
    }
}