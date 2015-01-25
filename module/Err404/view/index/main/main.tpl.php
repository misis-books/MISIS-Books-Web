<?php
/** @var \Qemy\Core\View\AbstractView $this */
/** @var \Qemy\User\User $user */
$user = $this->getData()['user'];
?>
<div class="content-layer">
    <div class="content-wrapper">
        <div class="content-visible">
            <div class="content-inner">
                <div style="text-align: center; font-size: 30px; padding: 100px;">
                    Страницы не существует
                </div>
            </div>
            <?php
            $this->includeView('footer');
            ?>
        </div>
    </div>
</div>