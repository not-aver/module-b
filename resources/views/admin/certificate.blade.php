<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сертификат</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .certificate {
            width: 800px;
            padding: 40px;
            background: white;
            border: 10px double #4a4a4a;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            text-align: center;
        }
        h1 {
            font-size: 42px;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        .name {
            font-size: 36px;
            font-weight: bold;
            margin: 20px 0;
            color: #27ae60;
        }
        .course {
            font-size: 28px;
            margin: 20px 0;
            color: #2980b9;
        }
        .number {
            font-size: 18px;
            margin-top: 40px;
            color: #7f8c8d;
        }
        .date {
            margin-top: 30px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Сертификат</h1>
        <p>Настоящим подтверждается, что</p>
        <div class="name">{{ $student->name }}</div>
        <p>успешно завершил(а) курс</p>
        <div class="course">{{ $course->name }}</div>
        <div class="number">Номер сертификата: {{ $certificateNumber }}</div>
        <div class="date">Дата выдачи: {{ now()->format('d.m.Y') }}</div>
    </div>
</body>
</html>