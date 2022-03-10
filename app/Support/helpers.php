
<?php

if (!function_exists('isEdit')) {

    /**
     * Verifica se a view atual é para edição
     *
     * @return boolean
     */
    function isEdit()
    {

        $uri = request()->route()->uri();
        $ep = explode('/', $uri);

        if (is_array($ep)) {
            return end($ep) == 'edit';
        }
        return false;
    }
}
