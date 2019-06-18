$(document).ready(function(){
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


