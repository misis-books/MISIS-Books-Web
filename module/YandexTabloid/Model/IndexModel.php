<?php

namespace YandexTabloid\Model;

use Qemy\Core\Model\AbstractModel;

class IndexModel extends AbstractModel {

    public function main() {
        $count = $this->getQemyDb()->query("SELECT COUNT(*) FROM `editions`")->fetch_array()[0];
        $container = array(
            "count" => $count
        );
        $this->setData($container);
        return $this;
    }
}