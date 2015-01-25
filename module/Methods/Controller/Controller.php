<?php

namespace Methods\Controller;

use Methods\Model\AddFaveModel;
use Methods\Model\DeleteAllFavesModel;
use Methods\Model\DeleteFaveModel;
use Methods\Model\GetCategoriesModel;
use Methods\Model\GetFavesModel;
use Methods\Model\GetPopularForWeekModel;
use Methods\Model\GetPopularModel;
use Methods\Model\SearchModel;
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
        echo json_encode($response);
    }

    public function getPopularForWeekAction() {
        $model = new GetPopularForWeekModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function searchAction() {
        $model = new SearchModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function getCategoriesAction() {
        $model = new GetCategoriesModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function getFavesAction() {
        $model = new GetFavesModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function addFaveAction() {
        $model = new AddFaveModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function deleteFaveAction() {
        $model = new DeleteFaveModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function deleteAllFavesAction() {
        $model = new DeleteAllFavesModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }
}