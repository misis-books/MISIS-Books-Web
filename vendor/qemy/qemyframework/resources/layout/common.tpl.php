<!DOCTYPE html>
<html>
    <head>
        <?php
        /** @var $this \Qemy\Core\View\View */
        $this->includeHeaders();
        ?>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="vk_api_transport"></div>
        <?php
        /** @var $this \Qemy\Core\View\View */
        $this->includePage();
        /** @var $this \Qemy\Core\View\View */
        $this->includeScripts();
        ?>
    </body>
</html>