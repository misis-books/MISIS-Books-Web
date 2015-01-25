$(document).ready(function() {
    $('.payment-selection__yd,.payment-selection__bc,.payment-selection__payeer').on('click', function(e) {
        $('.payment-selection__yd,.payment-selection__bc,.payment-selection__payeer').removeClass('payment-selection__selected');
        $(this).addClass('payment-selection__selected');
        if ($(this).hasClass('payment-selection__payeer')) {
            return;
        }
        var type_input = document.getElementById('paymentType');
        if ($('.payment-selection__yd').hasClass('payment-selection__selected')) {
            type_input.value = 'PC';
        }
        if ($('.payment-selection__bc').hasClass('payment-selection__selected')) {
            type_input.value = 'AC';
        }
    });
});

Array.prototype.in_array = function(p_val) {
    for(var i = 0, l = this.length; i < l; i++)	{
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
};


var Payment = {
    pay: function(sum, target) {
        $('.payment-selection__selected').empty().append('<div style="margin-top: 30px;" class="content-spin__layer"><div class="search-spin-layer_"><div class="search-spin"></div></div></div>');
        if ($('.payment-selection__yd').hasClass('payment-selection__selected') || $('.payment-selection__bc').hasClass('payment-selection__selected')) {
            var form = $('#paymentForm')[0];
            var sum_input = document.getElementById('paymentSum');
            var sums = [33, 99, 299];
            sum_input.value = sums.in_array(sum) ? sum : 33;
            form.submit();
        }
        if ($('.payment-selection__payeer').hasClass('payment-selection__selected')) {
            var forms = [
                ge('payeerForm_1'),
                ge('payeerForm_3'),
                ge('payeerForm_12')
            ];
            var submit_buttons = [
                ge('payeerForm_submit_1'),
                ge('payeerForm_submit_3'),
                ge('payeerForm_submit_12')
            ];
            switch (sum) {
                case 33:
                    submit_buttons[0].click();
                    break;
                case 99:
                    submit_buttons[1].click();
                    break;
                case 299:
                    submit_buttons[2].click();
                    break;
                default:
                    submit_buttons[0].click();
                    break;
            }
        }
    }
};