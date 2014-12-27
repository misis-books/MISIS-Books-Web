/**
 * Created by IPRIT on 08.12.2014.
 */
$(document).ready(function() {
    Help.setProgressWidth(__Progress.value);
    Help.startTimer(__Timer.remaining_time);
});

var Help = {};

!(function(obj) {

    'use strict';

    obj.setProgressWidth = function(width) {
        $('.b-progress__value').css({width: width + '%', opacity: 1});
    };

    obj.startTimer = function(time) {
        if (time <= 0) {
            return;
        }
        var date = __Timer.date;
        var timer = $('.b-timer__value');
        var interval = setInterval(function() {
            if (!date[3]) {
                date[3] = 59;
                if (!date[2]) {
                    date[2] = 59;
                    if (!date[1]) {
                        date[1] = 23;
                        date[0] && date[0]--;
                    } else {
                        date[1]--;
                    }
                } else {
                    date[2]--;
                }
            } else {
                date[3]--;
            }
            for (var sum = 0, i = 0; i < date.length; ++i) {
                sum += date[i];
            }
            !sum && clearInterval(interval);
            timer.text(date[0] + " дн. " + date[1] + " ч. " + date[2] + " мин. " + date[3] + " сек.");
        }, 1000);
    };
})(Help);