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
                    <strong>Статистика</strong>
                </div>
            </div>
        </div>
        <?php
        $this->includeView('head.profile');
        ?>
    </div>
</div>