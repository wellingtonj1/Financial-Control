<?php

namespace App\Http\Controllers;

use App\Libraries\SessionFilter;
use App\Models\BillsToPay;
use App\Models\BillsToPayCategories;
use App\Traits\NestedSelectTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BillsPayController extends Controller
{

    use NestedSelectTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $request = SessionFilter::updateFilters($request);

		list($limit, $column, $sort, $status) = $filters = filterSearch($request, 50);

		$orderedColumns = [ 'id', 'name', 'type', 'due_date', 'cost', 'delay_cost', 'created_at'];
		$column = checkOrderBy($orderedColumns, $request->column, 'id');

        //Obtem todas as categorias
        $categories = BillsToPayCategories::orderBy('name', 'asc')->get();
        
        $bills = BillsToPay::with('user', 'category')->orderBy($column, $sort)->paginate($limit);
        
        foreach ($bills as $bill) {
            
            $bill->selectedCategories = '';
            $cats = array_reverse($this->buildItemPath($categories, $bill->category_id));
            
            foreach ($cats as $key => $cat) {
                $bill->selectedCategories .= $key == 0 ? $categories->find($cat)->name : ' > ' . $categories->find($cat)->name;
            }
        }

        return view('bills.payable.index', ['bills' => $bills]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->form(new BillsToPay());
    }

    public function form($billToPay)
    {
        $selectedCategories = [];

        if($billToPay->id) {

            //Obtem todas as categorias
            $categories = BillsToPayCategories::orderBy('name', 'asc')->get();

            //Obtêm as categorias selecionadas
            $selectedCategories = array_reverse($this->buildSelectedLists($categories, $billToPay->category_id));

            //Filtra somente as categorias sem pai
            $categories = $categories->where('parent_id', null);

        } else {
            $categories = BillsToPayCategories::FindByParentId()->get();
        }

        return view("bills.payable.create-edit", [
            'payable' => $billToPay, 
            'categories' => $categories,
            'selectedCategories' => $selectedCategories
        ]);

    }

    /**
     * Returns nodes associated with the specified element 
     *
     * @param Request $request
     * @return void
     */
    public function getNodes(Request $request){
        
        //Obtêm as categorias filhas
        return BillsToPayCategories::FindByParentId($request->parent_id)->get();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            
            $validator = $this->validation($request);

            if(!$validator->fails()) {

                $toPay = new BillsToPay();
                $this->save($request, $toPay);

                return redirect()->route('billstopay')->with('success', 'Conta criada com sucesso!');

            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }

        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $toPay = BillsToPay::find($id);
        return $this->form($toPay);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {

            $id = $request->id;
            $toPay = BillsToPay::find($id);

            if ($toPay) {
                $this->save($request, $toPay);
                return redirect()->route('billstopay')->with('success', 'Conta atualizada com sucesso!');
            } else {
                return redirect()->back()->withErrors('Conta não encontrada!')->withInput();
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function save(Request $request, BillsToPay $toPay)
    {
        try {
            
            $toPay->user_id = Auth::user()->id;
            $toPay->name = $request->name;
            $toPay->type = $request->type;
            $toPay->description = $request->description;
            $toPay->due_date = $request->due_date;
            $toPay->cost = $request->cost;
            $toPay->paid_cost = $request->paid_cost;
            $toPay->paid_date = $request->paid_date;
            $toPay->delay_cost = $request->delay_cost;

            // get the last not null element of $request->category_id
            $categoryId = array_filter($request->category_id, function($value) {
                return $value !== null;
            });

            $toPay->category_id = end($categoryId);
            $toPay->save();
            
        } catch (\Exception $e) {
            return throw new \Exception($e->getMessage());
        }

    }

    public function validation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'numeric'],
            'due_date' => ['required', 'date'],
            'paid_date' => ['nullable', 'date'],
            'paid_cost' => ['nullable', 'numeric'],
            'delay_cost' => ['nullable', 'integer'],
        ]);

        return $validator;
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $toPay = BillsToPay::find($id);

            if ($toPay) {
                $toPay->delete();
                return redirect()->route('billstopay')->with('success', 'Conta a pagar excluída com sucesso!');
            } else {
                return redirect()->back()->withErrors('Conta não encontrada!')->withInput();
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

}
