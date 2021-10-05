@extends('layouts.app')

@section('content')
    <div class="row d-flex justify-content-center">
        <h1>Banco de Horas - {{ $project->name }}</h1>
    </div>
    @if (Auth::check())
        <form class="row mt-3" action="/{{ $project->hash }}" method="POST">
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

    <hr>

    <form class="row mt-3" action="/{{ $project->hash }}" method="POST">
        @csrf
        <div class="col-4 mb-3">
            <input type="date" class="form-control" name="from">
        </div>
        <div class="col-4 mb-3">
            <input type="date" class="form-control" name="to">
        </div>
        <div class="col-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary mb-3">Consultar</button>
        </div>
    </form>

    <table class="table table-bordered">
        <tr>
            <th>Total Trabalhado</th>
            <th>Valor Total</th>
        </tr>
        <tr>
                @php ($sec = 0)
                @php ($total = 0)
                @foreach ($project->tasks()->orderBy('id', 'desc')->get() as $j => $task)
                    @foreach ($task->schedules as $i => $schedule)
                        @php ($sec += $schedule->end
                            ? \Carbon\Carbon::parse($schedule->start)->diffInSeconds(\Carbon\Carbon::parse($schedule->end))
                            : \Carbon\Carbon::parse(\Carbon\Carbon::now())->diffInSeconds($schedule->start))
                        @php ($val = $schedule->end
                            ? \Carbon\Carbon::parse($schedule->start)->diffInSeconds(\Carbon\Carbon::parse($schedule->end)) * (($task->value/60)/60)
                            : \Carbon\Carbon::parse(\Carbon\Carbon::now())->diffInSeconds($schedule->start) * (($task->value/60)/60))
                        @php ($total += $val)
                        @if (count($project->tasks()->orderBy('id', 'desc')->get()) == $j+1)
                            <td>{{gmdate("H:i:s", $sec)}}</td>
                            <td>{{Money::formatReal($total)}}</td>
                        @endif
                    @endforeach
                @endforeach
        </tr>
    </table>

    <hr>

    @foreach ($project->tasks()->orderBy('id', 'desc')->get(); as $task)
        <table class="table table-bordered">
            <tr>
                <td colspan="5">{{$task->content}}</td>
            </tr>
            <tr>
                <th>Início</th>
                <th>Fim</th>
                <th>Valor R$ {{$task->value}}/hr</th>
                <th>Status</th>
                @if (Auth::check())
                    <th>
                        Ação
                        <a href="/start/{{$task->id}}" type="button" class="btn btn-sm btn-success">Iniciar</a>
                        <a href="#" type="button" class="btn btn-sm btn-success">Encerrar</a>
                    </th>
                @endif
            </tr>

            @php ($sec = 0)
            @php ($total = 0)
            @foreach ($task->schedules as $i => $schedule)
                @php ($sec += $schedule->end
                    ? \Carbon\Carbon::parse($schedule->start)->diffInSeconds(\Carbon\Carbon::parse($schedule->end))
                    : \Carbon\Carbon::parse(\Carbon\Carbon::now())->diffInSeconds($schedule->start))
                @php ($val = $schedule->end
                    ? \Carbon\Carbon::parse($schedule->start)->diffInSeconds(\Carbon\Carbon::parse($schedule->end)) * (($task->value/60)/60)
                    : \Carbon\Carbon::parse(\Carbon\Carbon::now())->diffInSeconds($schedule->start) * (($task->value/60)/60))
                @php ($total += $val)
                <tr>
                    <td>{{\Carbon\Carbon::parse($schedule->start)->format('d/m/Y H:i:s')}}</td>
                    @if ($schedule->end)
                        <td>{{\Carbon\Carbon::parse($schedule->end)->format('d/m/Y H:i:s')}}</td>
                        <td>{{Money::formatReal($val)}}</td>
                        <td>Finalizado</td>
                    @else
                        <td>--:--:--</td>
                        <td>{{Money::formatReal($val)}}</td>
                        <td>Trabalhando há {{$schedule->start}}</td>
                    @endif
                    @if (Auth::check())
                        <td>
                            @if (!$schedule->end)
                                <a href="/end/{{$schedule->id}}" type="button" class="btn btn-sm btn-warning">terminar</a>
                            @endif
                            <a href="/delete-schedule/{{$schedule->id}}" class="btn btn-sm btn-danger">x</a>
                        </td>
                    @endif
                </tr>
                @if (count($task->schedules) == $i+1)
                    <tr>
                        <td colspan="2"><b>Horas Trabalhadas: {{gmdate("H:i:s", $sec)}}</b></td>
                        <td><b>Total: {{Money::formatReal($total)}}</b></td>
                    </tr>
                @endif
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
