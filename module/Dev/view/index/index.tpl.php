<div class="content-layer">
    <div class="content-wrapper">
        <div class="content-visible">
            <div class="content-inner">
                <div class="container">
                    <div class="content_layout">
                        <div class="content_wrapper">
                            <div class="dev_layout">
                                <div class="dev_content">
                                    <div class="dev_left_side fl_l">
                                        <div id="dev_layer" class="dev_layer"></div>
                                        <div id="left_container" class="dev_left_content">
                                            <?php
                                            /** @var $this \Qemy\Core\View\View */
                                            $data = $this->getData();
                                            $this->includeModuleView($data['content']);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="dev_right_side fl_r">
                                        <div class="dev_right_content">
                                            <ul>
                                                <li>
                                                    <?php
                                                    /** @var $this \Qemy\Core\View\View */
                                                    $flag = $data['action_name'] == 'index';
                                                    ?>
                                                    <a id="index_element" href="/dev" onclick="Dev.open(this, 'description'); return !(1 << 2)" class="dev_menu_element_link dev_header_element <?=(($flag)?'dev_select':'')?>">Описание</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'apiusage';
                                                    ?>
                                                    <a id="apiusage_element" href="/dev/apiusage" onclick="Dev.open(this, 'apiusage'); return !1" class="dev_menu_element_link dev_header_element <?=(($flag)?'dev_select':'')?>">Работа с API</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'api_requests';
                                                    ?>
                                                    <a id="about_element" href="/dev/api_requests" onclick="Dev.open(this, 'api_requests'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">Запросы к API</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'auth.register';
                                                    ?>
                                                    <a id="auth_element" href="/dev/auth.register" onclick="Dev.open(this, 'auth.register'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">Авторизация</a>
                                                </li>
                                                <li style="margin-top: 15px;">
                                                    <?php
                                                    $flag = $data['action_name'] == 'methods_list';
                                                    ?>
                                                    <a id="m_list_element" href="/dev/methods_list" onclick="Dev.open(this, 'methods_list'); return !1" class="dev_menu_element_link dev_header_element <?=(($flag)?'dev_select':'')?>">Список методов</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'materials.search';
                                                    ?>
                                                    <a id="m_search_element" href="/dev/materials.search" onclick="Dev.open(this, 'materials.search'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">materials.search</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'materials.getpopular';
                                                    ?>
                                                    <a id="m_pop_element" href="/dev/materials.getpopular" onclick="Dev.open(this, 'materials.getpopular'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">materials.getPopular</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'materials.getPopularForWeek';
                                                    ?>
                                                    <a id="m_pop_element" href="/dev/materials.getPopularForWeek" onclick="Dev.open(this, 'materials.getPopularForWeek'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">materials.getPopularForWeek</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'materials.getDocument';
                                                    ?>
                                                    <a id="m_pop_element" href="/dev/materials.getDocument" onclick="Dev.open(this, 'materials.getDocument'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">materials.getDocument</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'materials.getCategories';
                                                    ?>
                                                    <a id="m_categories_element" href="/dev/materials.getCategories" onclick="Dev.open(this, 'materials.getCategories'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">materials.getCategories</a>
                                                    <div class="dev-divider"></div>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'fave.getDocuments';
                                                    ?>
                                                    <a id="m_categories_element" href="/dev/fave.getDocuments" onclick="Dev.open(this, 'fave.getDocuments'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">fave.getDocuments</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'fave.addDocument';
                                                    ?>
                                                    <a id="m_categories_element" href="/dev/fave.addDocument" onclick="Dev.open(this, 'fave.addDocument'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">fave.addDocument</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'fave.deleteDocument';
                                                    ?>
                                                    <a id="m_categories_element" href="/dev/fave.deleteDocument" onclick="Dev.open(this, 'fave.deleteDocument'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">fave.deleteDocument</a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'fave.deleteAllDocuments';
                                                    ?>
                                                    <a id="m_categories_element" href="/dev/fave.deleteAllDocuments" onclick="Dev.open(this, 'fave.deleteAllDocuments'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">fave.deleteAllDocuments</a>
                                                    <div class="dev-divider"></div>
                                                </li>
                                                <li>
                                                    <?php
                                                    $flag = $data['action_name'] == 'account.getInfo';
                                                    ?>
                                                    <a id="m_categories_element" href="/dev/account.getInfo" onclick="Dev.open(this, 'account.getInfo'); return !1" class="dev_menu_element_link dev_sub_element <?=(($flag)?'dev_select':'')?>">account.getInfo</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $this->includeView('footer');
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>