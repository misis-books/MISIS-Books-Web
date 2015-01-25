<?php
/** @var \Qemy\Core\View\AbstractView $this */
/** @var \Qemy\User\User $user */
$user = $this->getData()['user'];
$result = $this->getData()['result'];
?>
<div class="content__not-auth__layer" style="padding-bottom: 100px;">
    <div class="content__not-auth">
        <div class="content__not-auth__description">
            <?php if ($result): ?>
                <span>Промо код успешно активирован. У вас <?=$user->getRemainingSubscriptionViewDays()?> подписки.</span>
                <div class="payment-selection__form" style="margin-top: 20px;">
                    <a href="/" class="button-content__payment button-payment__normal link-block_normal">
                        <div class="payment-button__text">Начать поиск</div>
                    </a>
                </div>
            <?php else: ?>
                <span>Промо код уже был активирован.</span>
            <?php endif; ?>
        </div>
    </div>
</div>