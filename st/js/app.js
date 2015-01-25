//DOM
function ge(el) {
    return (typeof el == 'string' || typeof el == 'number') ? document.getElementById(el) : el;
}
function geByTag(searchTag, node) {
    node = ge(node) || document;
    return node.getElementsByTagName(searchTag);
}
function geByTag1(searchTag, node) {
    node = ge(node) || document;
    return node.querySelector && node.querySelector(searchTag) || geByTag(searchTag, node)[0];
}

function clone(obj){
    if(obj == null || typeof(obj) != 'object')
        return obj;
    var temp = new obj.constructor();
    for(var key in obj)
        temp[key] = clone(obj[key]);
    return temp;
}

function addEvent(elem, evType, fn) {
    if (elem.addEventListener) {
        elem.addEventListener(evType, fn, false);
    }
    else if (elem.attachEvent) {
        elem.attachEvent('on' + evType, fn)
    }
    else {
        elem['on' + evType] = fn
    }
}

window.cf = (function(doc) {
    var frag = doc.createDocumentFragment(),
        elem = doc.createElement('div'),
        range = doc.createRange && doc.createRange();
    frag.appendChild(elem);
    range && range.selectNodeContents(elem);

    return range && range.createContextualFragment ?
        function (html) {
            if (!html) return doc.createDocumentFragment();
            return range.createContextualFragment(html);
        } :
        function (html) {
            if (!html) return doc.createDocumentFragment();
            elem.innerHTML = html;
            var frag = doc.createDocumentFragment();
            while (elem.firstChild) {
                frag.appendChild(elem.firstChild);
            }
            return frag;
        };
})(document);

function cancelEvent(event) {
    event = (event || window.event);
    if (!event) return false;
    while (event.originalEvent) {
        event = event.originalEvent;
    }
    if (event.preventDefault) event.preventDefault();
    if (event.stopPropagation) event.stopPropagation();
    event.cancelBubble = true;
    event.returnValue = false;
    return false;
}

function cancelBubbling(evt) {
    evt = evt || window.event;
    evt.cancelBubble = true;
}

window.refresh = function(location) {
    window.location.href = location || window.location.href.replace(/(\#\w+)$/gi, '');
};

$(document).ready(function() {
    if (!Modernizr.flexbox || !Modernizr.rgba) {
        alert("Ваш браузер не поддерживается");
    }

    User.data = $.parseJSON(_User_json);
    User.isAuth = !!User.data.view_name;

    $('.header-search__status').on('click', function(){$('#search_input').focus()[0].setSelectionRange(0, 0);});

    var input = $('#search_input');

    var toggle_search_callback = function(e) {
        $('.header-layout').removeClass('header-inactive').addClass('header-active');

        var cur_tab = $('.header-fave__toggle').hasClass('header-fave__toggled') ? 'fave' : 'search';

        $('#title_icon_fave').hide(0);
        $('#title_icon_search').hide(0);

        $('#title_icon_back').show(0).on('click', function(e) {
            input[0].value = '';
            input[0].blur();
            $('#title_icon_fave').hide(0);
            $('#title_icon_search').hide(0);
            $('.header-search__category-dropdown__layer').hide(0);
            Page.controller.switcher($('.header-fave__toggle').hasClass('header-fave__toggled') ? 'fave' : 'popular');
            if ($('.header-fave__toggle').hasClass('header-fave__toggled')) {
                $('#title_icon_search').hide(0);
                $('#title_icon_fave').show(0);
            } else {
                $('#title_icon_fave').hide(0);
                $('#title_icon_search').show(0);
            }

            $('#title_icon_back').hide(0).off('click');
            $('.header-layout').removeClass('header-active').addClass('header-inactive');
        });

        var hide_search_panel = function(e) {
            if (input.val().length) {
                return;
            }
            if (cur_tab == 'fave') {
                $('#title_icon_search').hide(0);
                $('#title_icon_fave').show(0);
            } else {
                $('#title_icon_fave').hide(0);
                $('#title_icon_search').show(0);
            }
            $('#title_icon_back').hide(0).off('click');
            $('.header-layout').removeClass('header-active').addClass('header-inactive');

            $('.content-layer').off('click.input_unfocus');
        };

        $(".content-layer").on('click.input_unfocus', hide_search_panel);
        input.on('keydown.esc', function(e) {
            if (e.keyCode == 27) {
                hide_search_panel();
                e.target.blur();
            }
            $(this).off('keydown.esc');
        });
    };

    input && input.on('keydown', toggle_search_callback).on('focus', toggle_search_callback).on('keyup', function(e) {
        if (e.target.value.trim().length > 0) {
            Page.controller.switcher('search');
        } else {
            Page.controller.switcher($('.header-fave__toggle').hasClass('header-fave__toggled') ? 'fave' : 'popular');
        }
    });

    $('.header-fave__toggle').on('click', function(e) {
        $('.header-fave__toggle').toggleClass('header-fave__toggled');
        if ($('.header-fave__toggle').hasClass('header-fave__toggled')) {
            Page.controller.switcher('fave');
            $('.header-fave__toggle-element')[0].title = 'Нажмите, чтобы скрыть избранное';
            $('#title_icon_search').hide(0);
            $('#title_icon_fave').show(0);
            $('.content-spin-loader').css({opacity: 1, display: 'block'});
        } else {
            $('.header-fave__toggle-element')[0].title = 'Нажмите, чтобы показать избранное';
            Page.controller.switcher((ge('search_input').value.length > 0 ? 'search' : 'popular'));
            $('#title_icon_search').show(0);
            $('#title_icon_fave').hide(0);
        }
    });

    $('.header-profile__link').on('click.toggle', function(e) {
        var layer = $('#profile_info_layer');
        if (layer.is(":visible")) {
            layer.hide(0);
        } else {
            layer.show(0);
        }
        $(".content-layer").off('click.toggle_profile').on('click.toggle_profile', function(e) {
            layer.hide(0);
            $(this).off('click.toggle_profile');
        });
    });

    $('.header-search__category-layer').on('click.toggleCategoryLayer', function(e) {
        var layer = $('.header-search__category-dropdown__layer');
        if (layer.is(":visible")) {
            layer.hide(0);
        } else {
            layer.show(0);
        }
        $(".content-layer").off('click.toggle_CategoryLayer').on('click.toggle_CategoryLayer', function(e) {
            layer.css({display: 'none'});
            $(this).off('click.toggle_CategoryLayer');
        });
    });

    var preview_layer = $('.preview-content__layer');
    setTimeout(function() {
        if (preview_layer.length) {
            preview_layer.css({height: $("body").innerHeight() * 0.6});
            preview_layer.css({opacity: 1});
            setTimeout(function() {
                $('.preview-content__image-layer').css({opacity: 1});
                $('.preview-content__text-layer').css({opacity: 1});
            }, 50);
            $('.header-layout').css({boxShadow: '0 0 4px rgba(0, 0, 0, 0.14),0 4px 8px rgba(0, 0, 0, 0.05)'});

            function getDocumentHeight() {
                return (document.body.scrollHeight > document.body.offsetHeight) ? document.body.scrollHeight:document.body.offsetHeight;
            }
            $(document).on("scroll", function(){
                var cur_scroll = $(window).scrollTop() + $("body").innerHeight();
                if (cur_scroll > getDocumentHeight() - 460) {
                    $('.btn-arrow').hide(100);
                } else {
                    $('.btn-arrow').show(100);
                }
            });
        }
    }, 500);

    $(window).resize(function() {
        if (preview_layer) {
            preview_layer.css({height: $("body").innerHeight() * 0.6});
        }
    });

    if (location.href.replace(/((\?|\#).*)$/i, '') == 'http://' + location.host + '/' && User.isAuth) {
        App.initTemplates([
            'material'
        ], function() {
            App.searchListener(20);
            App.insertPopular();
            App.actionListener();
        });
    }
    Page.updateTapEvent();
});

/*!
 * Copyright (c) Aleksandr Belov
 * Ajax Library v1.2
 */
var Ajax = {_init:function() {
    return this.createXmlHttpRequest();
}, createXmlHttpRequest:function() {
    var b;
    try {
        b = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (c) {
        try {
            b = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (a) {
            b = !1;
        }
    }
    b || "undefined" != typeof XMLHttpRequest && (b = new XMLHttpRequest);
    b || (location.href = "http://" + App.host + "/badbrowser");
    return b;
}, post:function(b, c) {
    var self = this;
    var a = Ajax._init();
    a && (a.open("POST", b.url, !0), a.setRequestHeader("X-Requested-With", "XMLHttpRequest"), a.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), a.send(Ajax.dataEncode(b.data)), a.onreadystatechange = function() {
        if (4 == a.readyState && 200 == a.status) {
            var b = $.parseJSON(a.responseText);
            c.call(self, b);
        }
    });
}, simple_get:function(b, c) {
    var self = this;
    var a = Ajax._init();
    a && (a.open("GET", b.url + "?" + Ajax.dataEncode(b.data), !0), a.setRequestHeader("X-Requested-With", "XMLHttpRequest"), a.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), a.send(), a.onreadystatechange = function() {
        4 == a.readyState && 200 == a.status && c.call(self, a.responseText);
    });
}, get:function(b, c) {
    var self = this;
    var a = Ajax._init();
    a && (a.open("GET", b.url + "?" + Ajax.dataEncode(b.data), !0), a.setRequestHeader("X-Requested-With", "XMLHttpRequest"), a.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), a.send(null), a.onreadystatechange = function() {
        if (4 == a.readyState && 200 == a.status) {
            var b = $.parseJSON(a.responseText);
            c.call(self, b);
        }
    });
}, dataEncode:function(b) {
    var c = "";
    if (b) {
        for (var a in b) {
            b.hasOwnProperty(a) && (c += "&" + a.toString() + "=" + encodeURIComponent(b[a]));
        }
        if ("&" == c.charAt(0)) {
            return c.substring(1, c.length);
        }
    }
    return c;
}};

/*!
 * Vk API
 */
window.vkAsyncInit = function() {
    VK.init({
        apiId: 4720039
    });
};

$(document).ready(function() {
    setTimeout(function() {
        var el = document.createElement("script");
        el.type = "text/javascript";
        el.src = "//vk.com/js/api/openapi.js";
        el.async = true;
        document.getElementById("vk_api_transport").appendChild(el);
    }, 0);
});

/*!
 * MISIS Books 4.0
 */
var App = {
    host: location.hostname,
    logout: function() {
        var obj = {
            data: {}
        };
        obj.url = "http://" + App.host + "/auth/logout";

        Ajax.post(obj, function(e){window.refresh()});
    },
    authInfo: function(response) {
        try {
            console.log(response);
            if (response.session) {
                var post_query_params = response.session.user;
                var obj = {
                    data: post_query_params
                };
                obj.url = "http://" + App.host + "/auth/login";

                var photo_callback = function (res) {
                    try {
                        var user = res.response[0];
                        if (res.response) {
                            obj.data.photo = (user.photo_200 !== undefined ? user.photo_200 : user.photo_max);
                        }
                    } catch (err) {
                        console.log(err);
                    }
                    console.log(obj);
                    Ajax.post(obj, function (e) {
                        window.refresh();
                    });
                };
                VK.Api.call('users.get', {
                    ids: response.session.user.id,
                    fields: 'photo_200,photo_max'
                }, photo_callback);
            }
        } catch (err) {
            alert("Авторизация не прошла, попробуйте снова.");
        }
    },
    signIn: function(target) {
        target.disabled = true;
        target.innerHTML = 'Идет вход...';
    },
    searchListener: function(milliseconds) {
        milliseconds = milliseconds || 100;
        if (Materials && Materials.search) {
            Materials.search.searchController.__init(milliseconds);
        }
    },
    insertFaves: function() {
        Fave.getFaves.updateInvoke();
    },
    initTemplates: function(templates, callbackSync) {
        Page.templates.count = templates.length;
        Page.templates.callbackSync = callbackSync;
        for (var el in templates) {
            if (!templates.hasOwnProperty(el)) continue;
            Page.templates.add(templates[el]);
        }
    },
    insertPopular: function() {
        Materials.getPopular.initInvoke();
    },
    actionListener: function() {
        Page.controller.__init();
    }
};

var Materials = {
    methods: {
        search: {
            url: 'http://' + App.host + '/methods/materials.search',
            data: {
                count: 10,
                offset: 0,
                q: '',
                fields: 'compact,photo_small',
                category: 1,
                sid: ''
            }
        },
        getPopular: {
            url: 'http://' + App.host + '/methods/materials.getPopular',
            data: {
                count: 20,
                offset: 0,
                fields: 'compact,photo_small',
                category: 1
            }
        },
        getPopularForWeek: {
            url: 'http://' + App.host + '/methods/materials.getPopularForWeek',
            data: {
                count: 10,
                offset: 0,
                fields: 'compact,photo_small,count_dl_week',
                category: 1
            }
        },
        getCategories: {
            url: 'http://' + App.host + '/methods/materials.getCategories',
            data: {}
        }
    },
    search: {
        category: 1,
        cache_params: null,
        invoke: function(params, callback) {
            if (!Page.templates.isSync) {
                return;
            }
            SpinLoader.activate();
            params.sid = this.generateSid(2);
            var obj = clone(Materials.methods.search);
            for (var el in params) {
                if (!params.hasOwnProperty(el) || typeof el == 'function') continue;
                obj.data[el] = params[el];
            }
            this.cache_params = obj.data;
            Ajax.post.call(this, obj, callback);
        },
        generateSid: function(length) {
            length = length || 2;
            for (var res = "", i = 0; i < length; ++i) {
                res += Math.floor(Math.random() * 1e8).toString(16);
            }
            return res;
        },
        searchController: {
            interval: null,
            __init: function(interval) {
                if (this.interval != null) {
                    clearInterval(this.interval);
                }
                var input = ge('search_input');
                if (Materials.search.cache_params == null) {
                    Materials.search.cache_params = clone(Materials.methods.search.data);
                }
                this.interval = setInterval(function() {
                    if (input.value.trim() != Materials.search.cache_params.q.trim()) {
                        if (!input.value.trim().length) {
                            Page.controller.switcher($('.header-fave__toggle').hasClass('header-fave__toggled') ? 'fave' : 'popular');
                        }
                        var params = clone(Materials.methods.search.data);
                        params.q = input.value.trim();
                        params.category = Materials.search.category;
                        Materials.search.invoke(params, function(res) {
                            var self = this;
                            setTimeout(function() {Page.insertSearchResult.call(self, res)}, 0); /* new thread */
                        });
                    }
                }, interval);
            },
            immediatelyInvoke: function() {
                var input = ge('search_input');
                if (Materials.search.cache_params == null) {
                    Materials.search.cache_params = clone(Materials.methods.search.data);
                }
                var params = clone(Materials.methods.search.data);
                params.q = input.value.trim();
                params.category = Materials.search.category;
                Materials.search.invoke(params, function(res) {
                    var self = this;
                    setTimeout(function() {Page.insertSearchResult.call(self, res)}, 0);
                });
            },
            offsetInvoke: function(offset) {
                var input = ge('search_input');
                if (Materials.search.cache_params == null) {
                    Materials.search.cache_params = clone(Materials.methods.search.data);
                }
                var params = clone(Materials.methods.search.data);
                params.q = input.value.trim();
                params.category = Materials.search.category;
                params.offset = offset;
                Materials.search.invoke(params, function(res) {
                    var self = this;
                    setTimeout(function() {Page.insertSearchResultToEnd.call(self, res)}, 0);
                });
            }
        }
    },
    getPopular: {
        cache_params: null,
        invoke: function(params, callback) {
            SpinLoader.activate();
            var obj = clone(Materials.methods.getPopular);
            for (var el in params) {
                if (!params.hasOwnProperty(el) || typeof el == 'function') continue;
                obj.data[el] = params[el];
            }
            this.cache_params = obj.data;
            Ajax.post.call(this, obj, callback);
        },
        initInvoke: function() {
            this.invoke({category: Materials.search.category}, function(res){
                $('.content-spin-loader').animate({opacity: 0}, 100, function() {
                    $(this).hide(0);
                });
                setTimeout(function() {Page.insertPopular(res)}, 0);
            });
        },
        offsetInvoke: function(offset) {
            if (Materials.getPopular.cache_params == null) {
                Materials.getPopular.cache_params = clone(Materials.methods.getPopular.data);
            }
            var params = clone(Materials.methods.getPopular.data);
            params.category = Materials.search.category;
            params.offset = offset;
            this.invoke(params, function(res) {
                setTimeout(function() {Page.insertPopularToEnd(res)}, 0);
            });
        }
    },
    getPopularForWeek: {
        cache_params: null,
        invoke: function(params, callback) {
            var obj = clone(Materials.methods.getPopularForWeek);
            for (var el in params) {
                if (!params.hasOwnProperty(el) || typeof el == 'function') continue;
                obj.data[el] = params[el];
            }
            this.cache_params = obj.data;
            Ajax.post.call(this, obj, callback);
        }
    },
    getCategories: {
        cache_params: null,
        invoke: function(params, callback) {
            this.cache_params = params;
            var obj = clone(Materials.methods.getCategories);
            for (var el in params) {
                if (!params.hasOwnProperty(el) || typeof el == 'function') continue;
                obj.data[el] = params[el];
            }
            this.cache_params = obj.data;
            Ajax.post.call(this, obj, callback);
        }
    }
};

var Fave = {
    methods: {
        getFaves: {
            url: 'http://' + App.host + '/methods/fave.getDocuments',
            data: {
                count: 10,
                offset: 0,
                category: 1,
                fields: 'compact,photo_small'
            }
        },
        addFave: {
            url: 'http://' + App.host + '/methods/fave.addDocument',
            data: {
                edition_id: null
            }
        },
        deleteFave: {
            url: 'http://' + App.host + '/methods/fave.deleteDocument',
            data: {
                edition_id: null
            }
        },
        deleteAllFaves: {
            url: 'http://' + App.host + '/methods/fave.deleteAllDocuments',
            data: {}
        }
    },
    getFaves: {
        cache_result: null,
        cache_params: null,
        invoke: function(params, callback) {
            var obj = clone(Fave.methods.getFaves);
            for (var el in params) {
                if (!params.hasOwnProperty(el) || typeof el == 'function') continue;
                obj.data[el] = params[el];
            }
            this.cache_params = obj.data;
            Ajax.post.call(this, obj, callback);
        },
        updateInvoke: function() {
            this.invoke({category: Materials.search.category}, function(res) {
                if (res.status == "OK") {
                    this.cache_result = res;
                    setTimeout(function() {Page.insertFaves(res)}, 0);
                }
            });
        },
        offsetInvoke: function(offset) {
            this.invoke({offset: offset, category: Materials.search.category}, function(res) {
                if (res.status == "OK") {
                    this.cache_result.items.push(res.items);
                    setTimeout(function() {Page.insertFavesToEnd(res)}, 0);
                }
            });
        }
    },
    addFave: {
        cache_params: null,
        invoke: function(params, callback) {
            var obj = clone(Fave.methods.addFave);
            for (var el in params) {
                if (!params.hasOwnProperty(el) || typeof el == 'function') continue;
                obj.data[el] = params[el];
            }
            this.cache_params = obj.data;
            Ajax.post.call(this, obj, callback);
        }
    },
    deleteFave: {
        cache_params: null,
        invoke: function(params, callback) {
            var obj = clone(Fave.methods.deleteFave);
            for (var el in params) {
                if (!params.hasOwnProperty(el) || typeof el == 'function') continue;
                obj.data[el] = params[el];
            }
            this.cache_params = obj.data;
            Ajax.post.call(this, obj, callback);
        }
    },
    deleteAllFaves: {
        cache_params: null,
        invoke: function(params, callback) {
            var obj = clone(Fave.methods.deleteAllFaves);
            for (var el in params) {
                if (!params.hasOwnProperty(el) || typeof el == 'function') continue;
                obj.data[el] = params[el];
            }
            this.cache_params = obj.data;
            Ajax.post.call(this, obj, callback);
        }
    },
    isExists: function(edition_id) {
        var cache_result = Fave.getFaves.cache_result;
        if (cache_result) {
            var fave_items = cache_result.items;
            for (var el in fave_items) {
                if (!fave_items.hasOwnProperty(el)) continue;
                if (fave_items[el].id == edition_id) {
                    return !0;
                }
            }
        }
        return false;
    }
};

var Page = {
    templates: {
        author: {
            pattern: '<div class="search-result__author" onclick="Page.setSearch(\'{{ author }}\', true, true); return cancelEvent(event);">{{ author }}</div>',
            data: {}
        },
        expand: {
            pattern: '<div class="content-search__result-item expand-layer__{{ method }}" onclick=\'Page.expandResults(this, \"\{\{ method \}\}\", \{\{ offset \}\})\'><div class="expand-layer__inner">Еще {{ count }} из {{ remain_count }}</div></div>',
            data: {}
        },
        empty_block: {
            pattern: '<div class="content-search__result-item not_found-layer__search"><div class="not_found-layer__inner">{{ text }}</div></div>',
            data: {}
        },
        isSync: false,
        countSync: 0,
        count: 0,
        callbackSync: function(){},
        add: function(template_name) {
            var obj = {
                url: "http://" + App.host + "/static/templates.get",
                data: {
                    name: template_name
                }
            };
            Ajax.simple_get.call(this, obj, function(res) {
                this[template_name] = {
                    pattern: res,
                    data: {}
                };
                this.countSync++;
                if (this.count == this.countSync) {
                    this.isSync = true;
                    this.callbackSync();
                    console.log("Templates has been loaded successfully. Callback invoked.");
                }
            });
        }
    },
    dataBindTemplate: function(template_name, data) {
        if (this.templates[template_name] === undefined) {
            return "";
        }
        this.templates[template_name].data = data = data || {};
        var pattern = this.templates[template_name].pattern;
        var reg_exp;
        for (var el in data) {
            if (!data.hasOwnProperty(el) || typeof el == 'function') continue;
            reg_exp = new RegExp('{{\\s' + el +'\\s}}', 'gi');
            pattern = pattern.replace(reg_exp, data[el]);
        }
        return pattern;
    },
    insertSearchResult: function(res) {
        // this - search context
        if (Page.templates.material && res.status == "OK"
            && this.cache_params && this.cache_params.sid == res.sid) {
            SpinLoader.deactivate();
            var insertAuthors = function(authors) {
                if (!authors.length) {
                    return "";
                }
                var result = "";
                for (var el in authors) {
                    if (!authors.hasOwnProperty(el)) continue;
                    result += Page.dataBindTemplate('author', {
                        author: authors[el]
                    })
                }
                return result;
            };

            $('.expand-layer__search').remove();
            var layer = $('.search-items');
            layer.empty();
            var lang_cl_label = ge('lang_cl');
            lang_cl_label.style.display = res.lang_cl ? 'block' : 'none';
            var items = res.items, el;
            for (el in items) {
                if (!items.hasOwnProperty(el)) continue;
                layer.append(
                    Page.dataBindTemplate('material', {
                        name: Page.resultHighLight(items[el].name, ge('search_input').value, res.lang_cl),
                        category_id: items[el].category.id,
                        category_name: items[el].category.name,
                        fave_added: items[el].fave ? 'red' : '',
                        edition_id: items[el].id,
                        authors: insertAuthors(items[el].authors),
                        href: items[el].download_url,
                        photo_small: items[el].photo_small
                    })
                );
            }
            var res_count = Materials.search.cache_params.offset + res.items_count;
            if (res_count == Materials.search.cache_params.offset + Materials.search.cache_params.count
                && res_count < res.all_items_count) {
                layer.append(Page.dataBindTemplate('expand', {
                    method: 'search',
                    offset: Materials.search.cache_params.offset + res.items_count,
                    remain_count: res.all_items_count - res_count,
                    count: Math.min(Materials.search.cache_params.count, res.all_items_count - res_count)
                }));
            }

            if (!res.found && res.q.length) {
                layer.append(Page.dataBindTemplate('empty_block', {
                    text: 'По вашему запросу ничего не найдено.'
                }));
            }
            Page.updateTapEvent();
        }
    },
    insertSearchResultToEnd: function(res) {
        if (Page.templates.material && res.status == "OK" && this.cache_params && this.cache_params.sid == res.sid) {
            SpinLoader.deactivate();
            var insertAuthors = function(authors) {
                if (!authors.length) {
                    return "";
                }
                var result = "";
                for (var el in authors) {
                    if (!authors.hasOwnProperty(el)) continue;
                    result += Page.dataBindTemplate('author', {
                        author: authors[el]
                    })
                }
                return result;
            };

            $('.expand-layer__search').remove();
            var layer = $('.search-items');
            var items = res.items, el;
            for (el in items) {
                if (!items.hasOwnProperty(el)) continue;
                layer.append(
                    Page.dataBindTemplate('material', {
                        name: Page.resultHighLight(items[el].name, ge('search_input').value, res.lang_cl),
                        category_id: items[el].category.id,
                        category_name: items[el].category.name,
                        fave_added: items[el].fave ? 'red' : '',
                        edition_id: items[el].id,
                        authors: insertAuthors(items[el].authors),
                        href: items[el].download_url,
                        photo_small: items[el].photo_small
                    })
                );
            }
            var res_count = Materials.search.cache_params.offset + res.items_count;
            if (res_count == Materials.search.cache_params.offset + Materials.search.cache_params.count
                && res_count < res.all_items_count) {
                layer.append(Page.dataBindTemplate('expand', {
                    method: 'search',
                    offset: Materials.search.cache_params.offset + res.items_count,
                    remain_count: res.all_items_count - res_count,
                    count: Math.min(Materials.search.cache_params.count, res.all_items_count - res_count)
                }));
            }
            Page.updateTapEvent();
        }
    },
    insertFaves: function(res) {
        if (Page.templates.material && res.status == "OK") {
            $('.content-spin-loader').animate({opacity: 0}, 100, function() {
                $(this).hide(0);
            });
            var insertAuthors = function(authors) {
                if (!authors.length) {
                    return "";
                }
                var result = "";
                for (var el in authors) {
                    if (!authors.hasOwnProperty(el)) continue;
                    result += Page.dataBindTemplate('author', {
                        author: authors[el]
                    })
                }
                return result;
            };

            var layer = $('.fave-items');
            layer.empty();
            var items = res.items, el;
            for (el in items) {
                if (!items.hasOwnProperty(el)) continue;
                layer.append(
                    Page.dataBindTemplate('material', {
                        name: items[el].name,
                        category_id: items[el].category.id,
                        category_name: items[el].category.name,
                        fave_added: items[el].fave ? 'red' : '',
                        edition_id: items[el].id,
                        authors: insertAuthors(items[el].authors),
                        href: items[el].download_url,
                        photo_small: items[el].photo_small
                    })
                );
            }

            var res_count = Fave.getFaves.cache_params.offset + res.items_count;
            if (res_count == Fave.getFaves.cache_params.offset + Fave.getFaves.cache_params.count
                && res_count < res.all_items_count) {
                layer.append(Page.dataBindTemplate('expand', {
                    method: 'fave',
                    offset: Fave.getFaves.cache_params.offset + res.items_count,
                    remain_count: res.all_items_count - res_count,
                    count: Math.min(Fave.getFaves.cache_params.count, res.all_items_count - res_count)
                }));
            }

            if (!res.items_count) {
                layer.append(Page.dataBindTemplate('empty_block', {
                    text: 'Нет элементов.'
                }));
            }
            Page.updateTapEvent();
        }
    },
    insertFavesToEnd: function(res) {
        if (Page.templates.material && res.status == "OK") {
            var insertAuthors = function(authors) {
                if (!authors.length) {
                    return "";
                }
                var result = "";
                for (var el in authors) {
                    if (!authors.hasOwnProperty(el)) continue;
                    result += Page.dataBindTemplate('author', {
                        author: authors[el]
                    })
                }
                return result;
            };

            $('.expand-layer__fave').remove();
            var layer = $('.fave-items');
            var items = res.items, el;
            for (el in items) {
                if (!items.hasOwnProperty(el)) continue;
                layer.append(
                    Page.dataBindTemplate('material', {
                        name: items[el].name,
                        category_id: items[el].category.id,
                        category_name: items[el].category.name,
                        fave_added: items[el].fave ? 'red' : '',
                        edition_id: items[el].id,
                        authors: insertAuthors(items[el].authors),
                        href: items[el].download_url,
                        photo_small: items[el].photo_small
                    })
                );
            }

            var res_count = Fave.getFaves.cache_params.offset + res.items_count;
            if (res_count == Fave.getFaves.cache_params.offset + Fave.getFaves.cache_params.count
                && res_count < res.all_items_count) {
                layer.append(Page.dataBindTemplate('expand', {
                    method: 'fave',
                    offset: Fave.getFaves.cache_params.offset + res.items_count,
                    remain_count: res.all_items_count - res_count,
                    count: Math.min(Fave.getFaves.cache_params.count, res.all_items_count - res_count)
                }));
            }
            Page.updateTapEvent();
        }
    },
    insertPopular: function(res) {
        if (Page.templates.material && res.status == "OK") {
            SpinLoader.deactivate();
            var insertAuthors = function(authors) {
                if (!authors.length) {
                    return "";
                }
                var result = "";
                for (var el in authors) {
                    if (!authors.hasOwnProperty(el)) continue;
                    result += Page.dataBindTemplate('author', {
                        author: authors[el]
                    })
                }
                return result;
            };

            var layer = $('.popular-items');
            layer.empty();
            var items = res.items, el;
            for (el in items) {
                if (!items.hasOwnProperty(el)) continue;
                layer.append(
                    Page.dataBindTemplate('material', {
                        name: items[el].name,
                        category_id: items[el].category.id,
                        category_name: items[el].category.name,
                        fave_added: items[el].fave ? 'red' : '',
                        edition_id: items[el].id,
                        authors: insertAuthors(items[el].authors),
                        href: items[el].download_url,
                        photo_small: items[el].photo_small
                    })
                );
            }

            var res_count = Materials.getPopular.cache_params.offset + res.items_count;
            if (res_count == Materials.getPopular.cache_params.offset + Materials.getPopular.cache_params.count
                && res_count < res.all_items_count) {
                layer.append(Page.dataBindTemplate('expand', {
                    method: 'popular',
                    offset: Materials.getPopular.cache_params.offset + res.items_count,
                    remain_count: res.all_items_count - res_count,
                    count: Math.min(Materials.getPopular.cache_params.count, res.all_items_count - res_count)
                }));
            }
            Page.updateTapEvent();
        }
    },
    insertPopularToEnd: function(res) {
        if (Page.templates.material && res.status == "OK") {
            SpinLoader.deactivate();
            var insertAuthors = function(authors) {
                if (!authors.length) {
                    return "";
                }
                var result = "";
                for (var el in authors) {
                    if (!authors.hasOwnProperty(el)) continue;
                    result += Page.dataBindTemplate('author', {
                        author: authors[el]
                    })
                }
                return result;
            };

            $('.expand-layer__popular').remove();
            var layer = $('.popular-items');
            var items = res.items, el;
            for (el in items) {
                if (!items.hasOwnProperty(el)) continue;
                layer.append(
                    Page.dataBindTemplate('material', {
                        name: items[el].name,
                        category_id: items[el].category.id,
                        category_name: items[el].category.name,
                        fave_added: items[el].fave ? 'red' : '',
                        edition_id: items[el].id,
                        authors: insertAuthors(items[el].authors),
                        href: items[el].download_url,
                        photo_small: items[el].photo_small
                    })
                );
            }

            var res_count = Materials.getPopular.cache_params.offset + res.items_count;
            if (res_count == Materials.getPopular.cache_params.offset + Materials.getPopular.cache_params.count
                && res_count < res.all_items_count) {
                layer.append(Page.dataBindTemplate('expand', {
                    method: 'popular',
                    offset: Materials.getPopular.cache_params.offset + res.items_count,
                    remain_count: res.all_items_count - res_count,
                    count: Math.min(Materials.getPopular.cache_params.count, res.all_items_count - res_count)
                }));
            }
            Page.updateTapEvent();
        }
    },
    updateFaveButtons: function(res) {
        var items = res.items, fave_button;
        $('.search-result__fave-add-button').each(function() {
            var className = this.className,
                classEdition = className.match(/fave\-(\d+)/i),
                edition_id = classEdition[1];
            if (Fave.isExists(edition_id)) {
                $(this).addClass('red');
            } else {
                $(this).removeClass('red');
            }
        });
    },
    toggleFave: function(target, edition_id) {
        if ($(target).hasClass('red')) {
            if (/^\d+$/i.test(edition_id.toString())) {
                Fave.deleteFave.invoke({edition_id: edition_id}, function(res) {
                    if (res.result) {
                        $('.fave-' + edition_id).removeClass('red');
                        console.log(Page.controller.cur_state);
                        if (Page.controller.cur_state != 'fave') {
                            Fave.getFaves.updateInvoke();
                        }
                    }
                })
            }
        } else {
            if (/^\d+$/i.test(edition_id.toString())) {
                Fave.addFave.invoke({edition_id: edition_id}, function(res) {
                    if (res.result) {
                        $('.fave-' + edition_id).addClass('red');
                        console.log(Page.controller.cur_state);
                        if (Page.controller.cur_state != 'fave') {
                            Fave.getFaves.updateInvoke();
                        }
                    }
                })
            }
        }
    },
    setSearch: function(q, scroll_flag, focus_flag) {
        var input = ge('search_input');
        input.value = q.trim();
        Page.controller.switcher('search');
        if (scroll_flag) {
            $.scrollTo('0', 100);
        }
        if (focus_flag) {
            input.focus();
            input.setSelectionRange && input.setSelectionRange(input.value.length, input.value.length);
        }
    },
    controller: {
        cur_state: 'popular',
        isInit: false,
        data: {
            input: null,
            switcher: null
        },
        __init: function() {
            if (!this.isInit) {
                this.data.input = ge('search_input');
                this.data.switcher = $('.header-fave__toggle')[0];
                this.isInit = true;
            }
        },
        switcher: function(cur_name) {
            var popular = $('.content-search__popular-layer'),
                search = $('.content-search__search-layer'),
                fave = $('.content-search__fave-layer');
            Page.controller.cur_state = cur_name;
            switch (cur_name) {
                case 'popular':
                    popular.show(0);
                    search.hide(0);
                    fave.hide(0);
                    break;
                case 'fave':
                    fave.show(0);
                    popular.hide(0);
                    search.hide(0);
                    Fave.getFaves.updateInvoke();
                    break;
                case 'search':
                    search.show(0);
                    popular.hide(0);
                    fave.hide(0);
                    break;
                default:
                    popular.show(0);
                    search.hide(0);
                    fave.hide(0);
                    break;
            }
        }
    },
    expandResults: function(target, method, offset) {
        if ($(target).hasClass('expanded')) {
            return;
        }
        $(target).addClass('expanded');
        switch (method) {
            case 'search':
                Materials.search.searchController.offsetInvoke(offset);
                break;
            case 'popular':
                Materials.getPopular.offsetInvoke(offset);
                break;
            case 'fave':
                Fave.getFaves.offsetInvoke(offset);
                break;
            default:
                break;
        }
    },
    materialClick: {
        cur: null,
        className: '.content-search__result-item',
        focusClass: 'element-focus',
        click: function(target) {
            if (Page.materialClick.cur != null) {
                $(Page.materialClick.cur).find('.search-result__header').off('click.header');
                $(Page.materialClick.cur).removeClass('element-focus');
                $('.content-layer').off('click.body_focus');
            }
            Page.materialClick.cur = target;
            $(target).addClass(this.focusClass);
            $(target).find('.search-result__header').on('click.header', function(e) {
                $(target).removeClass('element-focus');
                $(this).off('click.header');
                cancelEvent(e);
            });
            $.scrollTo(target, 200, {offset: {top:-100} });
        },
        stopEvent: function(target) {
            $('.content-layer').on('click.body_focus', function(e) {
                $(target).removeClass('element-focus');
                $('.search-result__header').off('click.header');
                $(this).off('click.body_focus');
            });
        }
    },
    selectCategory: function(target, category) {
        setTimeout(function() {
            $('.header-search__category-dropdown__layer').hide(0);
        }, 100);
        $('#current_category')
            .removeClass('category-' + Materials.search.category + '__category')
            .addClass('category-' + category + '__category');
        Materials.search.category = category;
        switch (Page.controller.cur_state) {
            case 'popular':
                Materials.getPopular.initInvoke();
                break;
            case 'search':
                Materials.search.searchController.immediatelyInvoke();
                Materials.getPopular.initInvoke();
                break;
            case 'fave':
                Fave.getFaves.updateInvoke();
                Materials.getPopular.initInvoke();
                break;
            default:
                break;
        }
    },
    updateTapEvent: function(elements) {
        elements = elements || [{
            el: '.button-block,.payment-button__text,.payment-button__subtext,.payment-selection__yd,.payment-selection__bc,.payment-selection__payeer',
            size: '_x1',
            hidden: true
        }, {
            el: '.ic-search__white',
            size: '_x1',
            hidden: true
        }, {
            el: '.expand-layer__popular,.expand-layer__search,.expand-layer__fave',
            size: '_x2',
            hidden: true
        }, {
            el: '.header-search__category__name',
            size: '_x1',
            hidden: true
        }];
        for (var el in elements) {
            if (!elements.hasOwnProperty(el)) continue;
            if (elements[el].size == '_x1') {
                $(elements[el].el).off('mousedown.tap').on('mousedown.tap', function (e) {
                    var x = e.originalEvent.offsetX == undefined ? e.originalEvent.layerX : e.originalEvent.offsetX,
                        y = e.originalEvent.offsetY == undefined ? e.originalEvent.layerY : e.originalEvent.offsetY;
                    if (x == 0 && y == 0) {
                        return;
                    }
                    $(this).css({position: 'relative'});
                    if (this.nodeName == 'A' || this.nodeName == 'BUTTON') {
                        $(this).css({overflow: 'hidden'});
                    }

                    $(this).append('<div style="top: ' + y + 'px; left: ' + x + 'px" class="tap__circle tap__animate_x1"><div>');
                    setTimeout(function () {
                        $('.tap__circle').remove();
                    }, 400);
                });
            } else {
                $(elements[el].el).off('mousedown.tap').on('mousedown.tap', function (e) {
                    console.log(e);
                    var x = e.originalEvent.offsetX == undefined ? e.originalEvent.layerX : e.originalEvent.offsetX,
                        y = e.originalEvent.offsetY == undefined ? e.originalEvent.layerY : e.originalEvent.offsetY;
                    if (x == 0 && y == 0) {
                        return;
                    }
                    $(this).css({position: 'relative'});
                    if (this.nodeName == 'A' || this.nodeName == 'BUTTON') {
                        $(this).css({overflow: 'hidden'});
                    }

                    $(this).append('<div style="top: ' + y + 'px; left: ' + x + 'px" class="tap__circle tap__animate_x2"><div>');
                    setTimeout(function () {
                        $('.tap__circle').remove();
                    }, 400);
                });
            }
        }
    },
    resultHighLight: function(e, a, b, c) {
        c = c || "rgba(251, 91, 94, 0.15)";
        b && (a = Page.invertLanguage(a));
        a = a.replace(/[^A-Za-z\u0410-\u042f\u0430-\u044f0-9 ]/gi, " ").trim().replace(/\s+/g, " ").split(" ");
        var d = "";
        for (b in a) {
            a.hasOwnProperty(b) && (3 > a.length || 1 != a[b].length) && (d += a[b] + "|");
        }
        pattern = "(" + d.replace(/\|$/i, "") + ")";
        console.log(pattern);
        try {
            return e.replace(new RegExp(pattern, "gi"), '<span class="rounded" style="background-color: ' + c + ';">$1</span>');
        } catch (f) {
            return e;
        }
    },
    invertLanguage: function(a) {
        return function(a) {
            var b = {q:"\u0439", "]":"\u044a", "'":"\u044d", w:"\u0446", a:"\u0444", z:"\u044f", e:"\u0443", s:"\u044b", x:"\u0447", r:"\u043a", d:"\u0432", c:"\u0441", t:"\u0435", f:"\u0430", v:"\u043c", y:"\u043d", g:"\u043f", b:"\u0438", u:"\u0433", h:"\u0440", n:"\u0442", i:"\u0448", j:"\u043e", m:"\u044c", o:"\u0449", k:"\u043b", ",":"\u0431", p:"\u0437", l:"\u0434", ".":"\u044e", "[":"\u0445", ";":"\u0436", "/":".", "`":"\u0451", "\u0439":"q", "\u0444":"a", "\u044f":"z", "\u0446":"w", "\u044b":"s",
                "\u0447":"x", "\u0443":"e", "\u0432":"d", "\u0441":"c", "\u043a":"r", "\u0430":"f", "\u043c":"v", "\u0435":"t", "\u043f":"g", "\u0438":"b", "\u043d":"y", "\u0440":"h", "\u0442":"n", "\u0433":"u", "\u043e":"j", "\u044c":"m", "\u0448":"i", "\u043b":"k", "\u0431":",", "\u0449":"o", "\u0434":"l", "\u044e":".", "\u0445":"[", "\u044d":"'", "\u0451":"`", "\u044a":"]"};
            return a.replace(/(.)/g, function(a) {
                return b[a] || a;
            });
        }(a);
    }
};

var SpinLoader = {
    selectorWrapper: '#spinSearch',
    selector: '.search-spin-layer',
    timeout_default: 500,
    timeout: null,
    activate: function() {
        if (this.timeout != null) {
            clearTimeout(this.timeout);
        }
        this.start();
    },
    deactivate: function(timeout) {
        timeout = timeout || this.timeout_default;
        if (this.timeout != null) {
            clearTimeout(this.timeout);
        }
        this.timeout = setTimeout(function() {
            SpinLoader.stop();
        }, timeout);
    },
    start: function() {
        ge('searchIconInput').style.display = 'none';
        $(this.selectorWrapper).show(0);
        $(this.selector).removeClass('stopped');
    },
    stop: function() {
        $(this.selector).addClass('stopped');
        setTimeout(function() {
            if (SpinLoader.timeout != null) {
                $(SpinLoader.selectorWrapper).hide(0);
                ge('searchIconInput').style.display = 'block';
                clearTimeout(SpinLoader.timeout);
            }
        }, 150);
    }
};