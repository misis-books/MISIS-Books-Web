<?php

namespace Template\Model;

use Qemy\Core\Application;
use Qemy\Core\Model\AbstractModel;

class GetModel extends AbstractModel {

    public function main() {
        $template_name = Application::$request_variables['get']['name'];

        $this->setData(array(
            'template_name' => $template_name
        ));

        return $this;
    }
}