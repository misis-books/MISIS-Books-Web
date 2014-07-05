<?php

namespace Qemy\Core\View;

use Qemy\Core\Application;

abstract class AbstractView {

    /** @var $data array */
    protected $data; //данные, переданные из контроллера

    /** @var $view string */
    protected $view; //путь до контента (относительный)

    /** @var $config array */
    protected $config; //глобальная конфигурация приложения

    /** @var $module_config array */
    protected $module_config; //модульная конфигурация

    /** @var $module_path string */
    protected $module_path; //полный путь до модуля

    /** @var $module_name string */
    protected $module_name; //имя модуля

    /** @var $common_view_path string */
    protected $common_view_path; //полный путь до общей верстки

    /** @var $general_includes array */
    protected $general_includes; //общие настройки подключаемых файлов

    /** @var $resources array */
    protected $resources; //ресурсы приложения

    function __construct($module_name) {
        $this->module_name = $module_name;
        $this->config = Application::$config;
        $this->common_view_path = Q_PATH.$this->config['views_options']['views_path'];

        $module_options = $this->config['module_options'];
        $this->module_path = Q_PATH.$module_options['module_path'].'/'.$module_name;
        $this->module_config_path = $this->module_path.'/config/module.config.php';
        $this->module_config = require $this->module_config_path;

        $this->general_includes = $this->mergeSettings($this->config, $this->module_config);

        $this->resources = $this->getApplicationResources();
    }

    public function setContent($view) {
        $this->view = $view;
    }

    //подключение разметки из модуля. Название передается как параметр.
    public function includeModuleView($view) {
        $view = isset($this->general_includes['module_views'][$view])
            ? $this->general_includes['module_views'][$view] : !1;
        $user_authorized = false;

        if ($view) {
            $prefix = $user_authorized && $view['authorized_mode'] ? '_authorized' : '';
            $this->includeFile($this->module_path.'/view/'.$view['path'].$prefix.'.tpl.php');
        }
    }

    //подключение разметки из общей разметки. Передается название в качестве параметра.
    public function includeView($view) {
        $view = isset($this->general_includes['views'][$view])
                ? $this->general_includes['views'][$view] : !1;
        $user_authorized = false;

        if ($view) {
            $prefix = $user_authorized && $view['authorized_mode'] ? '_authorized' : '';
            $this->includeFile($this->common_view_path.'/'.$view['path'].$prefix.'.tpl.php');
        }
    }

    //подключение скриптов
    public function includeScripts() {
        $scripts = $this->general_includes['script'];
        $script_template = '<script type="text/javascript" src="{0}"></script>';
        foreach ($scripts as $key) {
            if (array_key_exists($key, $this->resources['static']['script'])) {
                $version = isset($this->resources['static']['script'][$key]['version']) ? $this->resources['static']['script'][$key]['version'] : '';
                echo str_replace(array('{0}'), array($this->resources['static']['script'][$key]['file'].'?'.$version), $script_template);
            }
        }
    }

    //подключение содержимого head
    public function includeHeaders() {
        if (isset($this->general_includes['title'])) {
            echo '<title>'.$this->general_includes['title'].'</title>';
        }
        if (isset($this->general_includes['meta']) && is_array($this->general_includes['meta'])) {
            foreach ($this->general_includes['meta'] as $value) {
                echo $value;
            }
        }
        $styles = $this->general_includes['css'];
        if ($styles && is_array($styles)) {
            $style_template = '<link type="text/css" rel="stylesheet" href="{0}">';
            foreach ($styles as $key) {
                if (array_key_exists($key, $this->resources['static']['css'])) {
                    $version = isset($this->resources['static']['css'][$key]['version']) ? $this->resources['static']['css'][$key]['version'] : '';
                    echo str_replace(array('{0}'), array($this->resources['static']['css'][$key]['file'].'?'.$version), $style_template);
                }
            }
        }
        if (isset($this->general_includes['icon'])) {
            $key = $this->general_includes['icon'];
            $version = $this->resources['static']['img'][$key]['version'];
            echo str_replace(array('{0}'), array($this->resources['static']['img'][$key]['file'].'?'.$version), '<link rel="shortcut icon" type="image/png" href="{0}">');
        }
    }

    protected function includeFile($full_path) {
        if (file_exists($full_path)) {
            require $full_path;
        }
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getView() {
        return $this->view;
    }

    public function getData() {
        return $this->data;
    }

    private function mergeSettings($global_config, $module_config) {
        $general_includes = $global_config['views_options']['common_includes'];
        /* replace settings */
        if (isset($module_config['module_includes']['replace']) && !empty($module_config['module_includes']['replace'])) {
            foreach ($module_config['module_includes']['replace'] as $key => $value) {
                $general_includes[$key] = $value;
            }
        }
        /* merge settings */
        if (isset($module_config['module_includes']['merge']) && !empty($module_config['module_includes']['merge'])) {
            foreach ($module_config['module_includes']['merge'] as $key => $value) {
                if (!isset($general_includes[$key])) {
                    $general_includes[$key] = $value;
                    continue;
                }
                $general_includes[$key] = array_merge($general_includes[$key], $value);
            }
        }
        return $general_includes;
    }

    private function getApplicationResources() {
        return require Q_PATH.$this->config['resource_options']['path'];
    }
}