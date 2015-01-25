<?php
/** @var \Qemy\Core\View\AbstractView $this */
/** @var \Qemy\User\User $user */
$user = $this->getData()['user'];
?>
<div class="payment-selection__layer">
    <div class="payment-selection__description-layer">
        <div class="payment-selection__description" style="font-size: 15px;">
            <h1 style="color: #FF5353;">Подписка обновлена</h1>
            Платеж прошел успешно. Спасибо за помощь в развитии проекта. Подписка будет обновлена через пару секунд. Если подписка не появится в течение пяти минут, напишите на <a class="link-block" style="color: #ff5f64" href="mailto://admin@twosphere.ru"><strong>admin@twosphere.ru</strong></a>.
            <div class="payment-selection__form" style="margin-top: 20px;">
                <a href="/" class="button-content__payment button-payment__normal link-block_normal">
                    <div class="payment-button__text">Начать поиск</div>
                </a>
            </div>
        </div>
    </div>
</div>