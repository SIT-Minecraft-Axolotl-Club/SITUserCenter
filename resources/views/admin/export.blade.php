<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>导出活动数据 - 管理后台</title>
    <link href="https://www.bootcdn.cn/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/app/style.7eb5d06.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://unpkg.com/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" crossorigin>
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://skin.sitmc.club/app/3.ico">
    <link rel="icon" type="image/png" href="https://skin.sitmc.club/app/3.ico" sizes="192x192">
    <link rel="apple-touch-icon" href="https://skin.sitmc.club/app/3.ico" sizes="180x180">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="https://skin.sitmc.club">SIT-Minecraft</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">请选择一个活动导出报名信息</p>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.exportActivityData') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="input-group">
                        <select name="event_id" id="event_id" class="form-control" required>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                        </div>
                    </div>
                </div>
                              <a href="https://skin.sitmc.club/admin" class="btn btn-block bg-light border-secondary">
                            返回管理页面
                          </a>
                <button type="submit" id="exportButton" class="btn btn-primary btn-block">
                    <i class="fas fa-file-csv"></i> 导出 CSV
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const exportButton = document.getElementById('exportButton');
    const form = document.querySelector('form');

    form.addEventListener('submit', function () {
        exportButton.disabled = true;
        exportButton.innerText = '导出中，请稍候...';
    });
});
</script>
</body>
</html>
