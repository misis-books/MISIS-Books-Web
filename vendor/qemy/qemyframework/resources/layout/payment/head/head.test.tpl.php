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
            </div>
        </div>
    </div>
</div>