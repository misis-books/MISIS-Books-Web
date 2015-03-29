var Dev = {
    parent_node: null,
    cur_page: null,
    last_hash: null,
    open: function(element, resource) {
        this.parent_node = document.getElementById('left_container');
        if (resource && /[a-zA-Z_.-]/i.test(resource) && this.cur_page != resource) {
            this.reselect(element);
            this.changeHistory(resource);
            this.last_hash = Materials.search.generateSid(2);
            var a = {};
                a.url = 'http://' + App.host + "/dev/dev.content";
                a.data = {"resource": resource, hash: this.last_hash};
            Ajax.simple_get(a, Dev.handlerInsert);
            this.cur_page = resource;
        }
    },
    handlerInsert: function(response) {
        var hash = response.match(/^([0-9a-f]+)!hash!/i)[1];
        hash === Dev.last_hash && (Dev.parent_node.innerHTML = response.replace(/^[0-9a-f]+!hash!/i, ''));
        var layer = document.getElementById('dev_layer');
        layer.style.display = 'none';
    },
    reselect: function(element) {
        var layer = document.getElementById('dev_layer');
        layer.style.display = 'block';
        $("#index_element,#about_element,#apiusage_element,#auth_element,#m_list_element,#m_pop_element,#m_search_element,#m_categories_element").removeClass('dev_select');
        $(element).addClass('dev_select');
    },
    changeHistory:function(resource) {
        var curLoc = null, title;
        switch (resource) {
            case 'description':
                curLoc = 'dev';
                title = 'MISIS Books API';
                break;
            case 'api_requests':
                curLoc = 'dev/api_requests';
                title = 'Запросы к API | MISIS Books API';
                break;
            case 'auth.register':
                curLoc = 'dev/auth.register';
                title = 'Авторизация приложения | MISIS Books API';
                break;
            case 'methods_list':
                curLoc = 'dev/methods_list';
                title = 'Список методов | MISIS Books API';
                break;
            case 'materials.search':
                curLoc = 'dev/materials.search';
                title = 'Запросы к API | MISIS Books API';
                break;
            case 'materials.getpopular':
                curLoc = 'dev/materials.getpopular';
                title = 'Запросы к API | MISIS Books API';
                break;
            case 'materials.getCategories':
                curLoc = 'dev/materials.getCategories';
                title = 'Запросы к API | MISIS Books API';
                break;
            case 'apiusage':
                curLoc = 'dev/apiusage';
                title = 'Работа с API | MISIS Books API';
                break;
        }
        resource && window.history.pushState && window.history.pushState({}, '', '/' + curLoc);
        document.title = title
    }
};