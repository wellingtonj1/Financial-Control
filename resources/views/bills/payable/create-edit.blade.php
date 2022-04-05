@extends('layouts.app', ['mainClass' => 'payable-create-edit'])

@section('content')
<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-12 mg-up-50 mg-dw-50">
            <div class="alert">
                @include('partials._alert', ['oneError' => true ])
            </div>
            <div class="card custom">
                <div class="card-header between">
                    <span class="card-title">Contas a pagar</span>   
                    <a href="{{ url('bills/payable') }}" class="btn btn-secondary">Voltar</a>
                </div>

                <div class="card-body">

                    <form class="to-pay-form" submit-block method="post" action="{{ url('bills/payable') }}" data-url="{{ url('bills/payable') }}">
                        
                        {{ csrf_field() }}
                        
                        {{ isEdit() ? method_field('PUT'):method_field('POST') }}

                        <input type="hidden" name="id" value="{{ old('id', $payable->id) }}" />

                        <div class="tab-content">

                            <div class="row">

                                <div class="col-sm-12 col-xs-12">

                                    <div class="row">

                                        <div class="form-group col-sm-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">Nome</span>
                                                <input type="text" class="form-control" name="name" value="{{ old('name', $payable->name) }}" required="required" />
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-3">
                                            <div class="input-group mb-2">
                                                <label class="input-group-text" for="inputGroupSelect01">Tipo de Dívida</label>
                                                <select class="form-select" id="type" name="type" value="{{ old('type', $payable->type) }}">
                                                    <option value="1">Fixa</option>
                                                    <option value="2">Variavel</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-2">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Valor R$</span>
                                                <input type="numeric" class="form-control" name="cost" value="{{ old('cost', $payable->cost) }}" required="required" />
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            
                                            <div class="selects-list input-group">
                                                <span class="input-group-text">Categoria</span>
                                                @if (isEdit() && $selectedCategories && count($selectedCategories))
                                                
                                                    @foreach ($selectedCategories as $categories)
                                                        
                                                        <select 
                                                        name="category_id[]" 
                                                        
                                                        data-url="{{ url('/payable/category') }}" 
                                                        data-placeholder='Selecione uma categoria: <span class="required">*</span>'
                                                        data-parent={{ $categories->where('is_selected', true)->first()->id ?? null }}
                                                        data-lastId={{ $payable->category_id }}
                                                        >
                                                        <option value>Selecione</option>	
                                                        @foreach($categories as $index => $p)
                                                            <option value="{{ $p->id }}" 
                                                                {{ $p->is_selected ? 'selected="selected"':'' }}>
                                                                {{ $p->name }}
                                                            </option>
                                                        @endforeach
                                
                                                        </select>
                                                            
                                                    @endforeach
                                                
                                                @else
                                
                                                    <select 
                                                        name="category_id[]" 
                                                        
                                                        data-url="{{ url('/payable/category') }}" 
                                                        data-placeholder='Selecione uma categoria: <span class="required">*</span>'
                                                    >
                                                        <option value>Selecione</option>	
                                                        @foreach($categories->values() as $index => $p)
                                                            <option value="{{ $p->id }}" 
                                                            {{ $selectedCategories && $p->id === $selectedCategories[0][$index]->id && $selectedCategories[0][$index]->is_selected ? 'selected="selected"':'' }}>
                                                                {{ $p->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                            
                                                @endif
                                
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="form-group col-sm-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Data de Vencimento</span>
                                                <input type="date" class="form-control" name="due_date" value="{{ old('due_date', $payable->due_date) }}" required="required" />
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Data de Pagamento</span>
                                                <input type="date" class="form-control" name="paid_date" value="{{ old('paid_date', $payable->paid_date) }}"/>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Valor Pago R$</span>
                                                <input type="number" class="form-control" name="paid_cost" value="{{ old('paid_cost', $payable->paid_cost) }}" />
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="form-group col-sm-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Taxa de Juros mês %</span>
                                                <input type="number" class="form-control" name="delay_cost" value="{{ old('delay_cost', $payable->delay_cost) }}" />
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-8">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Descrição</span>
                                                <textarea class="form-control" aria-label="With textarea" name="description">{{ $payable->description }}</textarea>
                                            </div>
                                        </div>
                                        
                                    </div>

                                </div>
                            </div>
            
                        </div>

                        <div class=center-submit>
                            <button type="submit" class="btn btn-primary center submit">Salvar Conta a Pagar</button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
