<?php

namespace Cron\Controller;

use Cron\Model\IndexModel;
use Qemy\Core\Controller\AbstractController;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $model = new IndexModel();
        $model->main();
    }
}