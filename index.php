<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
define('Q_PATH', dirname(__FILE__));

require_once Q_PATH.'/application/config/autoload/global.php';
\Autoloader\Autoload::init();

\Qemy\Core\Application::init(require Q_PATH.'/application/config/application.config.php')->run();