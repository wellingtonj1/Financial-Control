<?php

namespace App\Traits;

/**
 * Trait para gerenciamento de seleção aninhada
 *
 * @author Wellington Junio <wellington.itbus@gmail.com>
 * @since 16/03/2022 
 * @version 1.0.0
 */
trait NestedSelectTrait
{

    /**
     * Mount the list of data setting with selected all root and nodes connecteds in the informed id 
     *
     * @param [type] $list
     * @param [type] $id
     * @param integer $level
     * @return array
     */
    public function buildSelectedLists($list, $id, $level = 0)
    {

        $categoryList = [];
        $item = $list->where('id', $id)->first();

        if ($item) {

            $items = $list->where('parent_id', $item->parent_id);

            $items = $items->map(function ($i) use ($item) {
                $i->is_selected = $i->id == $item->id;
                return $i;
            });

            if ($items->count()) {

                $categoryList[$level] = $items->values();

                if ($item->parent_id) {

                    $level++;

                    $cnl = $this->buildSelectedLists($list, $item->parent_id, $level);

                    if ($cnl && count($cnl)) {
                        $categoryList = array_merge($categoryList, $cnl);
                    }
                }
            }
        }

        return $categoryList;
    }

    /**
     * Seach for elements in a Layer tree 
     *
     * @param [type] $list
     * @param [type] $id
     * @return void
     */
    function dfsGetByList($list, $id)
    {

        $newAux = [];

        if ($id) {

            foreach ($list as $l) {

                $srch = $l->where('id', $id)->first();

                if ($srch) {

                    array_push($newAux, $srch);

                    $cnl = $this->dfsGetByList($list, $srch->parent_id);

                    if ($cnl) {
                        $newAux = array_merge($newAux, $cnl);
                    }
                }
            }

            return $newAux;
        }

        return false;
    }

    /**
     * Build the item path
     *
     * @param [type] $nestedList
     * @param [type] $id
     * @param [type] $path
     * @return array
     */
    public function buildItemPath($nestedList, $id, $path = null) {
        
        $path = $path ?? [];

        foreach($nestedList as $list) {
            
            $node = $this->searchInArrayStructure($list, $id);

            if($node) {
                
                array_push($path, $node['id']);

                if($node['parent_id']) {
                    $path = $this->buildItemPath($nestedList, $node['parent_id'], $path);
                }

                break;
            }
            
        }

        return $path;
        
    }

    private function searchInArrayStructure($list, $id, $aux = null) {
        
        if($list['id'] == $id) {
            return $list;

        } else {
            // Não é o cara que eu quero mas talvez o pai de tal

            if($list['children']){
                foreach ($list['children'] as $child) {
                    
                    $n = $this->searchInArrayStructure($child, $id, $aux);

                    if($n){
                        $aux = $n;
                    }
                }
            } 

        }
        
        return $aux;

    }
    
    /**
     * Carrega Lista de categorias disponíveis
     *
     * @return Collect
     */
    private function getCategoriesList($list)
    {
        return $this->buildTreeStructure($list, []);
    }

    /**
     * Obtém lista de categorias
     *
     * @param [type] $all
     * @param array $aux
     * @param [type] $parent
     */
    public function buildTreeStructure($all, $aux, $parent = null)
    {
        $orphan = $all->where('parent_id', $parent);

        if (count($orphan)) {

            foreach ($orphan->values() as $index => $o) {

                // Cria a estrutura dos Pais estruturados com a exclusão de seus pais
                array_push($aux, [
                    'id' => $o->id,
                    'name' => $o->name,
                    'parent_id' => $o->parent_id,
                    "has_bill" => $o->has_bill ?? null,
                    'children' => []
                ]);

                $son = $all->where('parent_id', $o->id);

                if (count($son)) {

                    $sonAux = [];

                    foreach ($son->values() as $s) {

                        $subSon = $this->buildTreeStructure($all, $sonAux, $s['id']);

                        // Entra quando a recursão retorna um filho com seu conjunto já completo
                        if ($subSon) {

                            array_push($aux[$index]['children'], [
                                'id' => $s->id,
                                'name' => $s->name,
                                'parent_id' => $s->parent_id,
                                "has_bill" => $s->has_bill ?? null,
                                'children' => $subSon
                            ]);

                            // Entra na possibilidade de ser o ultimo filho 
                        } else {

                            if (isset($aux[$index])) {

                                array_push($aux[$index]['children'], [
                                    'id' => $s->id,
                                    'name' => $s->name,
                                    'parent_id' => $s->parent_id,
                                    "has_bill" => $s->has_bill ?? null,
                                    'children' => []
                                ]);
                            }
                        }
                    }
                }

                $index++;
            }

            return $aux;
        }
        return false;
    }
}
