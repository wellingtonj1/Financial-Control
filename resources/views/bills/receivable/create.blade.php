@extends('layouts.app', ['mainClass' => 'wmodules create-edit'])

@section('content')
<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-12 mg-up-50 mg-dw-50">
            <div class="alert pd-up-25">
                @include('partials._alert', ['oneError' => true ])
            </div>
            <div class="card custom">
                <div class="card-header between">
                    <span class="card-title">Contas a receber</span>   
                    <a href="{{ url('bills/receivable') }}" class="btn btn-secondary">Voltar</a>
                </div>

                <div class="card-body">

                    <form class="modules-form" submit-block method="post" action="{{ url('bills/receivable') }}">
                        
                        {{ csrf_field() }}
                        {{ isEdit() ? method_field('PUT'):'' }}

                        <input type="hidden" name="id" value="{{ old('id', $receivable->id) }}" />

                        <div class="tab-content">

                            <div class="row">

                                <div class="col-sm-12 col-xs-12">

                                    <div class="row">

                                        <div class="form-group col-sm-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">Nome</span>
                                                <input type="text" class="form-control" name="name" value="{{ old('name', $receivable->name) }}" required="required" />
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="inputGroupSelect01">Tipo de Entrada</label>
                                                <select class="form-select" id="type" name="type" value="{{ old('type', $receivable->type) }}">
                                                    <option value="1">Fixa</option>
                                                    <option value="2">Variavel</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Valor R$</span>
                                                <input type="number" class="form-control" name="value" value="{{ old('value', $receivable->value) }}" required="required" />
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="row">

                                        <div class="form-group col-sm-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Data de Recebimento</span>
                                                <input type="date" class="form-control" name="receive_date" value="{{ old('receive_date', $receivable->receive_date) }}" required="required" />
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-8">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Descrição</span>
                                                <textarea class="form-control" aria-label="With textarea"></textarea>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
            
                        </div>

                        <div class=center-submit>
                            <button type="submit" class="btn btn-primary center submit">Salvar Worker</button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
