<div id="add_edition" class="popup fixed" style="display: none;">
    <div class="popup_layout">
        <div class="popup_header">
            <span class="popup_header_text">Добавить материал</span>
            <div class="popup_close_layer">
                <span title="Закрыть" class="popup_close"></span>
            </div>
        </div>
        <div class="popup_content">
            <?php /*<div class="search_wrapper animate_move" style="overflow: visible; background-image: url('/static/error_bg2.png'); border-bottom: initial; box-shadow: inset 0px -4px 0px -1px rgba(14, 0, 0, 0.1);">
                <div class="search_layout" style="line-height: 160%; padding: 25px;">
                    <div style="background: rgba(255, 255, 255, 0.69); border-radius: 5px; padding: 10px;">
                        На <b>elibrary.misis.ru</b> проблемы.
                        Пока нельзя добавлять новые материалы.
                    </div>
                </div>
            </div> */
            ?>
            <div class="popup_message">
                <div class="popup_message_layer">Вы можете добавить отсутствующий на этом сайте материал, который присутствует в электронной библиотеке НИТУ МИСиС.</div>
            </div>
            <div class="popup_text_layer">
                <span class="popup_text">Найдите нужный материал на сайте электронной библиотеки НИТУ МИСиС и скопируйте ссылку на него.</span><br>
                <span class="popup_text"></span>
            </div>
            <div class="popup_input_layout">
                <div class="popup_input_field">
                    <input id="add_edition_text" type="text" placeholder="Пожалуйста, вставьте сюда ссылку на материал" class="input_text_default">
                </div>
            </div>
        </div>
        <div class="popup_footer_layout" style="overflow: hidden;">
            <div class="popup_footer">
                <div class="popup_submit_layer fl_r">
                    <button class="popup_submit" onclick="Methods.addEdition()">Добавить</button>
                </div>
                <div class="popup_submit_state fl_r">Заявка успешно отправлена.</div>
                <span class="popup_submit_description clear_fix fl_r">Материал будет добавлен после проверки.</span>
            </div>
        </div>
    </div>
</div>