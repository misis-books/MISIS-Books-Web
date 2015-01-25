<?php

namespace Cron\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Cron\Cron;

class IndexModel extends AbstractModel {

    public function main() {
        $cron = new Cron($this->getQemyDb());
        $data = $this->getRequestParams();
        $cron->createPopularSnapshot($data['get']);

        return $this;
    }
}