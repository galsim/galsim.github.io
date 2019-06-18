jQuery(function($){
    function updDelivr() {

        $.ajax({
            url: '/dostavka/',
            success: function (data) {
                $('.card-block__price').html($(data).find('.card-block__price').html());
                setTimeout(updDelivr, 1000);
            }
        });
    }
    updDelivr();

    $('.add_to_cart_button.ajax_add_to_cart').click(function (e) {
        var proto = $(this).closest('.product__bar').find('.product__bar__img');
        var x = proto.offset().left;
        var y = proto.offset().top;
        var w = proto.outerWidth();
        var h = proto.outerHeight();
        var clone = proto.clone().addClass('fly-to-cart').css({
            'position': 'absolute',
            'top' : y+'px',
            'left' : x+'px',
            'width' : w+'px',
            'height' : h+'px',
            'z-index' : '1000',
            // 'border' : '1px solid #F00',
        }).appendTo('body').animate({
            opacity: 0.25,
            left: $('.card-block__img').offset().left+'px',
            top: $('.card-block__img').offset().top+'px',
            width: '20px',
            height: '20px',
        }, 1200, function() {
            console.log('finished');
            $($('.fly-to-cart')[0]).remove();
        });
        console.log();
    });

});



$(document).ready(function() {
    $('.card-block__img a').on('click', function(event) {
        event.preventDefault();

        $('.card-block__minicart').addClass('basket__popup');


        return false;
    });


    $('.minicart-headr span').on('click', function() {
        $('.card-block__minicart').removeClass('basket__popup');
    });

    $('.checkout-button').on('click', function() {
        window.location.href('http://rplv.creative-services.ru/oformlenie-zakaza/');
    });


});