<?php

namespace App\Libraries;

use Illuminate\Support\Facades\URL;

/**
 * SessionFilter Class
 */
class SessionFilter {

    /**
     * Atualizar sessionFilters
     * @param $request
     * @param $excludeFromFilters
     * @return Request
     */
    public static function updateFilters($request, $excludeFromFilters = []) {

        $sessionFilters = session("filters") ?? [];
        $page = $request->route()->uri;
        $params = $request->all();

        if (empty($params)) {

            $filters = $sessionFilters[$page] ?? null;

            if ($filters && self::isFiltersValid($filters, $sessionFilters)) {
                $request->merge($filters["params"]);
            }

        // Atualizar sessão
        } else {

            $sessionFilters[$page] = [
                "page" => $page,
                "created_at" => now(),
                "params" => self::clearFilters($params, $excludeFromFilters)
            ];

            session(["filters" => $sessionFilters]);
        }

        return $request;
    }

    /**
     * Verificar se o filtro ainda é valido (expiration time)
     * @param $filters
     * @param $sessionFilters
     * @return boolean
     */
    private static function isFiltersValid($filters, $sessionFilters) {

        $expiration = 5; // minutos que a sessão vai durar
        $valid = false;

        if ($filters && $filters["page"]) {

            if ($filters["created_at"] && $filters["params"]) {
                $valid = $filters["created_at"]->addMinutes($expiration) > now();
            }

            if ($valid == false) {
                unset($sessionFilters[($filters["page"])]);
                session(["filters" => $sessionFilters]);
            }
        }

        return $valid;
    }

    /**
     * Filtra um array, removendo valores nulos ou que são iguais aos default
     * @param $params
     * @param $excludeFromFilters
     * @return array
     */
    public static function clearFilters($params, $excludeFromFilters = []) {

        if (count($excludeFromFilters) == 0) {
            $excludeFromFilters = [ "display_qty" => 10 ];
        }

        return array_filter($params, function($key) use ($params, $excludeFromFilters) {
            return (isset($excludeFromFilters[$key]) && $excludeFromFilters[$key] != $params[$key]) || (isset($params[$key]) && !isset($excludeFromFilters[$key]));
        
        }, ARRAY_FILTER_USE_KEY);
    }
}