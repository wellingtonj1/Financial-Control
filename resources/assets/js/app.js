// Toogle side modal
$('.open-side-nav').on('click', function () {
    $('.side-panel').css('width', '100%').css('transition', '3s');
    $('.wrapper').css('margin-left', '250px');

});

$('.btn-side-close').on('click', function () {
    $('.side-panel').css('width', '0').css('transition', '1s');;
    $('.wrapper').css('margin-left', '0');
});

// Logout
$('.logout').on(`click`, function () {
    
    var $form = $('<form method="POST" action="' + window.Laravel.url + '/logout' + '" hidden><input name="_token" value="' + window.Laravel.csrfToken + '"></form>');
    $('body').append($form);
    $form.submit();
});

// Delete button
var deleteModalOpen = false;
$("body").on('click', '.btn-delete', function (e) {

    e.preventDefault();

    if (!deleteModalOpen) {

        deleteModalOpen = true;
        var id = $(this).attr('data-id');
        var action = $(this).attr('data-url');
        var question = $(this).attr('data-question');

        if (id !== undefined && action !== undefined) {

            question = question !== undefined && question != '' ? question : 'Essa é uma ação <b>irreversível</b>.<br/>Tem certeza que deseja continuar?';

            dialog.prompt(question, function (result) {

                deleteModalOpen = false;
                if (result == true) {
                    var $form = $('<form method="POST" class="d-none" action="' + action + '"><input name="_method" value="DELETE"/><input name="_token" value="' + window.Laravel.csrfToken + '"><input name="id" value="' + id + '"/></form>');
                    $('body').append($form);
                    $form.submit();
                }
            });
        }
    }
});

$(".select2-select-js").select2({

    // multiple: true,
    placeholder: 'Realize a pesquisa',
    language: {
        noResults: function () {
            return "Nenhum resultado foi encontrado"
        }
    }
});

/**
 * Bloquear o elemento (abrir loading)
 */
$.fn.wait = function (_class) {

	// var animatedSvg = `<img src='${window.Laravel.url}/assets/img/loading.svg' >`;
	var animatedSvg = `<object data="${window.Laravel.url}/assets/img/loading.svg" type="image/svg+xml"></object>`;

	_class = _class ? (' ' + _class) : '';

	$(this).find('.loader-container').remove();
	// $(this).append('<div class="loader-container' + _class + '"><div class="loader"></div></div>');
	$(this).append(`<div class="loader-container${_class}"><div class="loader">${animatedSvg}<div class='label'>Carregando...</div></div></div>`);
	$(this).addClass('loader-active');
}

/**
 * Desbloquear o elemento (remover loading)
 */
$.fn.closeWait = function () {

	var $self = $(this);
	''
	$(this).children(".loader-container").fadeOut(300, function () {
		$self.removeClass('loader-active');
		$(this).remove();
	});
};