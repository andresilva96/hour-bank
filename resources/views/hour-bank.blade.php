@extends('layouts.app')

@section('content')
    <div class="row d-flex justify-content-center">
        <h1>Banco de Horas - {{$project->name}}</h1>
    </div>
    @if (Auth::check())
        <form class="row mt-3" action="/hour-bank/{{$project->hash}}" method="POST">
            @csrf
            <div class="col-12 mb-3">
                <input type="number" class="form-control" name="value" placeholder="Valor da Tarefa" required>
            </div>
            <div id="task" class="col-11 mb-3">
                <input type="text" class="form-control" name="tasks[]" placeholder="Nome da Tarefa" required>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-primary mb-3" id="add-task">+</button>
            </div>
            <div id="tasks"></div>
            <div class="col-12 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary mb-3">Criar</button>
            </div>
        </form>
    @endif
@endsection

@push('scripts')
    <script>
        $('#add-task').click(function() {
            $('#task').clone().insertAfter('#tasks').find('input[type="text"]').val('');
        })
    </script>
@endpush
