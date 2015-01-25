<?php
/** @var \Qemy\Core\View\AbstractView $this */
/** @var \Qemy\User\User $user */
$user = $this->getData()['user'];
?>
<div class="content-layer">
    <div class="content-wrapper">
        <?php if (!$user->isAuth()): ?>
            <div class="preview-content__layer" style="max-height: 800px; height: 0; opacity: 0;">
                <div class="preview-content">
                    <div class="preview-content__image-layer" style="opacity: 0;"></div>
                    <div class="preview-content__text-layer" style="opacity: 0;">
                        <div class="preview-content__text">
                            <h1>MISIS Books</h1>
                            <span>4.0</span>
                        </div>
                    </div>
                </div>
                <div class="btn-arrow" onclick="$.scrollTo('47%', 500, '');"></div>
            </div>
            <div class="intro__layer">
                <div class="intro__text">На всех Ваших устройствах</div>
                <div class="intro__image">
                    <img src="/st/img/intro-product.png" style="width: 100%;">
                </div>
            </div>
        <?php endif; ?>
        <div class="content-visible">
            <div class="content-inner">
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