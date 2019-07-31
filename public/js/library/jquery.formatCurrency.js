(function ($) {
    $.fn.formatCurrency = function (settings) {
        settings = $.extend({
            decimalSep: ',',
            thousandsSep: '.',
            digits: 2
        }, settings || {});

        removerMascara = function (o) {
            var str = o + '';
            //console.log('removerMascara: '+str.replace(/\D/g, ""));
            return str.replace(/\D/g, "");
        };

        stripLeadingZeros = function (o) {
            //console.log('stripLeadingZeros: '+o.replace(/^0+/g, ""));
            return o.replace(/^0+/g, "");
        };

        wearLeadingZeros = function (o) {
            for (var len = o.length, i = settings.digits; len <= i; len++) {
                o = "0" + o;
            }
            //console.log('wearLeadingZeros: '+o);
            return o;
        };

        formatMoney = function (s) {
            s = removerMascara(s);
            s = stripLeadingZeros(s);
            s = wearLeadingZeros(s);

            s = s.split("");

            for (var i = s.length - settings.digits; (i -= 3) > 0; s[i - 1] += settings.thousandsSep) ;
            var j = s.length - (settings.digits + 1);
            s[j] += settings.decimalSep;
            return s.join("");
        };

        return this.each(function () {
            $(this).css('textAlign', 'right');
            $(this).keypress(function (e) {
                //$(this).css('textAlign', 'right');
                if (!e)
                    var e = window.event;
                key = (e.keyCode) ? e.keyCode : e.which;

                if (key === 8 && !document.all) {
                    var s = $(this).val();
                    var s = s.substring(0, s.length - 1);
                    $(this).val(formatMoney(s));
                    return false;
                } else if (key > 47 && key < 58) {

                    //console.log(String.fromCharCode(key));
                    s = $(this).val();
                    //console.log('max: '+$(this).attr('maxLength'));
                    if (s.length >= $(this).attr('maxLength') - 1) {
                        //console.log('falso');
                        return false;
                    }
                    s += String.fromCharCode(key);

                    //console.log($(this).val());
                    $(this).val(formatMoney(s));
                }

                if (key > 30) {
                    if (e.preventDefault) { //standart browsers
                        e.preventDefault();
                    } else { // internet explorer
                        e.returnValue = false;
                    }
                }
            });
        });
    };
})(jQuery);

