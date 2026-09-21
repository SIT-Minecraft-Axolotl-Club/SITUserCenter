<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>创建新活动</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://www.bootcdn.cn/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/app/style.7eb5d06.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://unpkg.com/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" crossorigin>
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://skin.sitmc.club/app/3.ico">
    <link rel="icon" type="image/png" href="https://skin.sitmc.club/app/3.ico" sizes="192x192">
    <link rel="apple-touch-icon" href="https://skin.sitmc.club/app/3.ico" sizes="180x180">

    <style>
        body {
            background-color: #f4f6f9;
            padding-top: 40px;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .form-control, .form-label {
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="col-lg-8 mx-auto">
            <div class="card p-4">
                <h2 class="mb-4">创建新活动</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ url('/admin/activity/create') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">活动名称</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">报名开始时间</label>
                            <input type="datetime-local" name="signup_start" class="form-control" value="{{ old('signup_start') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">报名结束时间</label>
                            <input type="datetime-local" name="signup_end" class="form-control" value="{{ old('signup_end') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">活动开始时间</label>
                            <input type="datetime-local" name="event_start" class="form-control" value="{{ old('event_start') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">活动结束时间</label>
                            <input type="datetime-local" name="event_end" class="form-control" value="{{ old('event_end') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">人数上限</label>
                        <input type="number" name="max_participants" class="form-control" min="1" value="{{ old('max_participants') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">活动介绍（支持 Markdown）</label>
                        <textarea name="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ url('/admin/activity/list') }}" class="btn btn-secondary">返回活动列表</a>
                        <button type="submit" class="btn btn-primary">创建活动</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>