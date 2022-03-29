<?php

namespace App\Http\Controllers;

use App\Models\BillsToPay;
use App\Models\BillsToPayCategories;
use App\Traits\NestedSelectTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BillsCategoriesController extends Controller
{

    use NestedSelectTrait;
    
    public function __construct() {

        $this->model = BillsToPayCategories::class;
        $this->itemsModel = BillsToPay::class;
        $this->table = 'bills_to_pay_categories';
        $this->itemsTable = 'bills_to_pay';

    }
    
    public function get($id)
    {
        
        $enabledCats = $this->model::searchByPta($id)
        ->select( $this->table . '.*')->orderBy('name', 'asc')->get();

        $allCat = $this->model::orderBy('name', 'asc')->get();
        $nestedCat = $this->getCategoriesList($allCat);

        $allCategories = [];
        
        foreach ($enabledCats as $category) {

            $path = $this->buildItemPath($nestedCat, $category->id);
            $allCategories = array_merge($allCategories, $path);
        }
        
        $allowedCategories = $allCat
            ->whereIn('id', array_unique($allCategories))
            ->values();

        return $this->getCategoriesList($allowedCategories);

    }

    public function insert(Request $request)
    {

        $validator = $this->validation($request);

        if (!$validator->fails()) {

            try {

                DB::beginTransaction();

                $category = new $this->model();
                $category->name = $request->name;
                $category->parent_id = $request->parent_id;
                $category->save();

                DB::commit();

                return $category;
            } catch (\Exception $e) {

                DB::rollBack();
                return response('Não foi possível salvar a categoria, houve um erro interno.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response('Não foi possível salvar a categoria, os dados são inválidos.', Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request)
    {

        $validator = $this->validation($request);

        if (!$validator->fails()) {

            try {

                DB::beginTransaction();

                $category = $this->model::findById($request->id)->first();

                if ($category) {

                    $category->name = $request->name;
                    $category->save();

                    DB::commit();

                    return $category;
                }

                DB::rollBack();
                return response('Não foram encontrados registros no sistema.', Response::HTTP_NOT_FOUND);
            } catch (\Exception $e) {

                DB::rollBack();
                return response('Não foi possível atualizar a categoria, houve um erro interno.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response('Não foi possível atualizar a categoria, os dados são inválidos.', Response::HTTP_BAD_REQUEST);
    }

    public function delete(Request $request)
    {

        if (isset($request->id) && isInteger($request->id)) {

            try {

                DB::beginTransaction();

                $category = $this->model::findById($request->id)->first();

                if ($category) {

                    $products = $this->itemsModel::where('category_id', $category->id)->exists();

                    $categoryNodes = $this->model::findByParentId($category->id)->first();

                    if (!$products && !$categoryNodes) {

                        $category->delete();

                        DB::commit();

                        return "Removido com Sucesso";
                    }

                    return response('Não foi possível remover. Existem elementos atrelados a esta categoria!', Response::HTTP_FORBIDDEN);
                }

                return response('Não foram encontradas registros no sistema.', Response::HTTP_NOT_FOUND);
            } catch (\Exception $e) {
                dd($e);
                return response('Não foi possível remover a categoria, houve um erro interno.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response('Não foi possível remover a categoria, os dados são inválidos.', Response::HTTP_BAD_REQUEST);
    }

    public function validation($request)
    {

        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|numeric|exists:' . $this->table . ',id',
            'name' => 'required|string|max:150',
        ]);

        $validator->sometimes('id', 'bail|required|numeric|exists:' . $this->table . ',id', function ($request) {
            return $request->_method == 'PUT';
        });

        return $validator;
    }

}
