<?php
/**
 * @var \Qemy\Core\View\AbstractView $this
 * @var \Qemy\User\User $user
 */
$head_title = $this->getData()['head_title'];
?>
<script type="text/javascript">
    var User = {a_l: 1, data: {}}, _User_json = '{}';
</script>
<div class="header-layout header-inactive">
    <div class="header__inner">
        <div class="header-title__layer" style="overflow: visible;">
            <div id="title_icon_main" class="header-title" style="opacity: 1; cursor: default;">
                <a href="/" class="header-title__icon">
                    <div class="ic-search__white"></div>
                </a>
                <div class="header-title__text" style="color: #fff;">
                    <a class="link-block_normal" href="/">
                        <strong>Главная</strong>
                    </a>
                </div>
                <div class="header-title__text" style="color: #fff; margin-left: 20px; cursor: default;">
                    <strong><?=($head_title ? $head_title : 'Оплата подписки')?></strong>
                </div>
            </div>
        </div>
        <div class="header-profile__layer">
            <div class="header-profile__wrapper">
                <div class="header-profile__inner">
                    <a target="_blank" class="header-siginin__link" style="cursor: pointer;" title="Войти через ВКонтакте" onclick="VK.Auth.login(App.authInfo, 65536);">
                        <div class="header-signin">
                            <span class="header-signin__text">Войти</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>