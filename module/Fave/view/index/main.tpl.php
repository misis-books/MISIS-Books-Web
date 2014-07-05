<div class="container">
    <div class="content_layout">
        <div class="content_wrapper"></div>
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