<?php

namespace Methods\Controller;

use Methods\Model\AddAuthorModel;
use Methods\Model\AddEditionModel;
use Methods\Model\GetPopularModel;
use Methods\Model\NewTicketModel;
use Methods\Model\SearchModel;
use Qemy\Core\Controller\AbstractController;

class Controller extends AbstractController {

    function __construct() {}

    public function addAuthorAction() {
        $model = new AddAuthorModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function addEditionAction() {
        $model = new AddEditionModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function getPopularAction() {
        $model = new GetPopularModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function newTicketAction() {
        $model = new NewTicketModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function searchAction() {
        $model = new SearchModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }
}