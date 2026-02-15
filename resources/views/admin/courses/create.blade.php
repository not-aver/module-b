@extends('layouts.app')

@section('content')
    <h1>Новый курс</h1>

    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" style="background:#fff; padding:20px; border-radius:8px;">
        @csrf

        <div class="form-group">
            <label>Название курса (max 30)</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="input-field @error('name') is-invalid @enderror">
            @error('name') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Описание</label>
            <input type="text" textarea name="description" value="{{ old('description') }}"
                   class="input-field @error('description') is-invalid @enderror">
            @error('description') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Продолжительность (в часах, не больше 10)</label>
            <input type="number" name="duration" value="{{ old('duration') }}"
                   class="input-field @error('duration') is-invalid @enderror">
            @error('duration') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Цена</label>
            <input type="number" inputmode="decimal" name="price" step="0.01" value="{{ old('price') }}"
                   class="input-field @error('price') is-invalid @enderror">
            @error('price') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Дата начала</label>
            <input type="date" name="start_date" value="{{ old('start_date') }}"
                   class="input-field @error('start_date') is-invalid @enderror">
            @error('start_date') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Дата окончания</label>
            <input type="date" name="end_date" value="{{ old('end_date') }}"
                   class="input-field @error('end_date') is-invalid @enderror">
            @error('end_date') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Обложка (max 2MB)</label>
            <input type="file" name="cover" class="input-field @error('cover') is-invalid @enderror">
            @error('cover') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Сохранить курс</button>
        <a href="{{ route('admin.courses.index') }}" class="btn" style="color:#666">Отмена</a>
    </form>
@endsection