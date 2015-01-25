<?php

namespace Payment\Controller;

use Payment\Model\ActivatePromoModel;
use Payment\Model\AddPayeerPaymentModel;
use Payment\Model\AddPaymentModel;
use Payment\Model\IndexModel;
use Payment\Model\PromoModel;
use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Payment');
        $view->setContent('main');
        $view->setData($data);
        $view->generate();
    }

    public function successAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Payment');
        $view->setContent('success');
        $view->setData($data);
        $view->generate();
    }

    public function errorAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Payment');
        $view->setContent('error');
        $view->setData($data);
        $view->generate();
    }

    public function addAction() {
        $model = new AddPaymentModel();
        $model->main();
    }

    public function addPayeerAction() {
        $model = new AddPayeerPaymentModel();
        $model->main();
    }

    public function promoAction() {
        $model = new PromoModel();
        $model->main()->getData();
    }

    public function activatePromoAction() {
        $model = new ActivatePromoModel();
        $data = $model->main()->getData();

        $view = new View('Payment');
        $view->setContent('main.promo');
        $view->setData($data);
        $view->generate();
    }
}