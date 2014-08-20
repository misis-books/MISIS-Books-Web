<div class="container">
    <div class="content_layout">
        <div class="content_wrapper" style="text-align: center; font-size: 22px; padding-top: 30px; font-family: 'Roboto Regular', Arial, sans-serif;">
            Страницы не существует
        </div>
    </div>
    <?php
    $this->includeView('footer');
    ?>
</div>
<?php
$this->includeModuleView('add_author');
$this->includeModuleView('copy_link');
$this->includeModuleView('add_edition');
$this->includeModuleView('preview');
?>