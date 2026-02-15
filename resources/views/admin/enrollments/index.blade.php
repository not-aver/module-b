@extends('layouts.app')

@section('content')
<div class="content-header">
    <h1>Записи на курсы</h1>
</div>

<div class="card">
    <div class="card-body">
        <!-- Фильтр по курсу -->
        <form method="GET" action="{{ route('admin.enrollments.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="course_id" class="form-control">
                        <option value="">Все курсы</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Фильтр</button>
                </div>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Студент</th>
                    <th>Email</th>
                    <th>Курс</th>
                    <th>Дата записи</th>
                    <th>Статус оплаты</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                <tr>
                    <td>{{ $enrollment->id }}</td>
                    <td>{{ $enrollment->user->name ?? 'Неизвестно' }}</td>
                    <td>{{ $enrollment->email }}</td>
                    <td>{{ $enrollment->course->name ?? 'Курс удалён' }}</td>
                    <td>{{ $enrollment->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($enrollment->payment_status == 'success')
                            <span class="badge bg-success">Оплачено</span>
                        @elseif($enrollment->payment_status == 'pending')
                            <span class="badge bg-warning">Ожидает оплаты</span>
                        @elseif($enrollment->payment_status == 'failed')
                            <span class="badge bg-danger">Ошибка оплаты</span>
                        @endif
                    </td>
                    <td>
                        @if($enrollment->payment_status == 'success')
                        <form action="{{ route('admin.certificate.print', $enrollment) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Сертификат</a>
                        </form>                        
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Нет записей</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Пагинация -->
        <div class="mt-3">
            {{ $enrollments->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection