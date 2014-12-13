<?php

namespace Qemy\Elibrary\Methods;

final class Methods extends AbstractMethods implements InterfaceMethods {

    function __construct(&$db) {
        parent::__construct($db);
    }

    public function search($params = [], $api = 0) {
        $query = $this->realEscapeString(htmlspecialchars((string)trim($params['q'])));
        $params = array(
            'hash' => (!empty($params['hash'])) ? $params['hash'] : 0,
            'query' => $query,
            'query_cl' => $this->invertKeyboard($query),
            'category' => (!empty($params['category'])) ? intval($params['category']) : 1,
            'count' => (!empty($params['count'])) ? intval($params['count']) : 10,
            'offset' => (!empty($params['offset'])) ? intval($params['offset']) : 0
        );
        return $this->searchMethod($params, $api);
    }

    public function getPopular($params = [], $api = 0) {
        $params = array(
            'category' => (!empty($params['category'])) ? intval($params['category']) : 1,
            'count' => (!empty($params['count'])) ? intval($params['count']) : 30,
            'offset' => (!empty($params['offset'])) ? intval($params['offset']) : 0
        );
        return $this->getPopularMethod($params);
    }

    public function getCategories($params = [], $api = 0) {
        return $this->getCategoriesMethod();
    }

    public function addEdition($params = []) {
        $params = array(
            'link' => (!empty($params['link'])) ? intval($params['link']) : null,
            'hash' => (!empty($params['hash'])) ? $params['hash'] : null,
            'time' => time(),
            'ip' => $_SERVER['REMOTE_ADDR']
        );
        return $this->addEditionMethod($params);
    }

    public function addAuthor($params = []) {
        $params = array(
            'id_edition' => (!empty($params['id'])) ? intval($params['id']) : 0,
            'author' => (!empty($params['author'])) ? $params['author'] : null,
            'time' => time()
        );
        return $this->addAuthorMethod($params);
    }

    public function addTicket($params = []) {
        $params = array(
            'email' => (!empty($params['email'])) ? $params['email'] : "Не указан",
            'message' => (!empty($params['ticket_message'])) ? $params['ticket_message'] : null,
            'time' => time()
        );
        return $this->addTicketMethod($params);
    }

    public function getMaterials($params = []) {
        return $this->getMaterialsMethod($params);
    }
}