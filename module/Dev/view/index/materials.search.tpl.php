<div class="dev_title" xmlns="http://www.w3.org/1999/html">
    <a name="m" class="dev_name_anchor"></a>
    materials.search

</div>
<div class="dev_p">
    Возвращает список с расширенной информацией о материалах в соответствии с заданным критерием поиска.
    <br><br>
    <div class="dev_title_sub">
        <a name="params" class="dev_name_anchor"></a>
        Параметры
    </div>
    <div class="dev_p">
        <ul>
            <li class="dev_li">
                <span class="dev_reset_color"><b>q</b> — строка поискового запроса. Например, "процессы".</span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
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
                                Используйте данное значение параметра, когда необходимо вернуть все поля документа для результата поиска.
                            </span>
                        </li>
                        <li class="dev_li">
                            <span class="dev_reset_color">
                                <b>compact</b> — группа, содержащая в себе следующие поля: <span class="json_key">id</span>, <span class="json_key">name</span>, <span class="json_key">download_url</span>, <span class="json_key">authors</span>, <span class="json_key">category</span>, <span class="json_key">size</span>, <span class="json_key">fave</span>.
                                Используйте данное значение параметра, когда необходимо вернуть все поля документа для результата поиска.
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
    <br>
    <div class="dev_title_sub">
        <a name="request" class="dev_name_anchor"></a>
        Пример запроса
        <a title="Ссылка на раздел" href="#request" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.search#request', 'Ссылка на раздел', 400)"></a>
    </div>
    <div class="dev_blockquote">
        http://twosphere.ru/api/<b>materials.search</b>?q=<span class="json_value">математический%20анализ</span>&count=<span class="json_value">2</span>&fields=<span class="json_value">all</span>&access_token=<span class="json_value">ACCESS_TOKEN</span>
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
    <span class="json_key">"empty_query"</span>: false,
    <span class="json_key">"q"</span>: <span class="json_value">"математический анализ"</span>,
    <span class="json_key">"category"</span>: {
        <span class="json_key">"id"</span>: <span class="json_value">1</span>,
        <span class="json_key">"name"</span>: <span class="json_value">"Все"</span>
    },
    <span class="json_key">"found"</span>: <span class="json_value">true</span>,
    <span class="json_key">"lang_cl"</span>: <span class="json_value">false</span>,
    <span class="json_key">"items_count"</span>: <span class="json_value">2</span>,
    <span class="json_key">"all_items_count"</span>: <span class="json_value">10</span>,
    <span class="json_key">"view_count"</span>: <span class="json_value">"Найдено 10 документов"</span>,
    <span class="json_key">"sid"</span>: <span class="json_value">"334f9acb2fed24df"</span>,
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
            <span class="json_key">"count_dl"</span>: <span class="json_value">330</span>,
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
    <br>
    <div class="dev_title_sub">
        <a name="retvals" class="dev_name_anchor"></a>
        Возвращаемые поля
        <a title="Ссылка на раздел" href="#retvals" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.search#retvals', 'Ссылка на раздел', 400)"></a>
    </div>
    <div class="dev_p">
        Объект <b>response</b> содержит следующие поля:
        <br><br>
        <ul>
            <li class="dev_li">
                <span class="dev_reset_color"><b>empty_query</b> — отвечает за флаг пустого запроса, т.е. переданный параметр <b>q</b> — пустой.</span>
                <br>
                <span class="dev_type_description">bool</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>items</b> — список полученных материалов.</span>
                <br>
                <span class="dev_type_description">массив</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>category</b> — объект. Содержит идентификатор и название категории, для которой был произведен поиск.</span>
                <br>
                <span class="dev_type_description">объект category</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>found</b> — если что-то найдено, то принимает значение true, иначе false.</span>
                <br>
                <span class="dev_type_description">bool</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>lang_cl</b> — флаг, указывающий на восстановленную раскладку клавиатуры для данного поискового запроса.</span>
                <br>
                <span class="dev_type_description">bool</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>items_count</b> — длина массива <b>items</b>.</span>
                <br>
                <span class="dev_type_description">неотрицательное число</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>all_items_count</b> — количество всех найденных материалов по заданному критерию поиска, исключая параметры <b>count</b> и <b>offset</b>.</span>
                <br>
                <span class="dev_type_description">неотрицательное число</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color">
                    <b>view_count</b> — all_items_count в отформатированном виде.
                </span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color">
                    <b>q</b> — строка запроса, взятая из параметра <b>q</b>.
                </span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color">
                    <b>sid</b> — любая сгенерированная строка-идентификатор.
                    <br>
                    Если Вы используете асинхронный поиск, то рекомендуется хранить в приложении последний сгенерированный идентификатор отправленного запроса, чтобы сверять его с пришедшим полем <b>sid</b>.
                    Если этого не делать, ответ на созданный ранее запрос может прийти позже, и результат поиска может не соответствовать введенной строке.
                </span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
        </ul>
        <br>
        Каждый материал из списка <b>items</b> содержит в себе следующие поля, если параметр fields содержит все поля или группу <b>all</b>:
        <br><br>
        <ul>
            <li class="dev_li">
                <span class="dev_reset_color"><b>id</b> — идентификатор материала.</span>
                <br>
                <span class="dev_type_description">положительное число</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>name</b> — название материала.</span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>authors</b> — список авторов.</span>
                <br>
                <span class="dev_type_description">массив</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>category</b> — объект. Содержит идентификатор и название категории.</span>
                <br>
                <span class="dev_type_description">объект category</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>count_dl</b> — количество загрузок материала.</span>
                <br>
                <span class="dev_type_description">неотрицательное число</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>photo_small</b> — URL <b>маленькой</b> фотографии первой страницы документа. Превью документа.</span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>photo_big</b> — URL <b>большой</b> фотографии первой страницы документа. Превью документа.</span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color">
                    <b>download_url</b> — ссылка на скачивание материала.
                </span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color">
                    <b>size</b> — размер материала. Например: 3Mb.
                </span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color">
                    <b>fave</b> — Принимает истинное значение, если материал содержится в Избранном.
                </span>
                <br>
                <span class="dev_type_description">bool</span>
            </li>
        </ul>
    </div>
</div>