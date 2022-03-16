'use strict';

function closeAlert($elem) {
	
	var $container = $('.alert-container');
	
	var delay = $container.length?$container.closest('.alert-container').attr('data-delay'):null;
	
	if (!delay || !$.isNumeric(delay)) {
		delay = 10000;
	}

	setTimeout(function() {
		
		$elem.removeClass('fadeInDown').addClass('fadeOutUp');
		
		setTimeout(function() {
			$elem.remove();
		}, 500);
	
	}, delay);
}

var $alert;

function getAlertContent(type, message) {
	
	var icons = {
		success: 'fa fa-check',
		info: 'fa fa-info',
		error: 'fa fa-times',
		warning: 'fa fa-warning'
	}
	
	var alertClass = {
		success: 'alert-success',
		info: 'alert-info',
		error: 'alert-danger',
		warning: 'alert-warning'
	}
	console.log(type);
	var html = 
	'<div class="alert animated fadeInDown">'+
		'<span class="icon"><i aria-hidden="true"></i></span>'+
		'<span class="message"></span>'+
		'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
	'</div>';
		
	var $elem = $(html);
	$elem.addClass(alertClass[type]);
	$elem.find("i").addClass(icons[type]);
	$elem.find(".message").html(message);
	
	return $elem;
}

$.fn.alert = function(type, message) {
	
	var $elem = getAlertContent(type, message);
	
	closeAlert($elem);
	$(this).append($elem);
	
};

(function() {
	
	$('.alert').each(function() {
		closeAlert($(this));

		// on click in button class close 
		$(this).find('.close').on('click', function() {
			$('.alert-container').closest('.alert-container').attr('data-delay', 0);
			closeAlert($(this).closest('.alert'));
			$('.alert-container').closest('.alert-container').attr('data-delay', 10000);
		});

	});
	
	var toggle = function(type, message, controlSpam) {
		
		var $container = $('.alert-container');
		var $activeAlerts = $container.find(".alert");
		
		if (!controlSpam || (controlSpam == true && $activeAlerts.length == 0)) {
			var $elem = getAlertContent(type, message);
			
			closeAlert($elem);
			$container.append($elem);
			$(window).scrollTop(0);
		}
	}
	
	$alert = {
			
		success: function(message, controlSpam) {
			toggle('success', message, controlSpam);
		},
		
		info: function(message, controlSpam) {
			toggle('info', message, controlSpam);
		},
		
		error: function(message, controlSpam) {
			toggle('error', message, controlSpam);
		},
		
		warning: function(message, controlSpam) {
			toggle('warning', message, controlSpam);
		}
	};
	
})();