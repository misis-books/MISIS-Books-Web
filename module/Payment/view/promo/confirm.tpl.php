<?php
/** @var \Qemy\Core\View\AbstractView $this */
/** @var \Qemy\User\User $user */
$user = $this->getData()['user'];
$result = $this->getData()['result'];
?>
<div class="content-layer">
    <div class="content-wrapper">
        <div class="content-visible">
            <div class="content-inner" style="padding: 0 0 1px;">
                <?php if ($result): ?>
                    <div class="content__not-auth__layer" style="padding-bottom: 100px;">
                        <div class="content__not-auth" style="padding: 40px 20px 30px; background: #FFF; border-radius: 3px;">
                            <div class="content__not-auth__description">
                                <div style="background: url(/st/img/congratulations.png); width: 100px; height: 100px; position: absolute; background-position: center center; background-size: 100%; top: -36px; left: -34px;"></div>
                                <span>Поздравляем!<br><br>Вы получили бесплатный купон на использование сервиса.</span>
                                <div class="payment-selection__form" style="margin-top: 20px;">
                                    <a href="/payment/activatePromo" class="button-content__payment button-payment__normal link-block_normal">
                                        <div class="payment-button__text">Активировать</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="content__not-auth__layer" style="padding-bottom: 100px;">
                        <div class="content__not-auth" style="padding: 40px 20px 30px; background: #FFF; border-radius: 3px;">
                            <div class="content__not-auth__description">
                                <div style="background: url(/st/img/promo_err.png); width: 100px; height: 100px; position: absolute; background-position: center center; background-size: 100%; top: -36px; left: -34px;"></div>
                                <span>Сожалеем...<br><br>Данный подарочный промокод уже был активирован.</span>
                                <div class="payment-selection__form" style="margin-top: 20px;">
                                    <a href="/" class="button-content__payment button-payment__normal link-block_normal">
                                        <div class="payment-button__text">Главная</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            $this->includeView('footer');
            ?>
        </div>
    </div>
</div>