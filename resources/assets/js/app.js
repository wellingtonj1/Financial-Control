// Toogle side modal
$('.open-side-nav').on('click', function () {
    $('.side-panel').css('width', '100%');
});

$('.btn-side-close').on('click', function () {
    $('.side-panel').css('width', '0');
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