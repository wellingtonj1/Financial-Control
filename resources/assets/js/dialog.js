var dialog = {
		
	success : function(message) {
		return bootbox.alert({
			title: "<div class='dl-title dl-success'><div>Sucesso</div></div>",
			message: message
		});
	},
	
	warning : function(message) { 
		return bootbox.alert({
			title: "<div class='dl-title dl-warning'>Atenção!</div>",
			message: message
		});
	}, 
	
	prompt: function(message, callback, title, size) {
		return bootbox.confirm({
			title: title || "<div class='dl-title dl-warning'>Atenção!</div>",
			message: message,
			callback: callback,
			size: size || 'small',
			buttons: {
                confirm: {
                    label: "Ok",
                    className: "btn-primary"
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-light"
                }
			}
		});
	},
	
	message: function(title, message, buttons) {
		
		return bootbox.dialog({
            title: title,
            message: message,
            buttons: buttons
		});
	},
	
	closeAll: function() {
		bootbox.hideAll();
	}
}
