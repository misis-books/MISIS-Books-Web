<div class="dev_title">
    <a name="api_auth" class="dev_name_anchor"></a>
    Авторизация приложения
</div>
<div class="dev_p">
    Для доступа к методам API MISIS Books, требующим аутентификации пользователя, необходимо получить специальный ключ доступа <b>access_token</b> —
    в данном описании API назовем формально этот ключ доступа mb_access_token.
    <br><br>
    Для получения этого ключа необходима авторизация через <b>ВКонтакте</b>. Авторизация через ВКонтакте предполагает получение <b>access_token</b> — формально будем называть vk_access_token.
    Подробнее можно почитать <a target="_blank" href="http://vk.com/dev/auth_mobile" class="dev_link">здесь</a>.
    <br>
    Окно авторизации через ВКонтакте выглядит следующим образом:
    <div class="dev-img__layer">
        <img class="dev-img" src="http://cs621729.vk.me/v621729809/16382/Vz03uwA5pXM.jpg">
    </div>
    <br><br>
    После получения <b>vk_access_token</b>, можно получить <b>mb_access_token</b>, отправив запрос на этот URL:
</div>
<div class="dev_blockquote">
    http://twosphere.ru/api/<b>auth.signin</b>
</div>
<p class="dev_p">
    Параметром этого запроса является единственное поле <b>vk_access_token</b>.
    <br>
    Пример реального запроса:
</p>
<div class="dev_blockquote">
    http://twosphere.ru/api/<b>auth.signin</b>?vk_access_token=<span class="json_value">7df901c53c139a3d723ef871e06f2af0da082962827a81c32b19675134c110bd4d1fc4dddb6073dafc034</span>
</div>
<p class="dev_p">
    В результате выполнения запроса будет возвращён сгенерированный <b>mb_access_token</b> (access_token), при помощи которого можно осуществлять вызовы к <a class="dev_link" href="/dev/methods_list">методам API</a>.
</p>
<div class="dev_blockquote">
    <pre style="margin: 0; padding: 0;">{<span class="json_key">"response"</span>: {
    <span class="json_key">"access_token"</span>: <span class="json_value">"5c62ed37fc776ff3a8b285f13aadd94ac0213bf9ec6b1b42a6a8100b00f40942"</span>
}}</pre>
</div>