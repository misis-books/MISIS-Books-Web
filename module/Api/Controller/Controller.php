<?php

namespace Api\Controller;

use Api\Model\GetCategoriesModel;
use Api\Model\GetPopularModel;
use Api\Model\RegisterModel;
use Api\Model\SearchModel;
use Qemy\Core\Application;
use Qemy\Core\Controller\AbstractController;

class Controller extends AbstractController {

    function __construct() {}

    public function registerAction() {
        $model = new RegisterModel();
        $response = $model->main()->getData();
        $this->outputResponse(Application::$request_variables['request']['type'], $response);
    }

    public function getCategoriesAction() {
        $model = new GetCategoriesModel();
        $response = $model->main()->getData();
        $this->outputResponse(Application::$request_variables['request']['type'], $response);
    }

    public function getPopularAction() {
        $model = new GetPopularModel();
        $response = $model->main()->getData();
        $this->outputResponse(Application::$request_variables['request']['type'], $response);
    }

    public function searchAction() {
        $model = new SearchModel();
        $response = $model->main()->getData();
        $this->outputResponse(Application::$request_variables['request']['type'], $response);
    }

    public function outputResponse($type, $response) {
        Application::disableRequestCache();
        if ($type == 'xml') {
            Application::setContentType('xml');
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><response></response>');
            if (!empty($response['response'])) {
                $this->ArrayToXml($response['response'], $xml);
            } else {
                $this->ArrayToXml($response, $xml);
            }
            echo $xml->asXML();
        } else {
            Application::setContentType('json');
            echo json_encode($response);
        }
    }

    private function ArrayToXml($obj, &$xml) {
        foreach($obj as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    /** @var $xml \SimpleXMLElement */
                    $subnode = $xml->addChild("$key");
                    $this->ArrayToXml($value, $subnode);
                } else {
                    $subnode = $xml->addChild("item");
                    $this->ArrayToXml($value, $subnode);
                }
            } else {
                if (!is_numeric($key)) {
                    $xml->addChild("$key",htmlspecialchars("$value"));
                } else {
                    $xml->addChild("author",htmlspecialchars("$value"));
                }
            }
        }
    }
}