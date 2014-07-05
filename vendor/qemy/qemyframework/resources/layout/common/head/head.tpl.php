<div id="scroll_layout" class="scroll_layout">
    <span id="scroll_inner" style="padding: 20px 0"></span>
</div>
<div class="head_container fixed">
    <header class="head_menu_sticky">
        <div class="right_column fl_r">
            <div class="top_nav_link">
                <div class="support_link">
                    <a href="#" onclick="Page.openAddEditionPopup('add_edition', 500); return false" class="header_support_link_none" id="top_support_link">
                        <div class="add_icon_cont">
                            <span class="add_icon"></span>
                        </div>
                        <span class="support_link_text">Добавить материал</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="nav">
            <ul class="tl_menu">
                <?php
                $flag = $this->module_name == 'Index';
                ?>
                <li class="<?=($flag)?'active':''?>">
                    <a href="/" class="header_menu_link<?=($flag)?'':'_none'?>" id="act_home">Поиск</a>
                </li>
                <?php
                $flag = $this->module_name == 'Fave';
                ?>
                <li class="<?=($flag)?'active':''?>">
                    <a href="/fave" class="header_menu_link<?=($flag)?'':'_none'?>" id="act_fave" style="position: relative;">Избранное<div class="count"></div></a>
                </li>
                <?php
                $flag = $this->module_name == 'Support';
                ?>
                <li class="<?=($flag)?'active':''?>">
                    <a href="/support" class="header_menu_link<?=($flag)?'':'_none'?>" id="act_support">Поддержка</a>
                </li>
                <li style="width: 62px;">
                    <a target="_blank" title="Приложение ВКонтакте" href="http://vk.com/app4102769" class="vk_app_logo"></a>
                </li>
            </ul>
        </div>
    </header>
</div>