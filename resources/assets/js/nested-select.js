'use-strict';

/**
 * Plugin de select customizado
 */
jQuery.fn.extend({

    nestedSelect: function (parentId) {

        // Referência do DOM
        var $self = null;
        var $select = $(this);

        // Referência do DOM criado dinamicamente
        var $selecorBlock = null;
        var $optionsBlock = null;

        // Informações do Seletor
        var placeholder = $select.data('placeholder');
        var url = $select.data('url');

        var myOptions = null;

        var modalIsOpen = false;

        if (!parentId) {
            parentId = null;
        }

        //Inicializa o plugin
        init();

        /**
         * Observa evento de clique do seletor
         */
        $self.on('click', '.selector-block', function () {
            toggleDropDown();
        });

        /**
         * Observa evento de clique do seletor
         */
        $self.on('click', '.drop-down-options .item .label', function () {

            var $this = $(this);
            var $li = $this.closest('li');

            selectItem($li.data('id'), $li.data('name'));
            closeDropdown($(this).closest('.nested-select'));

            //Dispara evento de seleção de item
            $select.trigger('item-selected', $li.data('id'));

        });

        /**
         * Fecha a edição atráves do trigger
         */
        $self.on('blur', '.drop-down-options .options-area ul li .edit input', function () {

            var $li = $(this).closest('li');

            if (isEdit($li)) {
                toggleEdit($li);
            } else {
                insert($(this));
            }

        }).on('keypress', '.drop-down-options .options-area ul li .edit input', function (event) {

            if (event.code == "NumpadEnter" || event.code == "Enter") {
                //Chamar a função de inserir ou banco ou editar

                event.preventDefault();
                // var $li = $(this).closest('li');

                // // Verificação para confirmar se é Edição ou Inserção
                // if (isEdit($li)) {
                //     // EDIÇÂO
                    $(this).trigger('blur');

                // } else {
                //     // INSERÇÂO
                //     insert($(this));
                // }

                return false;
            }

        });

        /**
         * Observa evento de clique do seletor
         */
        $self.on('keyup', '.drop-down-options .search-area input', function () {

            var searchKey = $(this).val();
            var $options = $select.find('option');
            var options = [];

            $options.each(function (i, opt) {

                var $opt = $(opt);
                var name = $opt.text();

                if (name.toLocaleLowerCase().indexOf(searchKey.toLocaleLowerCase()) > -1) {

                    options.push({
                        name: $opt.text(),
                        id: $opt.val() || null
                    });

                }

            });

            if (options) {

                var newUl = buildOptionsBlock(options).find('ul');
                $self.find('.options-area ul').detach();
                $self.find('.drop-down-options .options-area').append(newUl);
            }

        });

        /**
         * Observa evento de inserção de novo item
         */
        $self.on('click', '.drop-down-options .add-item', function () {

            var newData = {
                id: -1,
                name: ""
            };

            var newItem = buildItem(newData, true);
            $optionsBlock.find('ul').append(newItem);
            var $li = $optionsBlock.find(newItem);
            $li.find('.actions .update').trigger('click');

        });

        /**
         * Seleciona todo o conteudo dentro do input quando esse receber o foco
         */
        $self.on('focus', ".drop-down-options .options-area .edit input[type='text']", function () {
            $(this).select();
        });

        /**
                 * Edita um elemento
                 * @param {*} id
                 * @param {*} $item
                 */
        function toggleEdit($li) {

            var $editBlock = $(`
            <div class='edit hide'>
                 <input maxlength="50" type='text' value='${$li.data('name')}'>
            </div>`);

            var $input = $li.find('.edit input') || $editBlock.find('input');
            var $label = $li.find('.label');

            //Habilita edição do item atual
            var enableEdit = () => {

                $li.addClass('is-editing');
                $li.prepend($editBlock);

                $label.addClass('hide');
                $editBlock.removeClass('hide');

                $editBlock.find('input').trigger('focus');

            };

            //Desabilita edição do item atual
            var disableEdit = () => {

                $li.removeClass('is-editing');

                $label.removeClass('hide');
                $editBlock.addClass('hide');
                $li.find('.edit').detach();
            };

            if (!$li.hasClass('is-editing')) {

                enableEdit();

            } else {

                if ($input.val() && $li.data('name') != $input.val()) {

                    $li.wait();

                    $.ajax({
                        url: url,
                        method: 'put',
                        data: {
                            _token: window.Laravel.csrfToken,
                            name: $input.val(),
                            id: $li.data('id')
                        }

                    }).done((response) => {

                        $label.text($input.val());
                        $li.data('name', $input.val());

                        $select.find(`option[value="${response.id}"`).text($input.val());

                        disableEdit();

                        $li.closeWait();

                    }).catch((err) => {
                        disableEdit();
                        $alert.error(err.responseText);
                        $li.closeWait();
                    });

                } else {
                    disableEdit();
                }

            }

        }

        /**
         * Observa evento de clique na edição
         */
        $self.on('click', '.drop-down-options .item .actions .update', function (e) {

            var $li = $(this).closest('li');

            toggleEdit($li);
            $li.find('.edit input').trigger('click');

        });

        /**
         * Observa o click na opção para remoção do elemento
         */
        $self.on('click', '.drop-down-options .item .actions .remove', function () {

            if (!modalIsOpen) {

                modalIsOpen = true;

                var $this = $(this);
                var $li = $this.closest('li');
                var id = $li.data('id');

                // Verifica se o dado possui id
                if (id && id > 0) {

                    var question = 'Essa é uma ação <b>irreversível</b>.<br/>Tem certeza que deseja continuar?';

                    dialog.prompt(question, function (result) {

                        if (result == true) {

                            categoryDelete(id).then((result) => {
                                
                                $alert.success(result);

                                $select.find('option').each((index, opt) => {

                                    var $opt = $(opt);
                                    if ($opt.val() == id) {
                                        $opt.detach();
                                    } else if (!$opt.val()) {
                                        $opt.attr('selected', true);
                                    }

                                });

                                $selecorBlock.find('.label').html(placeholder);
                                $select.parent().nextAll('.nested-select').remove();
                                toggleDropDown();
                                $li.detach();

                            }).catch((err) => {
                                console.log('AQUII');
                                console.log($alert);
                                $alert.error(err);
                            });

                            $self.closest('.modalside-content').animate({
                                scrollTop: $self.closest('.modalside-content').offset()
                            }, 300);
                        }
                    });

                } else {
                    // Caso não haja id o elemento foi removido ou ele ainda não foi inserido no banco
                    $li.detach();
                }

                modalIsOpen = false;

            }

        });

        function init() {

            myOptions = loadOptions(); // Armazena opções inseridas no seletor

            $selecorBlock = buildSelectorBlock(placeholder);
            $optionsBlock = buildOptionsBlock(myOptions);

            var $nestedSelect = $(`<div class="nested-select"></div>`);
            $select.wrap($nestedSelect);

            $self = $select.parent();
            $self.append($selecorBlock, $optionsBlock);

        }

        /**
         * Seleciona um determinado item
         * @param {*} value
         * @param {*} text
         */
        function selectItem(value, text) {

            $select.find('option').each((index, opt) => {

                var $opt = $(opt);

                if ($opt.val() == value) {
                    $opt.attr('selected', true);
                } else {
                    $opt.attr('selected', false);
                }
            });

            // Altera valor atual do seletor

            if (!value) {

                if (placeholder) {
                    $selecorBlock.find('.label').html($select.data('placeholder'));
                }

            } else {
                $selecorBlock.find('.label').text(text);
            }

        }

        /**
         * Apresenta ou esconde bloco drop down
         *
         * @param {*} open
         */
        function toggleDropDown() {

            if (!$self.hasClass('opened')) {

                $self.parent().find('.nested-select').each(function () {
                    closeDropdown($(this));
                });

                $self.addClass('opened');

            } else {
                closeDropdown($self);
            }
        }

        /**
         * Fecha o dropdown
         * @param {*} $elem
         */
        function closeDropdown($elem) {

            $elem.find('.search-area input').val('');
            $elem.removeClass('opened');
        }

        /**
         * Cria elemento html para o seletor
         *
         * @param {*} placeHolder
         * @returns
         */
        function buildSelectorBlock(placeHolder) {

            var selectorBlock = $(`
                <div class="selector-block">
                    <div class='label'>${placeHolder}</div>
                    <div class='icon'>
                        <i class="fa fa-caret-down closed" aria-hidden="true"></i>
                        <i class="fa fa-caret-up opened" aria-hidden="true"></i>
                    </div>
                </div>
            `);

            return selectorBlock;
        }

        /**
         * Cria elemento html para opções drop down
         *
         * @param {*} options
         * @returns
         */
        function buildOptionsBlock(options) {

            var $optionsBlock = $(`
                <div class='drop-down-options'>
                    <div class='search-area'>
                        <input maxlength="50" type='text' class="form-control" placholder='Pesquisar'>
                    </div>
                    <div class="options-area">
                        <ul></ul>
                    </div>
                </div>
            `);

            options.forEach(opt => {

                var withActions = false;

                // Verifica se item possui menu de ações
                if (opt.id) {
                    withActions = true;
                }

                var li = buildItem(opt, withActions);
                $optionsBlock.find('ul').append(li);
            });

            $optionsBlock.append(`
                <div class='add-item'>
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <div class='label'>
                        Adicionar novo item
                    </div>
                </div>
            `);

            return $optionsBlock;

        }

        /**
         * Cria um novo item option
         *
         * @param {*} item
         * @returns
         */
        function buildItem(item) {

            var li = $(`
            <li class='item' data-id='${item.id}' data-name='${item.name}'>
                <div class='label'>
                    ${item.name}
                </div>
            </li>`);

            // Verifica se item possui menu de ações
            if (item.id) {

                var actions = `
                <div class='actions'>
                    <i class="fa fa-pencil update" aria-hidden="true"></i>
                    <i class="fa fa-trash remove" aria-hidden="true"></i>
                </div>`;

                li.append(actions);
            }

            return li;
        }

        /**
         * Carrega lista de itens inseridos no seletor
         * @returns
         */
        function loadOptions() {

            var $options = $select.find('option');
            var options = [];

            $options.each(function (i, opt) {

                var $opt = $(opt);

                if ($opt.attr('selected')) {
                    placeholder = $opt.text();
                }

                options.push({
                    name: $opt.text(),
                    id: $opt.val() || null
                });

            });

            return options;
        }

        /**
                 * Insere uma nova categoria
                 * @param {*} data
                 * @param {*} parent
                 * @returns
                 */
        function insertCategory(data, parent) {

            return new Promise(function (resolve, reject) {

                if (data) {

                    $.ajax({
                        url: url,
                        method: 'post',
                        data: {
                            _token: window.Laravel.csrfToken,
                            name: data,
                            parent_id: parent
                        }

                    }).done((response) => {

                        resolve(response);
                    }).catch((err) => {

                        reject(err);
                    });

                } else {
                    reject('Dados inválidos');
                }

            });
        }

        /**
         * Insere elemento na lista após a chamada de criação de nova categoria
         * @param {*} $input
         */
        function insert($input) {

            var data = $input.val().trim();
            var $li = $input.closest('li');
            var $label = $li.find('.label');

            // Verifica se foi escrito algo no input caso sim inserta o elemento caso não.. remove o mesmo da lista
            if (data) {

                $li.wait();

                insertCategory(data, parentId).then((response) => {

                    $li.attr('data-id', response.id);
                    $li.attr('data-name', response.name);
                    $li.data('id', response.id);
                    $li.data('name', response.name);
                    $li.removeClass('is-editing');

                    $label.text(response.name);
                    $label.removeClass('hide');
                    $li.find('.edit').detach();
                    $select.append($(`<option value="${response.id}">${response.name}</option>`));
                    $li.closeWait();

                }).catch((err) => {

                    $li.closeWait();
                });

            } else {
                $li.detach();
            }

        }

        /**
         * Verifica se está inserindo ou editando um determinado dado
         * @param {*} $li
         */
        function isEdit($li) {

            var id = $li.data('id');

            if (id && id > 0) {
                return true;
            } else {
                return false;
            }

        }

        /**
                 * Função responsável por fazer o ajax e deletar a categoria informada
                 * @param {*} id
                 * @returns
                 */
        function categoryDelete(id) {

            return new Promise(function (resolve, reject) {

                $.ajax({
                    url: url,
                    method: 'delete',
                    data: {
                        _token: window.Laravel.csrfToken,
                        id: id
                    }

                }).done((item) => {
                    resolve(item);
                }).catch((err) => {
                    reject(err.responseText);
                });


            });

        }

    }

});
