var CheckRequests = {
    timestamp_l: null,
    timestamp_r: null,
    interval: null,
    base_url: Settings.base_url + "/admin/upd",
    _init: function(date) {
        this.timestamp_r = date;
        //http://twosphere.ru/admin/methods/check_upd.php
        this.setListener(400);
    },
    setListener: function(milliseconds) {
        milliseconds = milliseconds || 1500;
        if (this.interval == null) {
            this.interval = setInterval(function() {
                CheckRequests.get();
            }, milliseconds)
        }
    },
    get: function() {
        var data = {};
            data.data = {timestamp_l: this.timestamp_l, timestamp_r: this.timestamp_r};
            data.url = this.base_url;
        Ajax.get(data, CheckRequests.handler);
        this.timestamp_l = this.timestamp_r;
        this.timestamp_r = parseInt(Date.now()/1000);
    },
    handler: function(response) {
        var container = $(".container_update");
        if (response.items_count != 0) {
            for (var i = response.items.length - 1; i >= 0; --i) {
                var layout = "<div class='adm_req_result_item' style='display: none;'><div class='adm_req_q'><b>" + response.items[i].query + "</b></div><div class='adm_req_api'>ID: " + response.items[i].id + " | Api: " + response.items[i].api + " | Date: " + response.items[i].date + "</div></div>";
                container.prepend(layout);
                $(".adm_req_result_item:first").toggle('slide').css({background: "#dd4b4f"});
            }
        }
    }
};

$(document).ready(function() {
    CheckRequests._init(parseInt(Date.now()/1000));
});