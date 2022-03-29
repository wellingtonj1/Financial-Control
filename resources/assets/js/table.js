//Tabelas
$('.table.table-ordered tr th[data-column]').on(`click`, function () {

    var column = $(this).attr('data-column');

    if (column != undefined && column != '') {

        var params = urlParams();

        if (params['column'] == column) {
            params['sort'] = params['sort'] == 'asc' ? 'desc' : 'asc';
        } else {
            params['column'] = column;
            params['sort'] = 'asc';
        }

        var query = [];
        $.each(params, function (k, v) {
            query.push(k + '=' + v);
        });

        var location = window.location;
        var url = location.origin + location.pathname + '?' + query.join('&');

        document.location = url;
    }

}).each(function () {

    var params = urlParams();

    if (params['column'] !== undefined && $(this).attr('data-column') !== undefined && params['column'] == $(this).attr('data-column')) {

        $(this).closest('.table').find('tr th[data-column]').removeAttr('data-ordered');
        $(this).attr('data-ordered', params['sort']);
    }
});

$('.table-area .table-footer [name=row_limit]').on(`change`, function () {

    var rowLimit = $(this).val();
    var $formFilter = $('.table-area form[name=form-filter]');

    $formFilter.find('[name=row_limit]').val(rowLimit);
    $formFilter.submit();

});

/**
 * Obtêm os parâmetros da url
 * @returns
 */
function urlParams() {

	var result = {};
	var tmp = [];

	location.search.substr(1).split("&").forEach(function (item) {

		if (item) {
			tmp = item.split("=");
			result[tmp[0]] = decodeURIComponent(tmp[1]);
		}

	});

	return result;
}