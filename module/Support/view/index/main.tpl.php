<div class="container">
    <div class="content_layout">
        <div class="content_wrapper">
            <div class="support_wrap" style="margin: 0 auto; width: 600px;">
                <div class="support_header">
                    <div class="support_header_layer">
                        <span class="support_header_text">Здесь Вы можете сообщить о любой проблеме, связанной с сайтом</span>
                    </div>
                </div>
                <div class="support_content_layout">
                    <div class="support_content">
                        <div class="support_content_textarea">
                            <textarea id="tickets_text" class="textarea_default" placeholder="Пожалуйста, опишите подробно Вашу проблему.."></textarea>
                        </div>
                        <div class="support_content_email" style="display: none; margin-top: 6px;">
                            <div style="padding: 0 0 11px;">Ваш email:</div>
                            <input id="tickets_email" class="input_text_default" maxlength="100" placeholder="Пожалуйста, укажите Ваш email для связи..">
                        </div>
                    </div>
                </div>
                <div class="support_footer_layout" style="overflow: hidden;">
                    <div class="support_footer">
                        <div class="support_submit_layer fl_l">
                            <button class="support_submit" onclick="Methods.sendTicket()">Отправить</button>
                        </div>
                        <div id="support_submit_state" class="support_submit_state fl_l" style="display: none;">Сообщение успешно отправлено и будет рассмотрено.</div>
                        <div id="support_valid_email" class="support_submit_state fl_l" style="display: none;">Введите корректный email.</div>
                    </div>
                </div>
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