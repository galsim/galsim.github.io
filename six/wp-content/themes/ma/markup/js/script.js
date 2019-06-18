jQuery(document).ready(function(){
	var xenu = 0;
	$('.mn').click(function() {
		if (xenu == 1) {
			$('.mn').removeClass('open');
			$('.overlay').fadeOut(300);
			$('.menupop').fadeOut(300, function() { xenu = 0; } );
		} else {
			$('.mn').addClass('open');
			$('.overlay').fadeIn(300);
			$('.menupop').fadeIn(300, function() { xenu = 1; } );
		}
	});
	$('.rules').click(function() {
		if ($('span', this).attr('class') == 'a') {
			$('span', this).removeClass();
		} else {
			$('span', this).addClass('a');
		}
	});
});
jQuery(document).ready(function () {
	price_prod = $(".woocommerce-Price-amount").contents().not($(".woocommerce-Price-amount").children()).text();
	cur_symbol = $(".woocommerce-Price-currencySymbol").text();
	quantity = $("input[name='quantity']").val();
	$('.minus').click(function () {
		if(quantity > 1){
			quantity--;
			end_price = price_prod*quantity;
			$(".woocommerce-Price-amount").text(end_price + " " + cur_symbol);
			$("input[name='quantity']").attr("value", quantity);

		}
	});
	$('.plus').click(function () {
		quantity++;
		end_price = price_prod*quantity;
		$(".woocommerce-Price-amount").text(end_price + " " + cur_symbol);
		$("input[name='quantity']").attr("value", quantity);

	});
});

jQuery(document).ready(function(){
	var obj = $('.gg_coll_back_to_new_style');
	var offset = obj.offset();
	var topOffset = offset.top;
	var marginTop = obj.css("marginTop");

	$(window).scroll(function() {
		var scrollTop = $(window).scrollTop();

		if (scrollTop >= topOffset){
			obj.attr('style','margin-top: 70px;position: fixed;margin-left: -80px !important');
		}

		if (scrollTop < topOffset){

			obj.css({
				marginTop: 20,
				position: 'relative',
				marginLeft: '',
			});
		}
	});
	$(window).scroll();
});

function LoadScript(action, id) {
	var xlink = '/sub.php?action=' + action + '&id=' + id;
	$.ajax({ url: xlink, context: document.body, dataType: 'script' });
}

function FormBooking(){
	var rs_name = $("#rs_name").val();
	var rs_email = $("#rs_email").val();
	var rs_phone = $("#rs_phone").val();
	var rs_date = $("#rs_date").val();
	var rs_guests = $("#rs_guests").val();
	var rs_comment = $("#rs_comment").val();
	
	if ($('.rules span').attr('class') == 'a') {
		if (rs_name && rs_phone && rs_email && rs_date && rs_guests) LoadScript("FormBooking", rs_name + ':::' + rs_email + ':::' + rs_phone + ':::' + rs_date + ':::' + rs_guests + ':::' + rs_comment);
		else $('.form-error').html('Необходимо заполнить все обязательные поля');
	}
	else {
		$('.form-error').html('Необходимо ваше согласие на обработку данных');
	}
}

function FormDelivery(){
	var rs_name = $("#rs_name").val();
	var rs_email = $("#rs_email").val();
	var rs_phone = $("#rs_phone").val();
	var rs_date = $("#rs_date").val();
	var rs_address = $("#rs_address").val();
	var rs_comment = $("#rs_comment").val();
	
	if ($('.rules span').attr('class') == 'a') {
		if (rs_name && rs_phone && rs_email && rs_date && rs_address) LoadScript("FormBooking", rs_name + ':::' + rs_email + ':::' + rs_phone + ':::' + rs_date + ':::' + rs_address + ':::' + rs_comment);
		else $('.form-error').html('Необходимо заполнить все обязательные поля');
	}
	else {
		$('.form-error').html('Необходимо ваше согласие на обработку данных');
	}
}





