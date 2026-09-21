<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社团活动列表</title>
    <link href="https://www.bootcdn.cn/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="/app/style.7eb5d06.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" crossorigin>
    <link rel="shortcut icon" href="https://skin.sitmc.club/app/3.ico">
    <link rel="icon" type="image/png" href="https://skin.sitmc.club/app/3.ico" sizes="192x192">
    <link rel="apple-touch-icon" href="https://skin.sitmc.club/app/3.ico" sizes="180x180">

    <style>
        /* ===== Minecraft 暗黑风格（与之前完全一致） ===== */
        @font-face {
            font-family: 'Minecraft';
            src: url('https://cdn.jsdelivr.net/gh/South-Paw/typeface-minecraft@master/public/MinecraftRegular-Bmg3.woff2') format('woff2');
        }

        .navbar-nav .nav-item .fa-moon,
        .navbar-nav .nav-item .fa-sun,
        .navbar-nav .nav-item a[href*="theme"],
        [data-widget="darkmode"],
        .navbar .fa-moon,
        .navbar .fa-sun,
        .theme-switch,
        #toggle-dark-mode {
            display: none !important;
        }

        html, body, .wrapper, .content-wrapper, main {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
        }

        h1, h2, h3, h4, h5, h6, p, span, a, .nav-link, .brand-text {
            color: #ffffff !important;
        }
        h1, h2, h3, .brand-text, .card-title, .display-4, .display-3, .splash-head {
            font-family: 'Minecraft', sans-serif !important;
            text-shadow: 3px 3px 0px #000000 !important;
            letter-spacing: 1px;
        }
        p.lead, p, .splash-subhead, .splash p {
            color: #cccccc !important;
            text-shadow: 1px 1px 0px #000000 !important;
        }

        .card {
            border: 2px solid #000000 !important;
            border-radius: 0 !important;
            background: #2b2b2b !important;
            box-shadow: 4px 4px 0px rgba(0, 0, 0, 0.6) !important;
            height: 100%;
        }
        .card-header {
            background-color: #222222 !important;
            border-bottom: 2px solid #000000 !important;
        }
        .card-header .card-title {
            color: #ffffff !important;
        }
        .card-body {
            color: #cccccc !important;
        }

        /* 按钮风格 */
        .btn-primary, .btn-success, .btn-info, a.btn, button.btn, .main-button {
            background-color: #3c8527 !important;
            border: 2px solid #1e4513 !important;
            border-radius: 0 !important;
            color: #ffffff !important;
            font-family: 'Minecraft', sans-serif !important;
            text-transform: uppercase;
            box-shadow: inset -2px -4px rgba(0,0,0,0.25), inset 2px 2px rgba(255,255,255,0.4) !important;
            transition: all 0.1s;
        }
        .btn-primary:hover, .btn-success:hover, .btn-info:hover, a.btn:hover, button.btn:hover, .main-button:hover {
            background-color: #4aa330 !important;
            box-shadow: inset -2px -4px rgba(0,0,0,0.35), inset 2px 2px rgba(255,255,255,0.5) !important;
            transform: scale(1.02);
            color: #ffffff !important;
            text-decoration: none !important;
        }
        .btn-secondary, .btn-outline-secondary {
            background-color: rgba(255,255,255,0.08) !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            color: #cccccc !important;
            font-family: 'Minecraft', sans-serif !important;
            border-radius: 0 !important;
        }
        .btn-secondary:hover, .btn-outline-secondary:hover {
            background-color: rgba(255,255,255,0.15) !important;
            color: #ffffff !important;
        }
        .btn-danger {
            background-color: #a94442 !important;
            border-color: #6d2a2a !important;
            font-family: 'Minecraft', sans-serif !important;
            border-radius: 0 !important;
        }

        /* 页面布局 */
        .login-box {
            width: 100% !important;
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
        }
        @media (max-width: 576px) {
            .login-box {
                padding: 0.5rem;
            }
        }

        .page-title {
            font-family: 'Minecraft', sans-serif;
            text-shadow: 3px 3px 0 #000;
            color: #ffffff;
            margin-bottom: 1.5rem;
        }

        .back-btn {
            margin-bottom: 1.5rem;
        }

        /* ----- 对齐优化：标签固定宽度，内容右对齐 ----- */
        .event-detail-list {
            list-style: none;
            padding-left: 0;
        }
        .event-detail-list li {
            display: flex;
            align-items: baseline;
            padding: 0.2rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .event-detail-list li strong {
            min-width: 110px;          /* 按需调整，保证最长的“报名时间：”完整显示 */
            flex-shrink: 0;
            color: #aaddaa;
            font-weight: bold;
        }
        .event-detail-list li span {
            flex: 1;
            color: #cccccc;
        }

        .event-content {
            color: #cccccc;
            margin-bottom: 0.75rem;
        }

        .card-footer {
            background-color: transparent !important;
            border-top: none !important;
            padding-top: 0;
        }
    </style>
</head>
<body class="hold-transition login-page">
    </br></br>
    <div class="login-box">
        <!-- 标题与返回按钮 -->
        <div class="d-flex justify-content-between align-items-center back-btn">
            <h3 class="page-title mb-0"><i class="fas fa-calendar-alt"></i> 活动列表</h3>
            <a href="{{ url('/user') }}" class="btn btn-outline-secondary">
                <i class="fas fa-home"></i> 个人中心
            </a>
        </div>

        <!-- 卡片网格 -->
        <div class="row">
            @foreach ($events as $event)
                @php
                    $signupStart = \Carbon\Carbon::parse($event->signup_start);
                    $signupEnd   = \Carbon\Carbon::parse($event->signup_end);
                    $canSignup   = $now->between($signupStart, $signupEnd);
                @endphp

                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">{{ $event->name }}</h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <!-- 内容 -->
                            <div class="event-content">
                                {!! nl2br(e($event->content)) !!}
                            </div>

                            <!-- 对齐的详细信息列表 -->
                            <ul class="event-detail-list">
                                <li><strong>报名时间：</strong><span>{{ $event->signup_start }} ~ {{ $event->signup_end }}</span></li>
                                <li><strong>活动时间：</strong><span>{{ $event->event_start }} ~ {{ $event->event_end }}</span></li>
                                <li><strong>已报名人数：</strong><span>{{ $event->signup_count }} / {{ $event->max_participants }}</span></li>
                            </ul>

                            <!-- 操作按钮 -->
                            <div class="mt-auto">
                                @if ($event->is_signed_up)
                                    @if ($event->is_checked_in)
                                        <button class="btn btn-sm btn-success" disabled><i class="fas fa-check-double"></i> 已签到</button>
                                    @else
                                        <a href="{{ url('/events/' . $event->id . '/checkin') }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-qrcode"></i> 签到
                                        </a>
                                    @endif
                                @elseif ($canSignup)
                                    @if ($event->signup_count >= $event->max_participants)
                                        <button class="btn btn-sm btn-danger" disabled><i class="fas fa-users-slash"></i> 名额已满</button>
                                    @else
                                        <form method="POST" action="{{ route('event.signup', $event->id) }}" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-user-plus"></i> 报名
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        {!! $now->lt($signupStart) ? '<i class="fas fa-clock"></i> 尚未开始' : '<i class="fas fa-times-circle"></i> 已截止' !!}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.0.5/dist/js/adminlte.min.js"></script>
</body>
</html>