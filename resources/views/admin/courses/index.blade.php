@extends('layouts.app')

@section('content')
    <div style="display:flex; justify-content: space-between; align-items: center;">
        <h1>Курсы</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-success">Добавить курс</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Длительность</th>
                <th>Цена</th>
                <th>Дата начала</th>
                <th>Дата окончания</th>
                <th>Обложка</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course->id }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->description }}</td>
                <td>{{ $course->duration }}</td>    
                <td>{{ $course->price }}</td>
                <td>{{ $course->start_date->format('d-m-Y') }} </td>
                <td>{{ $course->end_date->format('d-m-Y') }}</td>
                <td>@if($course->cover)
                        <img src="{{ asset('storage/' . $course->cover) }}" alt="cover" style="max-width: 50px; max-height: 50px;">
                    @endif</td>
                <td>
                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">Редактировать</a>
                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить курс?')">Удалить</button>
                    </form>
                </td>
                <td>
                    <a href="{{ route('admin.lessons.index', $course) }}">Уроки ({{ $course->lessons()->count() }})</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $courses->links('pagination::bootstrap-5') }}
    </div>
@endsection