<?php
/** @var \Qemy\Core\View\AbstractView $this */
/** @var \Qemy\User\User $user */
$user = $this->getData()['user'];
?>
<div class="payment-selection__layer">
    <div class="payment-selection__description-layer">
        <div class="payment-selection__description" style="font-size: 15px;">
            <h1 style="color: #FF5353;">Платеж не произведен</h1>
            Платеж отменен. Если это ошибка, напишите на <a class="link-block" style="color: #ff5f64" href="mailto://admin@twosphere.ru"><strong>admin@twosphere.ru</strong></a>.
            <br><br>
        </div>
    </div>
</div>