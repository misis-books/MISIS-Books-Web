<?php

namespace Doc\Model;

use Qemy\Core\Model\AbstractModel;
use Qemy\Elibrary\Elibrary;

class IndexModel extends AbstractModel {

    public function main() {
        $elib = new Elibrary($this->getQemyDb());
        $data = $this->getRequestParams();
        //$elib->ShowError('Пока тут все сломано, сбегай за пособием в МИСиС.');
        $elib->ShowFile($data['get']['id']);
        return $this;
    }
}