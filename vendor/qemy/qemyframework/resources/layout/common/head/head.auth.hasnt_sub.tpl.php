<?php
/**
 * @var \Qemy\Core\View\AbstractView $this
 * @var \Qemy\User\User $user
 */
$user = $this->getData()['user'];
/**
 * @param $u \Qemy\User\User
 */
function printJsonUser($u) {
    $result = array();
    if (empty($u) || !$u->isAuth()) {
        return '{}';
    }
    $result['id'] = $u->getId();
    $result['photo'] = $u->getPhoto();
    $result['view_name'] = $u->getViewName();
    $result['vk_profile'] = $u->getVkProfileReference();
    $result['has_subscription'] = $u->hasSubscription();
    $result['remaining_days'] = $u->getRemainingSubscriptionDays();
    $result['remaining_view_days'] = $u->getRemainingSubscriptionViewDays();
    $result['vk_id'] = $u->getVkId();
    $result['count_dl'] = $u->getDownloadCount();
    return json_encode($result);
}
?>
<script type="text/javascript">
    var User = {a_l: 1, data: {}}, _User_json = '<?=printJsonUser($user)?>';
</script>
<div class="header-layout header-inactive">
    <div class="header__inner">
        <div class="header-title__layer">
            <div id="title_icon_back" class="header-title" style="display: none;">
                <div class="header-title__icon">
                    <div class="ic-back__black"></div>
                </div>
                <div class="header-title__text">
                    <strong>Назад</strong>
                </div>
            </div>
            <div id="title_icon_search" class="header-title" style="opacity: 1;">
                <div class="header-title__icon">
                    <div class="ic-search__white"></div>
                </div>
                <div class="header-title__text" style="color: #fff;">
                    <strong>Поиск</strong>
                </div>
            </div>
            <div id="title_icon_fave" class="header-title" style="display: none; opacity: 1;">
                <div class="header-title__icon">
                    <div class="ic-fave__white"></div>
                </div>
                <div class="header-title__text" style="color: #fff;">
                    <strong>Избранное</strong>
                </div>
            </div>
        </div>
        <div class="header-search__layer">
            <div class="header-search__inner">
                <div class="header-search__content">
                    <div class="header-search__interface" style="padding-right: 10px;">
                        <div class="header-search__input-layer" style="padding-left: 20px;">
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
                                <input id="search_input" disabled placeholder="Необходима подписка" autocomplete="off" maxlength="500" class="search-input search-input__main" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $this->includeView('head.profile');
        ?>
    </div>
</div>