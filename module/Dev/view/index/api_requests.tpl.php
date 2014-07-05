<div class="dev_title">
    <a name="api_requests" class="dev_name_anchor"></a>
    Запросы к API
    <a title="Ссылка на раздел" href="#api_requests" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/api_requests#api_requests', 'Ссылка на раздел', 400)"></a>
</div>
<p class="dev_p">
    Для того чтобы вызвать метод API, Вам необходимо осуществить <b>POST</b> или <b>GET</b> запрос по протоколу <b>HTTP</b> на указанный URL:
</p>
<div class="dev_blockquote">
    http://twosphere.ru/api/<b>METHOD_NAME</b>?<b>PARAMETERS</b>&access_token=<b>ACCESS_TOKEN</b>
</div>
<p class="dev_p">
    METHOD_NAME – название метода из <a class="dev_link" href="http://twosphere.ru/dev/methods_list">списка функций API</a>,<br>
    PARAMETERS – параметры соответствующего метода API,<br>
    ACCESS_TOKEN – ключ доступа, полученный в результате успешной <a class="dev_link" href="http://twosphere.ru/dev/auth.register">авторизации приложения</a>.
</p>
<br>
<div class="dev_title">
    <a name="api_requests_ex1" class="dev_name_anchor"></a>
    Пример запроса
    <a title="Ссылка на раздел" href="#api_requests_ex1" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/api_requests#api_requests_ex1', 'Ссылка на раздел', 400)"></a>
</div>
<div class="dev_blockquote">
    http://twosphere.ru/api/<b>materials.search</b>?q=Степанова&count=30&offset=3&category=2&access_token=5c62ed37fc776ff3a8b285f13aadd94ac0213bf9ec6b1b42a6a8100b00f40942
</div>
<p class="dev_p">
    В ответ на такой запрос Вы получите ответ в формате <b>JSON</b>.<br><br>
    Для того, чтобы получить ответ в формате <b>XML</b>, необходимо добавить в список параметров тип формата <b>type=xml</b>.
</p>
<br>
<div class="dev_title">
    <a name="api_requests_ex2" class="dev_name_anchor"></a>
    Пример запроса с ответом в формате XML
    <a title="Ссылка на раздел" href="#api_requests_ex2" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/api_requests#api_requests_ex2', 'Ссылка на раздел', 400)"></a>
</div>
<div class="dev_blockquote">
    http://twosphere.ru/api/<b>materials.search</b>?q=Степанова&count=30&offset=3&category=2&access_token=5c62ed37fc776ff3a8b285f13aadd94ac0213bf9ec6b1b42a6a8100b00f40942&<b>type=xml</b>
</div>
<br>
<div class="dev_title">
    <a name="api_params" class="dev_name_anchor"></a>
    Передача параметров в API
    <a title="Ссылка на раздел" href="#api_params" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/api_requests#api_params', 'Ссылка на раздел', 400)"></a>
</div>
<p class="dev_p">
    Параметры могут передаваться как методом <b>GET</b>, так и <b>POST</b>.<br>
    Каждый метод имеет собственный набор поддерживаемых параметров, однако существует для всех одинаковый параметр. Это параметр <b>type</b>, который указывает на тип возвращаемого ответа.
</p>
<br>
<div class="dev_title">
    <a name="api_errors" class="dev_name_anchor"></a>
    Коды возвращаемых ошибок
    <a title="Ссылка на раздел" href="#api_errors" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/api_requests#api_errors', 'Ссылка на раздел', 400)"></a>
</div>
<div class="dev_p">
    <ul>
        <li class="dev_li">
            <span class="dev_reset_color"><b>3 Too many requests</b> — Слишком много запросов за единицу времени. После получения такой ошибки access_token будет заблокирован на короткий промежуток времени, по истечении которого, вернется в свое рабочее состояние.</span>
        </li>
        <li class="dev_li">
            <span class="dev_reset_color"><b>17 Invalid access token</b> — Передан недействительный или заблокированный access_token. После получения этой ошибки создайте новый ключ.</span>
        </li>
    </ul>
</div>