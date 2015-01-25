<?php

namespace Qemy\Vk\Api;

class VkApiException extends \Exception {

    public function getResult() {
        return $this->getMessage();
    }
}