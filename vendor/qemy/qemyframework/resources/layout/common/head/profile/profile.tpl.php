<?php
/**
* @var \Qemy\Core\View\AbstractView $this
* @var \Qemy\User\User $user
*/
$user = $this->getData()['user'];
/**
* @param $u \Qemy\User\User
*/
?>
<div class="header-profile__layer">
    <div class="header-profile__wrapper">
        <div class="header-profile__inner">
            <a target="_blank" href="<?=$user->getVkProfileReference()?>" class="header-profile__link link-block" title="Открыть профиль" onclick="return false;">
                <div class="header-profile__view-name">
                    <span class="header-profile__full-name"><?=$user->getViewName()?></span>
                    <span class="header-profile__first-name" style="display: none;"><?=$user->getFirstName()?></span>
                </div>
                <div class="header-profile__photo-layer">
                    <div class="header-profile__photo" style="background-image: url(<?=$user->getPhoto()?>)"></div>
                </div>
            </a>
            <div id="profile_info_layer" class="header-profile__user-info-panel__layer">
                <div class="header-profile__user-info-panel">
                    <div class="block-triangle triangle__top"></div>
                    <div class="header-profile__user-info__layer">
                        <div class="header-profile__user-info__inner">
                            <div class="header-profile__user-photo__layer">
                                <div class="header-profile__user-photo">
                                    <div class="header-profile__user-photo__bg" style="background-image: url(<?=$user->getPhoto()?>)"></div>
                                </div>
                            </div>
                            <div class="header-profile__full-info__layer">
                                <div class="header-profile__full-info">
                                    <div class="header-profile__full-info__name">
                                        <span class="header-profile__full-info__name__text"><?=$user->getViewName()?></span>
                                    </div>
                                    <div class="header-profile__full-info__subscription">
                                        <?php if ($user->hasSubscription()):?>
                                            <span class="header-profile__full-info__sub__text color-green">Подписка оформлена</span>
                                        <?php else: ?>
                                            <span class="header-profile__full-info__sub__text">Нет подписки</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="header-profile__get-subscription">
                                        <?php if ($user->hasSubscription()):?>
                                            <div class="header-profile__subscription-needed__wrapper">
                                                <div class="header-profile__subscription-needed__layer">
                                                    <div class="header-profile__subscription-needed__text"><?=$user->getRemainingSubcriptionViewF()?></div>
                                                    <a href="/payment" class="button-block button-color-red" style="overflow: hidden;margin-bottom: -3px;">Продлить подписку</a>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="header-profile__subscription-needed__wrapper">
                                                <div class="header-profile__subscription-needed__layer">
                                                    <div class="header-profile__subscription-needed__text">Для доступа к сайту необходима подписка</div>
                                                    <a href="/payment" class="button-block button-color-red" style="overflow: hidden;margin-bottom: -3px;">Получить подписку</a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-exit__layer">
                            <div class="profile-exit">
                                <button onclick="App.logout()" class="button-block button-exit">Выйти</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>