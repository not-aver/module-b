@extends('layouts.app')

@section('content')
<h1>Новый урок для курса: {{ $course->name }}</h1>

<form action="{{ route('admin.lessons.store', $course) }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Название (макс. 50)</label>
        <input type="text" name="title" value="{{ old('title') }}" class="input-field @error('title') is-invalid @enderror">
        @error('title') <div class="error-text">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label>Описание</label>
        <textarea name="description" class="input-field @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        @error('description') <div class="error-text">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label>Ссылка на видео (SuperTube)</label>
        <input type="url" name="video_link" value="{{ old('video_link') }}" placeholder="https://super-tube.cc/video/..." class="input-field @error('video_link') is-invalid @enderror">
        @error('video_link') <div class="error-text">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label>Длительность (часы, 1-4)</label>
        <input type="number" name="duration" min="1" max="4" value="{{ old('duration') }}" class="input-field @error('duration') is-invalid @enderror">
        @error('duration') <div class="error-text">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-success">Сохранить</button>
    <a href="{{ route('admin.lessons.index', $course) }}" class="btn">Отмена</a>
</form>
@endsection