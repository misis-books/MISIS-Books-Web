<?php

namespace Methods\Controller;

use Methods\Model\AddAuthorModel;
use Methods\Model\AddEditionModel;
use Methods\Model\GetMaterialsModel;
use Methods\Model\GetPopularModel;
use Methods\Model\NewTicketModel;
use Methods\Model\SearchModel;
use Qemy\Core\Application;
use Qemy\Core\Controller\AbstractController;

class Controller extends AbstractController {

    function __construct() {
        Application::disableRequestCache();
        Application::setContentType('json');
    }

    public function addAuthorAction() {
        $model = new AddAuthorModel();
        $response = $model->main()->getData();
        echo json_encode($response);
        $this->sendEmail(1);
    }

    public function addEditionAction() {
        $model = new AddEditionModel();
        $response = $model->main()->getData();
        echo json_encode($response);
        $this->sendEmail(2);
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
        $this->sendEmail(4);
    }

    public function searchAction() {
        $model = new SearchModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    public function getAction() {
        header("Cache-control: public");
        header("Cache-control: max-age=1800");

        $model = new GetMaterialsModel();
        $response = $model->main()->getData();
        echo json_encode($response);
    }

    private function sendEmail($type) {
        $to = 'ipritoflex@yandex.ru';
        $subject = "No subject";
        switch($type) {
            case 1:
                $subject = '1. [MISIS Books Support] Добавлен новый автор';
                break;
            case 2:
                $subject = '2. [MISIS Books Support] Добавлен новый материал';
                break;
            case 4:
                $subject = '4. [MISIS Books Support] Добавлен новый тикет';
                break;
        }

        $message = 'http://twosphere.ru/admin';
        $headers = 'From: admin@twosphere.ru' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }
}