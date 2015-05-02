<?php
/**
 * @var \Qemy\Core\View\AbstractView $this
 * @var \Qemy\User\User $user
 */
$user = $this->getData()['user'];
$q = trim($_GET['q']);
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
                    <span style="font-weight: bold;">Назад</span>
                </div>
            </div>
            <div id="title_icon_search" class="header-title" style="opacity: 1;">
                <a href="/" class="header-title__icon link-block_normal">
                    <div class="ic-search__white"></div>
                </a>
                <a class="header-title__text link-block_normal" href="/">
                    <span style="font-weight: bold;">Поиск</span>
                </a>
            </div>
            <div id="title_icon_fave" class="header-title" style="display: none; opacity: 1;">
                <div class="header-title__icon">
                    <div class="ic-fave__white"></div>
                </div>
                <div class="header-title__text" style="color: #fff;">
                    <span style="font-weight: bold;">Избранное</span>
                </div>
            </div>
        </div>
        <div class="header-search__layer">
            <div class="header-search__inner">
                <div class="header-search__content">
                    <div class="header-search__interface">
                        <div class="header-search__input-layer">
                            <div class="header-search__status">
                                <div class="header-search__icon" id="searchIconInput">
                                    <div class="ic-search__toggle"></div>
                                </div>
                                <div class="header-search__icon" id="spinSearch" style="display: none;">
                                    <div class="search-spin-layer stopped">
                                        <div class="search-spin"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="header-search__input">
                                <input id="search_input_overlay" autocorrect="off" spellcheck="false" disabled autocomplete="off" maxlength="500" class="search-input search-input__overlay" />
                                <input id="search_input" value="<?=$q?>" autocorrect="off" spellcheck="false" placeholder="Поиск" autocomplete="off" maxlength="500" class="search-input search-input__main" />
                                <div class="header-search__category-layer">
                                    <div class="header-search__category">
                                        <div id="current_category" style="width: 15px; height: 15px;" class="header-search__circle-category category-1__category"></div>
                                    </div>
                                </div>
                                <div class="header-search__category-dropdown__layer">
                                    <div class="block-triangle triangle__top category-triangle"></div>
                                    <div class="header-search__category-dropdown">
                                        <div class="header-search__category-select">
                                            <div class="header-search__category-element" onclick="Page.selectCategory(this, 1)">
                                                <div class="header-search__circle-category category-1__category"></div>
                                                <div class="header-search__category__name">Все</div>
                                            </div>
                                            <div class="header-search__category-element" onclick="Page.selectCategory(this, 2)">
                                                <div class="header-search__circle-category category-2__category"></div>
                                                <div class="header-search__category__name">Пособия</div>
                                            </div>
                                            <div class="header-search__category-element" onclick="Page.selectCategory(this, 3)">
                                                <div class="header-search__circle-category category-3__category"></div>
                                                <div class="header-search__category__name">Дипломы</div>
                                            </div>
                                            <div class="header-search__category-element" onclick="Page.selectCategory(this, 4)">
                                                <div class="header-search__circle-category category-4__category"></div>
                                                <div class="header-search__category__name">Сборники научных трудов</div>
                                            </div>
                                            <div class="header-search__category-element" onclick="Page.selectCategory(this, 5)">
                                                <div class="header-search__circle-category category-5__category"></div>
                                                <div class="header-search__category__name">Монографии</div>
                                            </div>
                                            <div class="header-search__category-element" onclick="Page.selectCategory(this, 6)">
                                                <div class="header-search__circle-category category-6__category"></div>
                                                <div class="header-search__category__name">Книги МИСиС</div>
                                            </div>
                                            <div class="header-search__category-element" onclick="Page.selectCategory(this, 7)">
                                                <div class="header-search__circle-category category-7__category"></div>
                                                <div class="header-search__category__name">Авторефераты диссертаций</div>
                                            </div>
                                            <div class="header-search__category-element" onclick="Page.selectCategory(this, 8)">
                                                <div class="header-search__circle-category category-8__category"></div>
                                                <div class="header-search__category__name">Разное</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="header-fave__toggle-layer">
                            <div class="header-fave__toggle-wrapper">
                                <div class="header-fave__toggle">
                                    <div class="header-fave__toggle-element" title="Нажмите, чтобы показать избранное">
                                        <div class="header-fave__toggle-pin">
                                            <div class="header-fave-pin__white-star"></div>
                                        </div>
                                    </div>
                                </div>
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