<div class="dev_title">
    <a name="m" class="dev_name_anchor"></a>
    materials.search
    <a title="Ссылка на раздел" href="#m" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/#m', 'Ссылка на раздел', 400)"></a>
</div>
<div class="dev_p">
    Возвращает список с расширенной информацией о материалах в соответствии с заданным критерием поиска.
    <br><br>
    <div class="dev_title_sub">
        <a name="params" class="dev_name_anchor"></a>
        Параметры
        <a title="Ссылка на раздел" href="#params" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.search#params', 'Ссылка на раздел', 400)"></a>
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
        </ul>
    </div>
    <br>
    <div class="dev_title_sub">
        <a name="request" class="dev_name_anchor"></a>
        Пример запроса
        <a title="Ссылка на раздел" href="#request" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/materials.search#request', 'Ссылка на раздел', 400)"></a>
    </div>
    <div class="dev_blockquote">
        http://twosphere.ru/api/<b>materials.search</b>?q=математический%20анализ&count=2&access_token=ACCESS_TOKEN
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
    <span class="json_key">"emptyQuery"</span>: false,
    <span class="json_key">"items"</span>: [ {
            <span class="json_key">"index"</span>: <span class="json_value">1</span>,
            <span class="json_key">"id"</span>: <span class="json_value">236</span>,
            <span class="json_key">"name"</span>: <span class="json_value">"№190 Математиче..."</span>,
            <span class="json_key">"download_url"</span>: <span class="json_value">"http://twospher..."</span>,
            <span class="json_key">"file_url"</span>: <span class="json_value">"http://s.twosphere.ru/..."</span>,
            <span class="json_key">"file_size"</span>: <span class="json_value">"1Mb"</span>,
            <span class="json_key">"photo_big"</span>: <span class="json_value">"http://s.twosphere..."</span>,
            <span class="json_key">"photo_small"</span>: <span class="json_value">"http://s.twosphere..."</span>,
            <span class="json_key">"authors"</span>: [
                <span class="json_value">"Плужникова Е. Л."</span>,
                <span class="json_value">"Разумейко Б. Г."</span>
            ],
            <span class="json_key">"category"</span>: <span class="json_value">2</span>,
            <span class="json_key">"count_dl"</span>: <span class="json_value">36</span>
        }, {
            <span class="json_key">"index"</span>: <span class="json_value">2</span>,
            <span class="json_key">"id"</span>: <span class="json_value">492</span>,
            <span class="json_key">"name"</span>: <span class="json_value">"№1431 Высшая математ..."</span>,
            <span class="json_key">"download_url"</span>: <span class="json_value">"http://twospher..."</span>,
            <span class="json_key">"file_url"</span>: <span class="json_value">"http://s.twospher..."</span>,
            <span class="json_key">"file_size"</span>: <span class="json_value">"3Mb"</span>,
            <span class="json_key">"photo_big"</span>: <span class="json_value">"http://s.twosphere..."</span>,
            <span class="json_key">"photo_small"</span>: <span class="json_value">"http://s.twosphere..."</span>,
            <span class="json_key">"authors"</span>: [
                <span class="json_value">"Бобкова Л. П."</span>
            ],
            <span class="json_key">"category"</span>: <span class="json_value">2</span>,
            <span class="json_key">"count_dl"</span>: <span class="json_value">39</span>
        }
    ],
    <span class="json_key">"category"</span>: {
        <span class="json_key">"key"</span>: <span class="json_value">1</span>,
        <span class="json_key">"value"</span>: <span class="json_value">"Все"</span>
    },
    <span class="json_key">"found"</span>: <span class="json_value">true</span>,
    <span class="json_key">"lang_cl"</span>: <span class="json_value">false</span>,
    <span class="json_key">"text"</span>: <span class="json_value">"Найдено 10 элементов"</span>,
    <span class="json_key">"items_count"</span>: <span class="json_value">2</span>,
    <span class="json_key">"all_items_count"</span>: <span class="json_value">10</span>,
    <span class="json_key">"query"</span>: <span class="json_value">"математический анализ"</span>
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
                <span class="dev_reset_color"><b>emptyQuery</b> — отвечает за флаг пустого запроса, т.е. переданный параметр <b>q</b> — пустой.</span>
                <br>
                <span class="dev_type_description">bool</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>items</b> — список материалов.</span>
                <br>
                <span class="dev_type_description">массив</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>authors</b> — список авторов.</span>
                <br>
                <span class="dev_type_description">массив</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>category</b> — идентификатор категории, к которой относится данный материал.</span>
                <br>
                <span class="dev_type_description">число от <b>1</b> до <b><?=8?></b></span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color"><b>count_dl</b> — количество загрузок материала.</span>
                <br>
                <span class="dev_type_description">неотрицательное число</span>
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
                    <b>file_size</b> — размер материала.
                </span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
            <li class="dev_li">
                <span class="dev_reset_color">
                    <b>query</b> — строка запроса, взятая из параметра <b>q</b>.
                    <br>
                    Если Вы используете асинхронный поиск, то рекомендуется хранить в приложении последний отправленный запрос, чтобы сверять его с пришедшим полем <b>query</b>. В противном случае, ответ на созданный ранее запрос может прийти позже, и результат поиска может не соответствовать введенной строке.
                </span>
                <br>
                <span class="dev_type_description">строка</span>
            </li>
        </ul>
    </div>
</div>