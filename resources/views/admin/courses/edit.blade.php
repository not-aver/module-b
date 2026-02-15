@extends('layouts.app')

@section('content')
    <h1>Редактировать курс</h1>

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data" style="background:#fff; padding:20px; border-radius:8px;">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Название курса (max 30)</label>
            <input type="text" name="name" value="{{ old('name', $course->name) }}" class="input-field @error('name') is-invalid @enderror">
            @error('name') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Описание</label>
            <input type="text" name="description" value="{{ old('description', $course->description) }}" class="input-field @error('description') is-invalid @enderror">
            @error('description') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Продолжительность (в часах, не больше 10)</label>
            <input type="number" name="duration" value="{{ old('duration', $course->duration) }}" class="input-field @error('duration') is-invalid @enderror">
            @error('duration') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Цена</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $course->price) }}" class="input-field @error('price') is-invalid @enderror">
            @error('price') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Дата начала</label>
            <input type="date" name="start_date" value="{{ old('start_date', $course->start_date->format('Y-m-d')) }}" class="input-field @error('start_date') is-invalid @enderror">
            @error('start_date') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Дата окончания</label>
            <input type="date" name="end_date" value="{{ old('end_date', $course->end_date->format('Y-m-d')) }}" class="input-field @error('end_date') is-invalid @enderror">
            @error('end_date') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Текущая обложка</label><br>
            @if($course->cover)
                <img src="{{ asset('storage/' . $course->cover) }}" alt="cover" style="max-width: 200px; max-height: 150px; margin-bottom: 10px;">
            @else
                <p>Нет изображения</p>
            @endif
        </div>

        <div class="form-group">
            <label>Новая обложка (оставьте пустым, если не хотите менять)</label>
            <input type="file" name="cover" class="input-field @error('cover') is-invalid @enderror">
            @error('cover') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Обновить курс</button>
        <a href="{{ route('admin.courses.index') }}" class="btn" style="color:#666">Отмена</a>
    </form>
@endsection