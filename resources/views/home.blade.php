@extends('layouts.app')

@section('content')
    <form class="row g-3" action="{{route('home')}}" method="POST">
        @csrf
        <div class="col-11">
            <input type="text" class="form-control" name="name" placeholder="Nome do Projeto">
        </div>
        <div class="col-1">
            <button type="submit" class="btn btn-primary mb-3">Criar</button>
        </div>
    </form>

    @foreach ($projects as $project)
        <ul class="list-group">
            <li class="list-group-item">
                <span>{{$project->hash}}</span>
                <a href="#">{{$project->name}}</a>
            </li>
        </ul>
    @endforeach
@endsection
