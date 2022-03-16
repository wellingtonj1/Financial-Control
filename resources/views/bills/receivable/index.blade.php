@extends('layouts.app', ['mainClass' => 'wmodules'])

@section('content')
<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-12 mg-up-50 mg-dw-50">
            <div class="allert pd-up-25">
                @include('partials._alert', ['oneError' => true ])
            </div>
            <div class="card custom">
                <div class="card-header between">
                    <span class="card-title">Contas a receber</span>   
                    <div class="heading-controls">
                        <a href="{{ url('bills/receivable/create') }}" class="btn btn-primary">Cadastrar Nova</a>
                    </div> 
                </div>

                <div class="card-body">
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <div class="table-area">

                        @if (!empty($worker_modules) && count($worker_modules) > 0)
            
                            <div class="table-responsive">
                                <table class="table table-striped table-actions table-ordered">
                                    <thead>
                                        <tr>
                                            <th data-column="id">ID</th>
                                            <th data-column="name">Nome do Módulo</th>
                                            <th data-column="db_host">Host Cliente</th>
                                            <th data-column="db_name">DB Cliente</th>
                                            <th data-column="bi_db_host">Host BI</th>
                                            <th data-column="bi_db_name">DB BI</th>
                                            <th data-column="user_name">Usuário Responsável</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                        @foreach ($worker_modules as $module)
                                            <td>{{ $module['id'] }}</td>
                                            <td>{{ $module['name'] }}</td>
                                            <td>{{ $module['db_host'] }}</td>
                                            <td>{{ $module['db_name'] }}</td>
                                            <td>{{ $module['bi_db_host'] }}</td>
                                            <td>{{ $module['bi_db_name'] }}</td>
                                            <td>{{ $module['user_name'] }}</td>
            
                                            <td>
                                                <a href="{{ url('modules/'.$module['id'].'/edit') }}" class="btn btn-light btn-sm" title="Editar"><i class="fas fa-pencil-alt"></i></a>
                                                <button data-url="{{ url('modules') }}" data-id="{{ $module['id'] }}" 
                                                    class="btn btn-danger btn-sm btn-delete" 
                                                    title="Remover"
                                                    {{ $module->worker != null ? 'disabled' : '' }}
                                                    >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
            
                            {{-- <div class="pagination-wrapper mt15">{{ $robot->appends(Request::except('page'))->links() }}</div> --}}
            
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
