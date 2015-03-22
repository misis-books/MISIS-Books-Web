<?php
/** @var \Qemy\Core\View\AbstractView $this */
/** @var \Qemy\User\User $user */
$user = $this->getData()['user'];
$sub_users = $this->getData()['sub_users'];
$show_all = $_GET['show'] == 'all';
?>
<div class="payment-selection__layer">
    <div class="payment-selection__description-layer">
        <div class="payment-selection__description" style="font-size: 15px;">
            <h1 style="color: #FF5353;">Пользователи</h1>
            <div style="">
                <div style="font-size: 10px; background: #f0f0f0; display: inline-block; padding: 10px; margin: 0 10px 10px 0;">
                    <a class="normal-link" href="/stats?show=all">Все</a>
                    <a class="normal-link" href="/stats">С подпиской</a>
                </div>
                <div style="font-size: 10px; background: #f0f0f0; display: inline-block; padding: 10px; margin: 0 10px 10px 0;">
                    <a class="normal-link">По ID: </a>
                    <a class="normal-link" href="/stats?sort=id&sort_type=asc<?=$show_all ? '&show=all' : ''?>">По возрастанию</a>
                    <a class="normal-link" href="/stats?sort=id&sort_type=desc<?=$show_all ? '&show=all' : ''?>">По убыванию</a>
                </div>
                <div style="font-size: 10px; background: #f0f0f0; display: inline-block; padding: 10px; margin: 0 10px 10px 0;">
                    <a class="normal-link">По последней активности: </a>
                    <a class="normal-link" href="/stats?sort=recent&sort_type=asc<?=$show_all ? '&show=all' : ''?>">По возрастанию</a>
                    <a class="normal-link" href="/stats?sort=recent&sort_type=desc<?=$show_all ? '&show=all' : ''?>">По убыванию</a>
                </div>
                <div style="font-size: 10px; background: #f0f0f0; display: inline-block; padding: 10px; margin: 0 10px 10px 0;">
                    <a class="normal-link">По времени оставшейся подписки: </a>
                    <a class="normal-link" href="/stats?sort=sub&sort_type=asc<?=$show_all ? '&show=all' : ''?>">По возрастанию</a>
                    <a class="normal-link" href="/stats?sort=sub&sort_type=desc<?=$show_all ? '&show=all' : ''?>">По убыванию</a>
                </div>
                <div style="font-size: 10px; background: #f0f0f0; display: inline-block; padding: 10px; margin: 0 10px 10px 0;">
                    <a class="normal-link">По кол-ву запросов: </a>
                    <a class="normal-link" href="/stats?sort=q&sort_type=asc<?=$show_all ? '&show=all' : ''?>">По возрастанию</a>
                    <a class="normal-link" href="/stats?sort=q&sort_type=desc<?=$show_all ? '&show=all' : ''?>">По убыванию</a>
                </div>
                <div style="font-size: 10px; background: #f0f0f0; display: inline-block; padding: 10px; margin: 0 10px 10px 0;">
                    <a class="normal-link">По кол-ву загрузок: </a>
                    <a class="normal-link" href="/stats?sort=dl&sort_type=asc<?=$show_all ? '&show=all' : ''?>">По возрастанию</a>
                    <a class="normal-link" href="/stats?sort=dl&sort_type=desc<?=$show_all ? '&show=all' : ''?>">По убыванию</a>
                </div>
                <?php /** @var \Qemy\User\User $cur_user */
                foreach($sub_users as $cur_user): ?>
                    <a class="list_element" target="_blank" href="<?=$cur_user->getVkProfileReference()?>" style="position: relative; padding: 20px; border-bottom: 1px solid #ccc; display: block; color: #2f2f2f; text-decoration: none;">
                        <div style="height: 100px; display: inline-block; vertical-align: top;">
                            <img src="<?=$cur_user->getPhoto()?>" style="height: 100%;"/>
                        </div>
                        <div style="top: 0; display: inline-block; vertical-align: top; margin-left: 20px;">
                            <div style=""><?=$cur_user->getViewName()?> <span style="color: #777;">#<?=$cur_user->getId()?></span></div>
                            <div style=""><?=$cur_user->getRemainingSubcriptionViewF()?> <span style="color: #777;">(<?=$cur_user->getRemainingSubscriptionTime()?> сек.)</span></div>
                            <div style="">Был в сети <?=$cur_user->getRecentActivityEllapsed()?></div>
                            <div style="">Количество запросов: <?=$cur_user->getQueriesCount()?> | Количество загрузок: <?=$cur_user->getDownloadCount()?></div>
                        </div>
                        <div style="display: inline-block; vertical-align: top; margin-left: 20px; float: right; position: absolute; right: 0; top: 20px;">
                            <div style="font-size: 30px; color: #FF7F7F; padding: 26px 21px; box-sizing: content-box; background: rgba(0, 0, 0, 0.04); border-radius: 5px;"><?=$cur_user->getSumSub()?> руб.</div>
                        </div>
                    </a>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>