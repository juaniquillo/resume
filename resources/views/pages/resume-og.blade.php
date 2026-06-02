<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OG Image</title>
    @vite(['resources/css/resume.css'])
    <style>
        body {
            width: 1200px;
            height: 630px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            font-family: 'Space Mono', monospace;
        }
        .card {
            background: white;
            width: 1100px;
            height: 530px;
            border-radius: 40px;
            display: flex;
            padding: 80px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            border: 8px solid #0284c7;
            align-items: center;
        }
        .avatar {
            width: 320px;
            height: 320px;
            border-radius: 40px;
            object-cover: cover;
            border: 8px solid #0284c7;
            margin-right: 60px;
        }
        .content {
            flex: 1;
        }
        .name {
            font-size: 80px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 20px;
            line-height: 1;
        }
        .label {
            font-size: 40px;
            font-weight: 600;
            color: #0284c7;
            margin-bottom: 30px;
        }
        .url {
            font-size: 24px;
            color: #64748b;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="card">
        @if($basics?->image)
            <img src="{{ route('image.serve', $basics->uuid) }}" class="avatar">
        @else
            <div class="avatar flex items-center justify-center bg-sky-100 text-sky-600 text-9xl font-bold">
                {{ substr($user->name, 0, 1) }}
            </div>
        @endif
        <div class="content">
            <div class="name">{{ $user->name }}</div>
            <div class="label">{{ $basics?->label }}</div>
            <div class="url">{{ config('app.url') }}/resume/{{ $user->slug }}</div>
        </div>
    </div>
</body>
</html>
