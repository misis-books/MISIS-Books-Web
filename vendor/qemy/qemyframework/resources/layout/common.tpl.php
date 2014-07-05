<!DOCTYPE html>
<html>
    <head>
        <?php
        /** @var $this \Qemy\Core\View\View */
        $this->includeHeaders();
        ?>
    </head>
    <body>
        <?php
        /** @var $this \Qemy\Core\View\View */
        $this->includePage();
        /** @var $this \Qemy\Core\View\View */
        $this->includeScripts();
        ?>
    </body>
</html>