<div class="content__not-auth__layer" style="padding-bottom: 100px;">
    <div class="content__not-auth">
        <div class="content__not-auth__image">
            <img draggable="false" src="/st/img/vk-integrate_6_x2.png" style="width: 100%">
        </div>
        <div class="content__not-auth__description">
            <span>Чтобы оплатить подписку, авторизируйтесь через ВКонтакте</span>
        </div>
        <div class="content__not-auth__login-button">
            <div style="display: inline-block; padding: 10px; background-color: #F3F3F3;">
                <button onclick="App.signIn(this); VK.Auth.login(App.authInfo, 65536);" class="button-flat-shadow__white">Войти</button>
            </div>
        </div>
    </div>
</div>