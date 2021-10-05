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
            <tr @if (!Auth::check()) class="collapse f-{{$task->id}}" @endif>
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
                <tr @if (!Auth::check()) class="collapse f-{{$task->id}}" @endif>
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
                        <td colspan="2">
                            @if (!Auth::check())
                            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target=".f-{{$task->id}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-plus-fill" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 3.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5H2.545c-.3 0-.545.224-.545.5zm6.5 5a.5.5 0 0 0-1 0V10H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V11H10a.5.5 0 0 0 0-1H8.5V8.5z"/>
                                </svg>
                            </button>
                            @endif
                            <b>Horas Trabalhadas: {{gmdate("H:i:s", $sec)}}</b>
                        </td>
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
