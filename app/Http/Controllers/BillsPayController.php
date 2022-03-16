<?php

namespace App\Http\Controllers;

use App\Models\BillsToPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BillsPayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = BillsToPay::with('user')->paginate(50);
        return view('bills.payable.index', ['bills' => $bills]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $toPay = new BillsToPay();
        return view('bills.payable.create-edit', ['payable' => $toPay]);
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
        return view('bills.payable.create-edit', ['payable' => $toPay]);
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
            $toPay->save();
            
        } catch (\Exception $e) {
            return throw new \Exception($e->getMessage());
        }

    }

    public function validation(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'integer', 'max:255'],
            'cost' => ['required', 'numeric'],
            'due_date' => ['required', 'date'],
            'paid_date' => ['nullable', 'date'],
            'paid_cost' => ['nullable', 'numeric'],
            'delay_cost' => ['nullable', 'integer']
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
