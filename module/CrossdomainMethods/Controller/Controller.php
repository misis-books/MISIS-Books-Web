<?php

namespace CrossdomainMethods\Controller;

use CrossdomainMethods\Model\GetPopularModel;
use CrossdomainMethods\Model\SearchModel;
use Qemy\Core\Controller\AbstractController;

class Controller extends AbstractController {

    function __construct() {}

    public function getPopularAction() {
        $model = new GetPopularModel();
        $response = $model->main()->getData();
        $callback_func = $this->getParams()['get']['callback'];
        echo $callback_func. '(' . json_encode($response) . ')';
    }

    public function searchAction() {
        $model = new SearchModel();
        $response = $model->main()->getData();
        $callback_func = $this->getParams()['get']['callback'];
        echo $callback_func. '(' . json_encode($response) . ')';
    }
}