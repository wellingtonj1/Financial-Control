@extends('layouts.app', ['mainClass' => 'payable'])

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center">

            <div class="col-md-12 mg-up-50 mg-dw-50">

                <div class="alert pd-up-25">
                    @include('partials._alert', ['oneError' => true])
                </div>

                <div class="card custom">
                    <div class="card-header between">
                        <span class="card-title">Contas a Pagar</span>
                        <div class="heading-controls">
                            <a href="{{ url('bills/payable/create') }}" class="btn btn-primary">Cadastrar Nova</a>
                        </div>
                    </div>

                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="table-area">

                            @if (!empty($bills) && count($bills) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-actions table-ordered text-center">
                                        <thead>
                                            <tr>
                                                <th data-column="id">ID</th>
                                                <th data-column="name">Nome da conta</th>
                                                <th data-column="type">Tipo</th>
                                                <th>Categoria</th>
                                                <th data-column="due_date">Vencimento</th>
                                                <th data-column="cost">Valor</th>
                                                <th data-column="delay_cost">Juros por atraso</th>
                                                <th>Status</th>
                                                <th data-column="created_at">Criada em:</th>
                                                <th>Criado por:</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($bills as $bill)
                                                
                                                <td>{{ $bill['id'] }}</td>
                                                <td>{{ $bill['name'] }}</td>
                                                <td>{{ $bill['type'] == 1 ? 'FIXA' : 'Variavel' }}</td>
                                                <td data-hover="{{ $bill->selectedCategories }}" >{{ $bill->category['name'] ?? '--' }}</td>
                                                <td>{{ $bill['due_date'] }}</td>
                                                <td>{{ $bill['cost'] }}</td>
                                                <td>{{ $bill['delay_cost'] ? $bill['delay_cost'] . '%' : '--' }}</td>
                                                <td>{{ ($bill['paid_cost'] > 0 || $bill['paid_date']) ? 'Pago' : 'Não Pago' }}
                                                </td>
                                                <td>{{ $bill['created_at'] }}</td>
                                                <td>{{ $bill->user['name'] }}</td>

                                                <td>
                                                    <a href="{{ url('bills/payable/' . $bill['id'] . '/edit') }}"
                                                        class="btn btn-warning btn-sm btn-edit" title="Editar"><i
                                                            class="fas fa-pencil-alt"></i></a>
                                                    <button data-url="{{ url('bills/payable') }}"
                                                        data-id="{{ $bill['id'] }}"
                                                        class="btn btn-danger btn-sm btn-delete" title="Remover">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="pagination-wrapper mt15">
                                    {{ $bills->appends(Request::except('page'))->links() }}</div>
                            @else
                                <div class="obs">Não foram encontrados registros no sistema.</div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
