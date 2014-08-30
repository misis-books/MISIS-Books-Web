<?php

namespace CrossdomainMethods\Controller;

use CrossdomainMethods\Model\GetPopularModel;
use CrossdomainMethods\Model\SearchModel;
use Qemy\Core\Application;
use Qemy\Core\Controller\AbstractController;

class Controller extends AbstractController {

    function __construct() {
        Application::disableRequestCache();
        Application::setContentType('json');
    }

    public function getPopularAction() {
        $model = new GetPopularModel();
        $response = $model->main()->getData();
        $data = $this->getParams();
        $callback_func = $data['get']['callback'];
        echo $callback_func . '(' . json_encode($response) . ')';
    }

    public function searchAction() {
        $model = new SearchModel();
        $response = $model->main()->getData();
        $data = $this->getParams();
        $callback_func = $data['get']['callback'];
        echo $callback_func . '(' . json_encode($response) . ')';
    }
}
