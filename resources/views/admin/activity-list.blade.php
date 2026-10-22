<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>活动列表</title>
    <link href="https://www.bootcdn.cn/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ app_style_url() }}" rel="stylesheet" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://unpkg.com/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" crossorigin>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ app_favicon_url() }}">
    <link rel="icon" type="image/png" href="{{ app_favicon_url() }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ app_favicon_url() }}" sizes="180x180">
    <style>
        body {
            background-color: #f4f6f9;
            padding-top: 40px;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        table th, table td {
            vertical-align: middle;
        }
        .truncate-text {
            max-height: 4.5em;
            overflow: hidden;
            position: relative;
        }
        .truncate-text::after {
            content: '...';
            position: absolute;
            bottom: 0;
            right: 0;
            background: linear-gradient(to right, transparent, #fff 80%);
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="col-lg-12 mx-auto">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">活动列表</h2>
                    <a href="{{ url('/admin/activity/create') }}" class="btn btn-primary">＋ 创建新活动</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>活动名称</th>
                                <th>报名时间</th>
                                <th>活动时间</th>
                                <th>人数上限</th>
                                <th style="min-width: 200px;">活动介绍</th>
                                <th>签到码</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $a)
                                <tr>
                                    <td>{{ $a->id }}</td>
                                    <td>{{ $a->name }}</td>
                                    <td>
                                        <small>{{ $a->signup_start }}</small><br>
                                        <small class="text-muted">~ {{ $a->signup_end }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $a->event_start }}</small><br>
                                        <small class="text-muted">~ {{ $a->event_end }}</small>
                                    </td>
                                    <td>{{ $a->max_participants }}</td>
                                    <td style="text-align: left;">
                                        <div class="truncate-text" title="{{ $a->content }}">
                                            {{ mb_substr($a->content, 0, 200) }}
                                        </div>
                                    </td>
                                    <td><code>{{ $a->sign_code }}</code></td>
                                    <td>
                                        <form method="POST" action="{{ url('/admin/activity/' . $a->id . '/reset-code') }}">
                                            @csrf
                                            <button class="btn btn-sm btn-warning" type="submit" onclick="return confirm('确定要重置签到码？');">重置</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ url('/admin') }}" class="btn btn-secondary">返回管理面板</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
