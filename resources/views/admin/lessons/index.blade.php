@extends('layouts.app')

@section('content')
<div class="content-header">
    <h1>Уроки курса: {{ $course->name }}</h1>
    <a href="{{ route('admin.lessons.create', $course) }}" class="btn btn-success">Добавить урок</a>
    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Вернуться к курсам</a>
</div>
{{-- @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif --}}

@if ($errors->has('limit') || $errors->has('delete'))
    <div class="alert alert-danger">{{ $errors->first('limit') ?? $errors->first('delete') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Длительность (мин)</th>
            <th>Видео</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @forelse($lessons as $lesson)
        <tr>
            <td>{{ $lesson->id }}</td>
            <td>{{ $lesson->title }}</td>
            <td>{{ Str::limit($lesson->description, 50) }}</td>
            <td>{{ $lesson->duration }}</td>
            <td>
                @if($lesson->video_link)
                    <a href="{{ $lesson->video_link }}" target="_blank">Ссылка</a>
                @else
                    Нет
                @endif
            </td>
            <td>
                <a href="{{ route('admin.lessons.edit', [$course, $lesson]) }}"
                class="btn btn-primary">Редактировать</a>
                <form action="{{ route('admin.lessons.destroy', [$course, $lesson]) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить урок?')">Удалить</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;">Нет уроков</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection