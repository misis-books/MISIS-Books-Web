<?php

namespace YandexTabloid\Controller;

use Qemy\Core\Controller\AbstractController;
use YandexTabloid\Model\IndexModel;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $model = new IndexModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }
}