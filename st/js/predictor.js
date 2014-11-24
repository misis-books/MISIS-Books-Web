function strip_tags(str){ return str.replace(/<\/?[^>]+>/gi, ''); }

var _jsonp = function(a) {
    var b = 0, c = a.document, e = c.documentElement;
    return function(g, h) {
        var d = 'frame_' + b++, f = c.createElement("script");
        a[d] = function() {
            try {
                delete a[d];
            } catch (b) {
                a[d] = null;
            }
            e.removeChild(f);
            h.apply(this, arguments);
        };
        e.insertBefore(f, e.lastChild).src = g + "=" + d;
    };
}(this);

JSONP = {
    _jsonp:_jsonp,
    query:function(a, b) {
        this._jsonp(a.url + "?" + JSONP.dataEncode(a.data), b);
    },
    dataEncode:function(a) {
        var b = "";
        if (a) {
            for (var c in a) {
                a.hasOwnProperty(c) && (b += "&" + c.toString() + "=" + encodeURIComponent(a[c]));
            }
            if ("&" == b.charAt(0)) {
                return b.substring(1, b.length);
            }
        }
        return b;
    }
};

function addEvent(elem, type, handler) {
    if (elem.addEventListener) {
        elem.addEventListener(type, handler, false);
    } else {
        elem.attachEvent('on' + type, handler);
    }
}

var Ya = {
    Predictor: {
        focus: false,
        url: 'http://predictor.yandex.net/suggest.json/complete',
        data: {
            lang: 'ru',
            sid: '8a765dab',
            q: '',
            limit: 3,
            callback: 'invoke'
        },
        setSearch: function(text, focus, scroll) {
            Search.setSearch(text, focus, scroll);
            var input = document.getElementById('input_text');
            input.blur();
        },
        createRequest: function(text) {
            this.data.q = text.trim();
            JSONP.query({
                url:    this.url,
                data:   this.data
            }, Ya.Predictor.requestHandler);
        },
        requestHandler: function(res) {
            var row = '<a href="/?q={1}" id="predictor_{4}" class="b-predictor" onmousedown="return false;" onclick="Ya.Predictor.setSearch(\'{2}\', !1, !0); return false;">\
                <span class="b-predictor__advice b-predictor__font-regular">{3}</span>\
                </a>';

            var input = document.getElementById('input_text'),
                predictor_layer = document.getElementById('predictor-layer');

            var ins;
            var max_str_length = 50;
            if (res.text && res.text.length > 0 && res.pos < 0) {
                $(predictor_layer).show(0);
                $(predictor_layer).empty();
                for (var el in res.text) {
                    var res_text = strip_tags(input.value.trim());
                    res_text = res_text.slice(0, res.pos) + res.text[el];

                    var res_text_ins = res_text;
                    if (res_text.length > max_str_length) {
                        res_text_ins = '...' + res_text.substring(res_text.length - max_str_length, res_text.length);
                    }
                    ins = row
                        .replace('{1}', encodeURIComponent(res_text))
                        .replace('{2}', encodeURIComponent(res_text))
                        .replace('{3}', res_text_ins)
                        .replace('{4}', parseInt(el) + 1);
                    $(predictor_layer).append(ins);
                }
            } else {
                var text = strip_tags(input.value.trim());
                var res_text_ins = text;
                if (text.length > max_str_length) {
                    res_text_ins = '...' + text.substring(text.length - max_str_length, text.length);
                }
                ins = row
                    .replace('{1}', encodeURIComponent(text))
                    .replace('{2}', encodeURIComponent(text))
                    .replace('{3}', res_text_ins)
                    .replace('{4}', 1);
                $(predictor_layer).empty().append(ins);
            }
        }
    }
};

$(document).ready(function() {
    var input = document.getElementById('input_text'),
        predictor_layer = document.getElementById('predictor-layer');

    setInterval(function() {
        if (!input.value.trim().length) {
            $(predictor_layer).hide(0);
        }
    }, 100);

    addEvent(input, 'focus', function(e) {
        if (strip_tags(e.target.value.trim()).length > 0) {
            $(predictor_layer).show(0);
            Ya.Predictor.focus = true;
            Ya.Predictor.createRequest(strip_tags(e.target.value.trim()));
        }
    });

    addEvent(input, 'click', function(e) {
        if (strip_tags(e.target.value.trim()).length > 0) {
            $(predictor_layer).show(0);
            Ya.Predictor.createRequest(strip_tags(e.target.value.trim()));
        }
    });

    addEvent(input, 'blur', function(e) {
        Ya.Predictor.focus = false;
        $(predictor_layer).hide(0);
    });

    addEvent(input, 'keyup', function(e) {
        if (strip_tags(e.target.value.trim()).length > 0) {
            $(predictor_layer).show(0);
            Ya.Predictor.createRequest(strip_tags(e.target.value.trim()));
        } else {
            $(predictor_layer).hide(0);
        }
    });

    addEvent(input, 'keydown', function(e) {
        if (e.keyCode == 39 || e.keyCode == 13) {
            $('#predictor_1').click();
            input.focus();
        }
    });
});