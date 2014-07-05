var Dev = {
    parent_node: null,
    cur_page: null,
    last_hash: null,
    open: function(element, resource) {
        this.parent_node = document.getElementById('left_container');
        if (resource && /[a-zA-Z_.-]/i.test(resource) && this.cur_page != resource) {
            this.reselect(element);
            this.changeHistory(resource);
            this.last_hash = Search.createHash(16);
            var a = {};
                a.url = Settings.base_url+"/dev/dev.content";
                a.data = {"resource": resource, hash: this.last_hash};
            Ajax.simple_get(a, Dev.handlerInsert);
            this.cur_page = resource;
        }
    },
    handlerInsert: function(response) {
        var hash = response.match(/^(\w{16})!hash!/i)[1];
        hash === Dev.last_hash && (Dev.parent_node.innerHTML = response.replace(/^\w{16}!hash!/i, ''));
        var spin = document.getElementById('menu_spin'), layer = document.getElementById('dev_layer');
        spin && $(spin).remove();
        layer.style.display = 'none';
    },
    reselect: function(element) {
        var spin = document.getElementById('menu_spin'), layer = document.getElementById('dev_layer');
        spin && spin.remove();
        layer.style.display = 'block';
        $("#index_element,#about_element,#apiusage_element,#auth_element,#m_list_element,#m_pop_element,#m_search_element,#m_categories_element").removeClass('dev_select');
        $(element).addClass('dev_select');
        var wrapper = document.createElement('div');
            wrapper.className = 'dev_menu_spin';
            wrapper.id = 'menu_spin';
        (new Spinner({
            lines: 5,
            length: 0,
            width: 5,
            radius: 5,
            corners: 1,
            rotate: 53,
            direction: 1,
            color: "#fff",
            speed: 2.2,
            trail: 10,
            shadow: !1,
            hwaccel: !1,
            className: "spinner",
            zIndex: 2E4,
            top: "0",
            left: "0"
        })).spin(wrapper);
        wrapper.childNodes[0].style.top = 0; wrapper.childNodes[0].style.left = 0;
        element.appendChild(wrapper);
    },
    changeHistory:function(resource) {
        var curLoc = null, title;
        switch (resource) {
            case 'description':
                curLoc = 'dev';
                title = 'API Twosphere';
                break;
            case 'api_requests':
                curLoc = 'dev/api_requests';
                title = 'Запросы к API | API Twosphere';
                break;
            case 'auth.register':
                curLoc = 'dev/auth.register';
                title = 'Авторизация приложения | API Twosphere';
                break;
            case 'methods_list':
                curLoc = 'dev/methods_list';
                title = 'Список методов | API Twosphere';
                break;
            case 'materials.search':
                curLoc = 'dev/materials.search';
                title = 'Запросы к API | API Twosphere';
                break;
            case 'materials.getpopular':
                curLoc = 'dev/materials.getpopular';
                title = 'Запросы к API | API Twosphere';
                break;
            case 'materials.getCategories':
                curLoc = 'dev/materials.getCategories';
                title = 'Запросы к API | API Twosphere';
                break;
            case 'apiusage':
                curLoc = 'dev/apiusage';
                title = 'Работа с API | API Twosphere';
                break;
        }
        resource && window.history.pushState && window.history.pushState({}, '', '/' + curLoc);
        document.title = title
    }
};