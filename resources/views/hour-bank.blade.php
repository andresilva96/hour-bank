@extends('layouts.app')

@section('content')
    <div class="row d-flex justify-content-center">
        <h1>Banco de Horas - {{ $project->name }}</h1>
    </div>
    @if (Auth::check())
        <form class="row mt-3" action="/hour-bank/{{ $project->hash }}" method="POST">
            @csrf
            <div class="col-12 mb-3">
                <input type="number" class="form-control" name="value" placeholder="Valor da Tarefa" required>
            </div>
            <div id="task" class="col-11 mb-3">
                <input type="text" class="form-control" name="tasks[]" placeholder="Conteúdo da Tarefa" required>
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

    @foreach ($project->tasks()->orderBy('id', 'desc')->get(); as $task)
        <table class="table table-bordered">
            <tr>
                <td colspan="4">{{$task->content}}</td>
                <td>
                    @if (Auth::check())
                        <a href="/start/{{$task->id}}" type="button" class="btn btn-sm btn-success">Iniciar</a>
                    @endif
                    <a href="#" type="button" class="btn btn-sm btn-success">Encerrar</a>
                </td>
            </tr>
            <tr>
                <th>Início</th>
                <th>Fim</th>
                <th>Status</th>
                <th>Valor R$ {{$task->value}}/hr</th>
                @if (Auth::check())
                    <th>Ação</th>
                @endif
            </tr>
            @foreach ($task->schedules as $schedule)
                <tr>
                    <td>{{\Carbon\Carbon::parse($schedule->start)->format('d/m/Y H:i:s')}}</td>
                    @if ($schedule->end)
                        <td>{{\Carbon\Carbon::parse($schedule->end)->format('d/m/Y H:i:s')}}</td>
                        <td>Finalizado</td>
                        <td>{{Money::formatReal(\Carbon\Carbon::parse($schedule->start)->diffInSeconds(\Carbon\Carbon::parse($schedule->end)) * (($task->value/60)/60))}}</td>
                    @else
                        <td>--:--:--</td>
                        <td>Trabalhando há {{$schedule->start}}</td>
                        <td>{{Money::formatReal(\Carbon\Carbon::parse(\Carbon\Carbon::now())->diffInSeconds($schedule->start) * (($task->value/60)/60))}}</td>
                    @endif
                    @if (Auth::check())
                        <td>
                            @if (!$schedule->end)
                                <a href="/end/{{$schedule->id}}" type="button" class="btn btn-sm btn-warning">terminar</a>
                            @endif
                            <button type="button" class="btn btn-sm btn-danger">x</button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </table>
    @endforeach
@endsection

@push('scripts')
    <script>
        $('#add-task').click(function() {
            $('#task').clone().insertAfter('#tasks').find('input[type="text"]').val('');
        })
    </script>
@endpush
