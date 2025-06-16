@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

<a href="{{ route($routePrefix . '.create') }}" class="btn btn-success mb-4">Dodaj nowy</a>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        @foreach($columns as $col)
                            <th>{{ ucfirst($col) }}</th>
                        @endforeach
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            @foreach($columns as $col)
                                <td>{{ $item->$col }}</td>
                            @endforeach
                            <td>
                                <a href="{{ route($routePrefix . '.edit', $item) }}" class="btn btn-primary btn-sm">Edytuj</a>
                                <form action="{{ route($routePrefix . '.destroy', $item) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Na pewno chcesz usunąć?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
