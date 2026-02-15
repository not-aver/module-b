@extends('layouts.app')

@section('content')
<div style="max-width: 400px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px;">
    <h2>Вход</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="input-field @error('email') is-invalid @enderror">
            @error('email') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" 
                   class="input-field @error('password') is-invalid @enderror">
            @error('password') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        @if($errors->has('auth'))
            <div class="alert alert-danger">{{ $errors->first('auth') }}</div>
        @endif

        <button type="submit" class="btn btn-primary" style="width:100%">Войти</button>
    </form>
</div>
@endsection