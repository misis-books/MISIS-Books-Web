<?php
/** @var \Qemy\Core\View\AbstractView $this */
/** @var \Qemy\User\User $user */
$user = $this->getData()['user'];
?>
<div class="content-layer">
    <div class="content-wrapper">
        <div class="content-visible">
            <div class="content-inner" style="padding: 0 0 1px;">
                <?php
                $this->includeModuleView('content');
                ?>
            </div>
            <?php
            $this->includeView('footer');
            ?>
        </div>
    </div>
</div>