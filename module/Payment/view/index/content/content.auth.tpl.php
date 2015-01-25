<?php
/** @var \Qemy\Core\View\AbstractView $this */
/** @var \Qemy\User\User $user */
$user = $this->getData()['user'];
?>
<div class="payment-selection__layer">
    <div class="payment-selection__description-layer">
        <div class="payment-selection__description" style="font-size: 15px;">
            <h1 style="color: #FF5353;">Информация</h1>
            В связи с тем, что добровольные сборы средств на обслуживание ресурса провалились, проект MISIS Books переходит на подписочную схему помощи.
            Теперь проект приобретает автономный статус и живет исключительно за счет студентов НИТУ «МИСиС».
            <br>
            Неловкий момент: для того, чтобы начать пользоваться сервисом, вы должны приобрести подписку за совсем скромную сумму, которая пойдет на развитие проекта.
            <br>
            <br>
            Вырученных средств хватит на оплату следующего:
            <ul>
                <li>Оплата хостинга</li>
                <li>Оплата домена</li>
                <li>Оплата ежегодной подписки iOS Developer Program - для приложения MISIS Books под iOS, которое в данный момент разрабатывается</li>
                <li>Оплата регистрации в Google Play для последующего релиза приложения на Android</li>
            </ul>
            Проект очень ценит тех людей, которые помогли при добровольных сборах небольшими суммами, поэтому эти люди получают возможность использовать сервис бесплатно на протяжении <b>двух</b> лет — достаточно прислать на email-адрес <a class="link-block" style="color: #ff5f64" href="mailto:admin@twosphere.ru"><strong>admin@twosphere.ru</strong></a> свой id ВКонтакте, на который будет привязана подписка, а также скриншот перевода из истории операций (скриншот должен включать дату, время и сумму перевода).
            <br>
            <h1 style="color: #FF5353;">Оплата</h1>
            За <strong>33 рубля</strong> в месяц вы получаете комфортный доступ к электронной библиотеке НИТУ «МИСиС».
            <div class="payment-selection">
                <div class="payment-selection__yd"></div>
                <div class="payment-selection__bc payment-selection__selected"></div>
                <?php if (/*$user->getId() == 1*/true): ?>
                    <div class="payment-selection__payeer"></div>
                <?php endif; ?>
            </div>
            <div class="payment-selection__form">
                <?php
                    // 33
                    $m_shop = '39473982';
                    $m_orderid = $user->getId().'_'.rand(1000, 1e8);
                    $m_amount = number_format(33, 2, '.', '');
                    $m_curr = 'RUB';
                    $m_desc = base64_encode('MISIS Books. Оплата подписки на 1 месяц.');
                    $m_key = 'IK6TTQBITWeo0na';

                    $arHash = array(
                        $m_shop,
                        $m_orderid,
                        $m_amount,
                        $m_curr,
                        $m_desc,
                        $m_key
                    );
                    $sign = strtoupper(hash('sha256', implode(':', $arHash)));
                ?>
                <form id="payeerForm_1" method="GET" action="//payeer.com/merchant/">
                    <input type="hidden" name="m_shop" value="<?=$m_shop?>">
                    <input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
                    <input type="hidden" name="m_amount" value="<?=$m_amount?>">
                    <input type="hidden" name="m_curr" value="<?=$m_curr?>">
                    <input type="hidden" name="m_desc" value="<?=$m_desc?>">
                    <input type="hidden" name="m_sign" value="<?=$sign?>">
                    <input id="payeerForm_submit_1" type="submit" name="m_process" hidden value="send" />
                </form>

                <?php
                    // 99
                    $m_shop = '39473982';
                    $m_orderid = $user->getId().'_'.rand(1000, 1e8);
                    $m_amount = number_format(99, 2, '.', '');
                    $m_curr = 'RUB';
                    $m_desc = base64_encode('MISIS Books. Оплата подписки на 3 месяца.');
                    $m_key = 'IK6TTQBITWeo0na';

                    $arHash = array(
                        $m_shop,
                        $m_orderid,
                        $m_amount,
                        $m_curr,
                        $m_desc,
                        $m_key
                    );
                    $sign = strtoupper(hash('sha256', implode(':', $arHash)));
                ?>
                <form id="payeerForm_3" method="GET" action="//payeer.com/merchant/">
                    <input type="hidden" name="m_shop" value="<?=$m_shop?>">
                    <input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
                    <input type="hidden" name="m_amount" value="<?=$m_amount?>">
                    <input type="hidden" name="m_curr" value="<?=$m_curr?>">
                    <input type="hidden" name="m_desc" value="<?=$m_desc?>">
                    <input type="hidden" name="m_sign" value="<?=$sign?>">
                    <input id="payeerForm_submit_3" type="submit" name="m_process" hidden value="send" />
                </form>

                <?php
                    // 299
                    $m_shop = '39473982';
                    $m_orderid = $user->getId().'_'.rand(1000, 1e8);
                    $m_amount = number_format(299, 2, '.', '');
                    $m_curr = 'RUB';
                    $m_desc = base64_encode('MISIS Books. Оплата подписки на 1 год.');
                    $m_key = 'IK6TTQBITWeo0na';

                    $arHash = array(
                        $m_shop,
                        $m_orderid,
                        $m_amount,
                        $m_curr,
                        $m_desc,
                        $m_key
                    );
                    $sign = strtoupper(hash('sha256', implode(':', $arHash)));
                ?>
                <form id="payeerForm_12" method="GET" action="//payeer.com/merchant/">
                    <input type="hidden" name="m_shop" value="<?=$m_shop?>">
                    <input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
                    <input type="hidden" name="m_amount" value="<?=$m_amount?>">
                    <input type="hidden" name="m_curr" value="<?=$m_curr?>">
                    <input type="hidden" name="m_desc" value="<?=$m_desc?>">
                    <input type="hidden" name="m_sign" value="<?=$sign?>">
                    <input id="payeerForm_submit_12" type="submit" name="m_process" hidden value="send" />
                </form>

                <form id="paymentForm" method="post" action="https://money.yandex.ru/quickpay/confirm.xml">
                    <input name="label" type="hidden" value="<?=$user->getId()?>"/>
                    <input name="receiver" type="hidden" value="41001358087451"/>
                    <input name="formcomment" type="hidden" value="MISIS Books. Оформление подписки."/>
                    <input name="short-dest" type="hidden" value="MISIS Books. Оформление подписки."/>
                    <input name="quickpay-form" type="hidden" value="shop"/>
                    <input name="targets" type="hidden" value="Оплата подписки на сайте twosphere.ru"/>
                    <input type="hidden" name="successURL" value="http://<?=$_SERVER['HTTP_HOST']?>/payment/success">
                    <input id="paymentSum" name="sum" type="hidden" value="33"/>
                    <input id="paymentType" name="paymentType" type="hidden" value="AC"/>
                </form>
                <button class="button-content__payment button-payment__normal" onclick="Payment.pay(33, this)">
                    <div class="payment-button__text">Оплатить 33 <strike>Р</strike></div>
                    <div class="payment-button__subtext">
                        Подписка на 1 месяц
                    </div>
                </button>
                <button class="button-content__payment button-payment__normal" onclick="Payment.pay(99, this)">
                    <div class="payment-button__text">Оплатить 99 <strike>Р</strike></div>
                    <div class="payment-button__subtext">Подписка на 3 месяца</div>
                </button><br>
                <button class="button-content__payment button-payment__normal" onclick="Payment.pay(299, this)">
                    <div class="payment-button__text">Оплатить 299 <strike>Р</strike></div>
                    <div class="payment-button__subtext">
                        Подписка на 1 год
                        <br>
                        24 рубля в месяц
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>