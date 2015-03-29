<div class="dev_title">
    <a name="m" class="dev_name_anchor"></a>
    materials.getPopular

</div>
<div class="dev_p">
    Возвращает список популярных материалов. (Выборка с сортировкой по убыванию количества скачиваний)
    <br><br>
    <div class="dev_title_sub">
        <a name="params" class="dev_name_anchor"></a>
        Параметры
    </div>
    <div class="dev_p">
        <ul>
            <li class="dev_li">
                <span class="dev_reset_color"><b>count</b> — количество возвращаемых материалов.</span>
                <br>
                <span class="dev_type_description">положительное число, по умолчанию <b>10</b>, максимальное значение <b>200</b></span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>offset</b> — смещение относительно первого материала для выборки определенного подмножества.</span>
                <br>
                <span class="dev_type_description">неотрицательное число, по умолчанию <b>0</b></span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>category</b> — идентификатор категории. Актуальный список можно получить <a class="dev_link" href="/dev/materials.getCategories">здесь</a>.</span>
                <br>
                <span class="dev_type_description">число от <b>1</b> до <b><?=8?></b></span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color">
                    <b>fields</b> — поля документа, которые необходимо получить, перечисленные через запятую.
                    <br>
                    Можно перечислить необходимые поля, которые описаны чуть ниже на странице. Помимо перечисления полей, определены 2 группы, содержащие в себе определенный набор полей.
                    <br>
                    <span class="dev_type_description">строка</span>
                    <br><br>
                    <ul>
                        <li class="dev_li">
                            <span class="dev_reset_color">
                                <b>all</b> — группа, содержащая в себе все существующие поля.
                                Используйте данное значение параметра, когда необходимо вернуть все поля документа.
                            </span>
                        </li>
                        <li class="dev_li">
                            <span class="dev_reset_color">
                                <b>compact</b> — группа, содержащая в себе следующие поля: <span class="json_key">id</span>, <span class="json_key">name</span>, <span class="json_key">download_url</span>, <span class="json_key">authors</span>, <span class="json_key">category</span>, <span class="json_key">size</span>, <span class="json_key">fave</span>.
                                Используйте данное значение параметра, когда необходимо вернуть все поля документа.
                            </span>
                        </li>
                        <li class="dev_li">
                            <span class="dev_reset_color">
                                <b>default</b> — группа, содержащая в себе следующие поля: <span class="json_key">id</span>, <span class="json_key">name</span>.
                                Группа, которая ставится по умолчанию, если не передан параметр <b>fields</b>.
                            </span>
                        </li>
                    </ul>
                    Группы можно комбинировать, например: <b>photo_big,compact,count_dl</b>.
                    <br>
                </span>
            </li>
        </ul>
    </div>
    <div class="dev_title_sub">
        <a name="response" class="dev_name_anchor"></a>
        Пример ответа
        <a title="Ссылка на раздел" href="#response" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.search#response', 'Ссылка на раздел', 400)"></a>
    </div>
    <div id="test" class="dev_blockquote">
        <pre style="margin: 0; padding: 0;">
{<span class="json_key">"response"</span>: {
    <span class="json_key">"status"</span>: <span class="json_value">"OK"</span>,
    <span class="json_key">"category"</span>: {
        <span class="json_key">"id"</span>: <span class="json_value">1</span>,
        <span class="json_key">"name"</span>: <span class="json_value">"Все"</span>
    },
    <span class="json_key">"items_count"</span>: <span class="json_value">2</span>,
    <span class="json_key">"all_items_count"</span>: <span class="json_value">10</span>,
    <span class="json_key">"view_count"</span>: <span class="json_value">"Найдено 10 документов"</span>,
    <span class="json_key">"items"</span>: [ {
            <span class="json_key">"id"</span>: <span class="json_value">236</span>,
            <span class="json_key">"name"</span>: <span class="json_value">"№190 Математиче..."</span>,
            <span class="json_key">"download_url"</span>: <span class="json_value">"http://twospher..."</span>,
            <span class="json_key">"size"</span>: <span class="json_value">"1Mb"</span>,
            <span class="json_key">"photo_big"</span>: <span class="json_value">"http://s.twosphere..."</span>,
            <span class="json_key">"photo_small"</span>: <span class="json_value">"http://s.twosphere..."</span>,
            <span class="json_key">"authors"</span>: [
                <span class="json_value">"Плужникова Е. Л."</span>,
                <span class="json_value">"Разумейко Б. Г."</span>
            ],
            <span class="json_key">"category"</span>: {
                <span class="json_key">"id"</span>: <span class="json_value">2</span>,
                <span class="json_key">"name"</span>: <span class="json_value">"Пособия"</span>
            },
            <span class="json_key">"count_dl"</span>: <span class="json_value">3230</span>,
            <span class="json_key">"fave"</span>: false
        }, {
            <span class="json_key">"id"</span>: <span class="json_value">492</span>,
            <span class="json_key">"name"</span>: <span class="json_value">"№1431 Высшая математ..."</span>,
            <span class="json_key">"download_url"</span>: <span class="json_value">"http://twospher..."</span>,
            <span class="json_key">"size"</span>: <span class="json_value">"3Mb"</span>,
            <span class="json_key">"photo_big"</span>: <span class="json_value">"http://s.twosphere..."</span>,
            <span class="json_key">"photo_small"</span>: <span class="json_value">"http://s.twosphere..."</span>,
            <span class="json_key">"authors"</span>: [
                <span class="json_value">"Бобкова Л. П."</span>
            ],
            <span class="json_key">"category"</span>: {
                <span class="json_key">"id"</span>: <span class="json_value">2</span>,
                <span class="json_key">"name"</span>: <span class="json_value">"Пособия"</span>
            },
            <span class="json_key">"count_dl"</span>: <span class="json_value">1332</span>,
            <span class="json_key">"fave"</span>: true
        }
    ]
}}</pre>
    </div>
</div>