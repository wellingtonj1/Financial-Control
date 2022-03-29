'use-strict';
$(function () {

    $('.payable').each(function () {

        // walk tds and find data-hover
        var $td = $(this).find('td[data-hover]');
        var ctrl = 0;

        $($td).on('click', function() {
        
            var $this = $(this);

            if (ctrl == 0) {
                $this.append($("<span> ("+ $this.data('hover') +") </span>"));
                ctrl = 1;
            } else {
                $td.find('span').remove();
                ctrl = 0;
            }

        });

    });

    $('.payable-create-edit').each(function () {

        var isSubmiting = false;
        console.log('target');
        //Create edit
        var $target = $(this);
        var $mainAlertContainer = $target.find('.alert-container');
        $mainAlertContainer.addClass('stand-by-alert-container');
        $mainAlertContainer.removeClass('alert-container');

        // TODO alert to nested select

        /**
         * Realiza o load da lib de select's customizados
         */
        $target.find('select[name="category_id[]"]').each(function () {

            var $select = $(this);
            var parent = $select.data('parent') || null;
            var lastId = $select.data('lastid');

            bindNewNestedSelect($select, parent);

            if (parent && lastId == parent) {
                $(this).trigger('item-selected', lastId);
            }

        });

        // Carrega select no create edit
        $target.find('.select2-select-js').select2();

        /**
             * Submits the form to inser a  bill to pay
             */
        $target.find('.to-pay-form').on('submit', function (e) {

            // e.preventDefault();

            // if (isSubmiting == false) {

            //     isSubmiting = true;
            //     var $form = $(this);
            //     $form.wait();

            //     var url = $form.data('url');
            //     var id = $form.find('input[name="id"]').val();
            //     var $nestedSelects = $form.find('.nested-select');
                // var $actualSelect = $($nestedSelects[($nestedSelects.length - 2)]);

                // var fd = new FormData();
                // fd.append('_token', window.Laravel.csrfToken);
                // fd.append('_method', $form.find('input[name="_method"]').val());
                // fd.append('id', id);
                // fd.append('name', $form.find('input[name="name"]').val());
                // fd.append("type", $form.find('input[name="type"]').val());
                // fd.append("cost", $form.find('input[name="cost"]').val());
                // fd.append("due_date", $form.find('input[name="due_date"]').val());
                // fd.append("paid_date", $form.find('input[name="paid_date"]').val());
                // fd.append("paid_cost", $form.find('input[name="paid_cost"]').val());
                // fd.append("delay_cost", $form.find('input[name="delay_cost"]').val());
                // fd.append("description", $form.find('textarea[name="description"]').val());
                // fd.append("category_id", $actualSelect.find('select[name="category_id[]"]').val());

                // $form.submit();
                // $.ajax({
                //     type: "POST",
                //     url: url,
                //     data: fd,
                //     processData: false,
                //     contentType: false,
                //     success: function (result) {

                //         $('body').success(result);

                //         setTimeout(() => {
                //             $btn.trigger('modalside:close');
                //             isSubmiting = false;
                //             $form.closeWait();
                //         }, 2000);

                //     },
                //     error: function (err) {

                //         $('body').error(err.responseText);
                //         $form.closeWait();
                //         isSubmiting = false;
                //     }
                // });

                // // Realiza Scroll para o topo da pagina
                // $target.find('.modalside-content').animate({
                //     scrollTop: $target.offset().top
                // }, 300);

            // }

        });

        /**
         * Função responsável por retornar os filhos do nó informado
         * @param {*} node 
         * @returns 
         */
        function getNodes(parentId) {

            return new Promise(function (resolve) {

                if (parentId) {

                    var url = window.Laravel.url + '/payable/category/nodes';

                    $.get(url, { parent_id: parentId }, function (response) {
                        resolve(response);

                    }).fail(err => {
                        resolve(false);
                    });

                } else {
                    resolve(false);
                }

            });
        }

        /**
         * Adapta select para select custom e realiza o bind do mesmo
         * @param {*} $elem 
         * @param {*} id 
         */
        function bindNewNestedSelect($elem, id) {

            $elem.nestedSelect(id);

            $elem.on('item-selected', function (e, id) {

                clearRigthElms($elem); // Limpa lista de seletores a frente

                if (id) {

                    getNodes(id).then(children => {

                        var $selectorElm = insertNewSelect(children);
                        bindNewNestedSelect($selectorElm, id);

                    }).catch(err => {

                    });

                }

            });

        }

        /**
         * Insere um novo select
         * @param {*} children 
         * @returns 
         */
        function insertNewSelect(children) {

            var newSelect = selectFactory(children);
            $target.find('.selects-list').append(newSelect);

            return newSelect;

        }

        /**
         * Fabrica um novo seletor
         * @param {*} data 
         */
        function selectFactory(data) {

            var $selector = $(`<select 
                    name="category_id[]" 
                    data-url="${window.Laravel.url + '/payable/category'}" 
                    data-placeholder='Selecione uma categoria'>
                    <option value selected>Selecione</option>	
                </select>`);

            if (data.length) {

                var option = [];
                // Insere categorias filhas
                data.forEach(category => {

                    option.push(`<option value="${category.id}">${category.name}</option>`);

                });

            }

            $selector.find('option').after(option);


            return $selector;

        }

        /**
        * Limpa lista de seletores
        * @param {*} $ref 
        */
        function clearRigthElms($elem) {

            $elem.parent().nextAll('.nested-select').remove();

        }

    });

});

// TODO submit to insert bill to pay with category_id ajax