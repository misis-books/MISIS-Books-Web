<div class="dev_title">
    <a name="api_requests" class="dev_name_anchor"></a>
    Запросы к API
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

</div>
<div class="dev_blockquote">
    http://twosphere.ru/api/<b>materials.search</b>?q=степанова&count=30&offset=3&category=2&access_token=5c62ed37fc776ff3a8b285f13aadd94ac0213bf9ec6b1b42a6a8100b00f40942
</div>
<p class="dev_p">
    В ответ на такой запрос Вы получите ответ в формате <b>JSON</b>.
</p>
<br>
<div class="dev_title">
    <a name="api_params" class="dev_name_anchor"></a>
    Передача параметров в API

</div>
<p class="dev_p">
    Параметры могут передаваться как методом <b>GET</b>, так и <b>POST</b>.<br>
</p>
<br>
<div class="dev_title">
    <a name="api_errors" class="dev_name_anchor"></a>
    Коды возвращаемых ошибок
</div>
<div class="dev_p">
    <ul>
        <li class="dev_li">
            <span class="dev_reset_color"><b>2 The user has no subscription</b> — Пользователь не имеет доступа к методам по причине отсутствия подписки.</span>
        </li>
        <li class="dev_li">
            <span class="dev_reset_color"><b>3 Too many requests per second</b> — Слишком много запросов за единицу времени. После получения такой ошибки access_token будет заблокирован на короткий промежуток времени, по истечении которого, вернется в свое рабочее состояние.</span>
        </li>
        <li class="dev_li">
            <span class="dev_reset_color"><b>4 Invalid Access Token</b> — Несуществующий маркер доступа (mb_access_token).</span>
        </li>
        <li class="dev_li">
            <span class="dev_reset_color"><b>5 Missing Access Token</b> — Не передан маркер доступа.</span>
        </li>
        <li class="dev_li">
            <span class="dev_reset_color"><b>6 Invalid VK Access Token</b> — Передан недействительный маркер доступа VK. Ошибка возникает в методе <b>auth.signin</b></span>
        </li>
        <li class="dev_li">
            <span class="dev_reset_color"><b>7 Too many requests to creation token</b> — Слишком много запросов на создание маркера доступа. Пользователь не может создавать маркер доступа в течение 30 секунд.</span>
        </li>
    </ul>
</div>