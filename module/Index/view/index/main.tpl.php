<div class="container">
    <div class="content_layout">
        <div class="pdf_wrapper" id="pdf_wrapper" style="display: none; top: 1000px; padding: 0 10px; box-sizing: border-box;">
            <div class="pdf_layer"></div>
        </div>
        <img src="/st/img/android_misis_book.png" style="display: none;"/>
        <div class="content_wrapper">
            <div class="search_wrapper" style="overflow: visible;">
                <a href="http://twosphere.ru/help" style="
                    display: block;
                    height: 100%;
                    position: absolute;
                    width: 100%;
                    z-index: 400;
                "></a>
                <div class="search_layout">
                    <div class="search_field">
                        <div><div class="b-overlay__image" style="
                            vertical-align: middle;  display: inline-block;
                            width: 50px;
                            height: 50px;
                        "></div>
                            <span class="b-predictor__font-regular" style="
                                vertical-align: middle;
                                display: inline-block;
                                font-size: 24px;
                                margin-left: 20px;
                            ">Помочь проекту »</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="search_wrapper" style="overflow: visible;">
                <div class="search_layout">
                    <div class="search_field">
                        <div class="input_wrap">
                            <div class="input_inner">
                                <input id="input_text" autocorrect="off" spellcheck="false" autocomplete="off" maxlength="500" class="input_text_default" type="text" value="<?=htmlspecialchars($_GET['q'])?>" placeholder="Введите название, номер или автора">
                                <div class="b-predictor__layer" id="predictor-layer" style="display: none;"></div>
                            </div>
                            <span id="search_loading" class="search_load_spin"></span>
                            <div title="Очистить поле" style="display: none;" id="reset_text_input" class="reset_input" onclick="Search.setSearch('', true);"></div>
                        </div>
                        <div class="sp_select_box_wrap" style="margin-left: 15px; top: -1px; position: relative;">
                            <?php
                            $select = array(
                                "Все",
                                "Пособия",
                                "Дипломы",
                                "Сборники научных трудов",
                                "Монографии, научные издания",
                                "Книги МИСиС",
                                "Авторефераты диссертаций",
                                "Разное"
                            );
                            $selected = (isset($_GET['category']) && !empty($_GET['category']) && $_GET['category'] > 0 && $_GET['category'] <= 8) ? $_GET['category'] : 1;
                            ?>
                            <div style="display: inline-block;">
                                <select id="sp_select_category" style="width: 250px; display: none;">
                                    <?php
                                    for ($i = 1; $i <= count($select); $i++) {
                                        echo "<option".(($i==$selected)?" selected='selected'":"")." value=".$i.">".$select[$i - 1]."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="search_result_wrap" style="margin-bottom: 0; overflow: visible;">
                <div class="search_result_layout"></div>
            </div>
            <div class="search_result_wrap_popular" style="margin: 23px 0 0; overflow: visible; display: none;">
                <div class="search_result_layout_popular"></div>
            </div>
        </div>
    </div>
    <?php
        $this->includeView('footer');
    ?>
</div>
<?php
    $this->includeModuleView('add_author');
    $this->includeModuleView('copy_link');
    $this->includeModuleView('add_edition');
    $this->includeModuleView('preview');
?>