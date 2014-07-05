<div class="dev_title">
    <a name="api_auth" class="dev_name_anchor"></a>
    Авторизация приложения
    <a title="Ссылка на раздел" href="#api_auth" class="dev_anchor" onclick="Page.openLinkPopup('copy_link', 'http://twosphere.ru/dev/auth.register#api_auth', 'Ссылка на раздел', 400)"></a>
</div>
<p class="dev_p">
    Для доступа к методам API, не требующим аутентификации пользователя, необходимо получить специальный ключ доступа <b>access_token</b>. В соответствии со спецификацией протокола <a target="_blank" class="dev_link_ext" href="http://tools.ietf.org/html/draft-ietf-oauth-v2-31">OAuth 2.0</a> механизм прямой авторизации сервера приложения без участия пользователя называется <b>Client Credentials Flow</b>.
    <br><br>
    На данный момент ключ используется для контроля частоты запросов к API и сбора статистики. В дальнейшем, могут появиться новые методы.
    <br><br>
    Для получения ключа необходимо выполнить запрос на данный URL.
</p>
<div class="dev_blockquote">
    http://twosphere.ru/api/<b>auth.register</b>
</div>
<p class="dev_p">
    В результате выполнения запроса будет возвращён сгенерированный access_token, при помощи которого можно осуществлять вызовы к <a class="dev_link" href="/dev/methods_list">методам API</a>.
</p>
<div class="dev_blockquote">
    <pre style="margin: 0; padding: 0;">{<span class="json_key">"response"</span>: {
    <span class="json_key">"access_token"</span>: <span class="json_value">"5c62ed37fc776ff3a8b285f13aadd94ac0213bf9ec6b1b42a6a8100b00f40942"</span>
}}</pre>
</div>