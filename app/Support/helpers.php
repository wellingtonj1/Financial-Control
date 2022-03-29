
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

if (!function_exists('filterSearch')) {

    /**
     * Cria os arrays destinadas para filtragem nas listagens
     *
     * @param Request $request
     * @param integer $limit
     * @param string $sort
     * @param string $column
     * @param string $status
     * @return void
     */
    function filterSearch($request, $limit = 10, $sort = "desc", $column = "id", $status = "active")
    {

        $column = $request->column ?? $column;
        $limit = $request->row_limit ?? $limit;

        if ($limit > 500) {
            $limit = 500;
        }

        if ($request->sort && in_array($request->sort, ['asc', 'desc'])) {
            $sort = $request->sort;
        }

        $status = $request->status ?? $status;

        if ($column == "created_at") {
            $column = "created_at";
        } else if ($column == "updated_at") {
            $column = "updated_at";
        }

        return [$limit, $column, $sort, $status];
    }
}

if (!function_exists('checkOrderBy')) {

    /**
     * Verifica se a coluna de ordenação existe no array de colunas da tabela
     * @param array $arr
     * @param unknown $column
     * @param unknown $default
     * @return $value
     */
    function checkOrderBy($arr, $column, $default)
    {

        if (!empty($arr) && !empty($column) && in_array($column, $arr)) {
            return $column;
        }

        return $default;
    }
}

if (!function_exists('isInteger')) {

    /**
     * Verifica se o parâmetro informado é do tipo inteiro
     *
     * @param integer $val
     * @return boolean
     */
    function isInteger($val)
    {

        if (is_numeric($val) && intval($val) <= 2147483647) {
            return true;
        }
        return false;
    }
}

/**
 * Ignora os acentos presentes no valor avaliado na consulta
 *
 */
if (!function_exists('likePtBr')) {

    /**
     * Modifica a string para um formato destinado a melhor busca por termos PT-BR
     *
     * @param string $field
     * @param string $value
     * @return void
     */
    function likePtBr($field, $value)
    {
        return 'ignore_accents(' . $field . ") ilike ('%' || ignore_accents('" . str_replace('\'', '\'\'', $value) . "') || '%')";
    }
}