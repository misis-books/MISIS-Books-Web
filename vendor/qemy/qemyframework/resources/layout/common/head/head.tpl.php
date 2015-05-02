<?php
/**
 * @var \Qemy\Core\View\AbstractView $this
 * @var \Qemy\User\User $user
 */
?>
<script type="text/javascript">
    var User = {a_l: 1, data: {}}, _User_json = '{}';
</script>
<div class="header-layout header-inactive">
    <img src="/st/img/preview-misis-books-4_0.png" style="display: none;">
    <div class="header__inner">
        <div class="header-title__layer">
            <div id="title_icon_main" class="header-title" style="opacity: 1;">
                <div class="header-title__icon">
                    <div class="ic-search__white"></div>
                </div>
                <div class="header-title__text" style="color: #fff;">
                    <strong>MISIS Books</strong>
                </div>
            </div>
        </div>
        <div class="header-search__layer">
            <div class="header-search__inner">
                <div class="header-search__content">
                    <div class="header-search__interface" style="padding-right: 10px;">
                        <div class="header-search__input-layer" style="padding-left: 10px;">
                            <div class="header-search__status" style="display: none;">
                                <div class="header-search__icon">
                                    <div class="ic-search__toggle"></div>
                                </div>
                                <div class="header-search__spin" style="display: none;">
                                    <div class="search-spin"></div>
                                </div>
                            </div>
                            <div class="header-search__input">
                                <input id="search_input_overlay" disabled autocomplete="off" maxlength="500" class="search-input search-input__overlay" />
                                <input id="search_input" disabled placeholder="Войдите, чтобы начать поиск" autocomplete="off" maxlength="500" class="search-input search-input__main" />
                            </div>
                        </div>
                    </div>
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