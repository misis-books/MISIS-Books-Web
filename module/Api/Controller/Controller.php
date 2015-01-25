<?php

namespace Api\Controller;

use Api\Model\AccountGetInfoModel;
use Api\Model\AddFaveModel;
use Api\Model\DeleteAllFavesModel;
use Api\Model\DeleteFaveModel;
use Api\Model\GetCategoriesModel;
use Api\Model\GetFavesModel;
use Api\Model\GetPopularForWeekModel;
use Api\Model\GetPopularModel;
use Api\Model\SearchModel;
use Api\Model\SignInModel;
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

    public function authSignInAction() {
        $model = new SignInModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function accountGetInfoAction() {
        $model = new AccountGetInfoModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }
}