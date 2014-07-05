<?php

namespace Admin\Model;

use Qemy\Core\Model\AbstractModel;

class IndexModel extends AbstractModel {

    public function main() {
        $tickets = $this->getQemyDb()->query("SELECT * FROM `tickets` WHERE `active` = 0");
        $authors = $this->getQemyDb()->query("SELECT * FROM `help_authors` WHERE `active` = 0");
        $materials = $this->getQemyDb()->query("SELECT * FROM `edition_offer` WHERE `active` = 0");
        $this->setData(array(
            'tickets' => $tickets,
            'authors' => $authors,
            'materials' => $materials
        ));
        return $this;
    }
}