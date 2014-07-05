<div id="add_new_author" class="popup fixed" style="display: none;">
    <div class="popup_layout">
        <div class="popup_header">
            <span class="popup_header_text">Добавить автора</span>
            <div class="popup_close_layer">
                <span title="Закрыть" class="popup_close"></span>
            </div>
        </div>
        <div class="popup_content">
            <div class="popup_message">
                <div class="popup_message_layer">Вы можете добавить нехватающего автора для улучшения нашего поиска.</div>
            </div>
            <div class="popup_text_layer">
                <span class="popup_text">Добавить автора для: </span><br>
                <span id="popup_edition_name" class="popup_text"></span>
            </div>
            <div class="popup_input_layout">
                <div class="popup_input_field">
                    <input type="text" placeholder="ФИО автора" class="input_text_default" id="popup_author_input">
                    <input id="popup_id_edition" type="hidden" value="">
                </div>
            </div>
        </div>
        <div class="popup_footer_layout" style="overflow: hidden;">
            <div class="popup_footer">
                <div class="popup_submit_layer fl_r">
                    <button class="popup_submit" onclick="Methods.addAuthor()">Добавить</button>
                </div>
                <div class="popup_submit_state fl_r">Автор успешно добавлен.</div>
                <span class="popup_submit_description clear_fix fl_r">Автор будет добавлен после проверки.</span>
            </div>
        </div>
    </div>
</div>