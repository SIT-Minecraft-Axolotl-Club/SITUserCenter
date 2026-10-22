<!DOCTYPE html>

<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>认证账户 - SIT-Minecraft</title>

<link href="https://www.bootcdn.cn/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<link href="{{ app_style_url() }}" rel="stylesheet" crossorigin="anonymous">

<link rel="stylesheet"
      href="https://unpkg.com/@fortawesome/fontawesome-free@5.15.4/css/all.min.css"
      crossorigin>

<link rel="shortcut icon" href="{{ app_favicon_url() }}">

<style>

    @font-face {
        font-family: 'Minecraft';
        src: url('https://cdn.jsdelivr.net/gh/South-Paw/typeface-minecraft@master/public/MinecraftRegular-Bmg3.woff2')
        format('woff2');
    }


    /* ==============================
       认证账户页面 Minecraft 主题
    ============================== */


    body.auth-only-page {

        background: #1a1a1a !important;

        min-height: 100vh;

        display: flex;

        align-items: center;

        justify-content: center;

        margin: 0;

        padding: 20px;

    }


    /* 页面整体 */

    .auth-only-box {

        width: 360px;

        max-width: 100%;

    }


    /* Logo */

    .auth-only-logo {

        text-align: center;

        margin-bottom: 20px;

    }


    .auth-only-logo a {

        font-family: 'Minecraft', sans-serif !important;

        color: #fff !important;

        font-size: 32px;

        text-shadow:
            3px 3px 0 #000 !important;

        text-decoration: none;

    }


    .auth-only-logo a:hover {

        text-decoration: none;

    }


    /* 卡片 */

    .auth-only-box .card {

        background: #2b2b2b !important;

        border: 2px solid #000 !important;

        border-radius: 0 !important;

        box-shadow:
            4px 4px 0 rgba(0, 0, 0, .6) !important;

    }


    /* 卡片内容 */

    .auth-only-card-body {

        background: transparent !important;

        padding: 32px 28px;

    }


    /* 图标 */

    .auth-only-icon {

        text-align: center;

        margin-bottom: 20px;

    }


    .auth-only-icon i {

        color: #ccc;

        font-size: 42px;

        text-shadow:
            2px 2px 0 #000;

    }


    /* 标题 */

    .auth-only-title {

        margin: 0 0 14px;

        text-align: center;

        font-family: 'Minecraft', sans-serif !important;

        font-size: 23px;

        font-weight: normal;

        color: #fff !important;

        text-shadow:
            2px 2px 0 #000;

    }


    /* 提示文字 */

    .auth-only-message {

        margin: 0;

        text-align: center;

        color: #ccc;

        font-size: 14px;

        line-height: 1.8;

    }


    /* OAuth 提示 */

    .auth-only-callout {

        margin-top: 24px;

        padding: 14px 15px;

        background: #222 !important;

        border-left: 5px solid #3c8527 !important;

        color: #bbb;

        font-size: 13px;

        line-height: 1.7;

    }


    .auth-only-callout strong {

        color: #fff;

        font-weight: normal;

    }


    /* 返回首页按钮 */

    .auth-only-button {

        margin-top: 24px;

    }


    .auth-only-button .btn {

        border-radius: 0 !important;

        font-family: 'Minecraft', sans-serif !important;

        background: #3c8527 !important;

        border: 2px solid #1e4513 !important;

        color: #fff !important;

        box-shadow:
            inset -2px -4px rgba(0, 0, 0, .25),
            inset 2px 2px rgba(255, 255, 255, .35) !important;

    }


    .auth-only-button .btn:hover {

        background: #4aa330 !important;

        transform: scale(1.02);

    }


    /* 退出登录 */

    .auth-only-logout {

        margin-top: 12px;

        text-align: center;

    }


    .auth-only-logout button {

        padding: 0;

        border: 0;

        background: transparent;

        color: #888;

        font-size: 13px;

        cursor: pointer;

    }


    .auth-only-logout button:hover {

        color: #ccc;

    }


    /* 底部 */

    .auth-only-footer {

        margin-top: 18px;

        text-align: center;

        color: #666;

        font-size: 12px;

    }


    @media (max-width: 480px) {

        body.auth-only-page {

            padding: 15px;

        }

        .auth-only-card-body {

            padding: 28px 22px;

        }

        .auth-only-logo a {

            font-size: 28px;

        }

    }

</style>

</head>

<body class="hold-transition auth-only-page">

<div class="auth-only-box">

<div class="auth-only-logo">

    <a href="{{ url('/') }}">

        SIT-Minecraft

    </a>

</div>


<div class="card">


    <div class="card-body auth-only-card-body">


        <div class="auth-only-icon">

            <i class="fas fa-user-shield"></i>

        </div>


        <h1 class="auth-only-title">

            认证账户

        </h1>


        <p class="auth-only-message">

            此账户仅用于 OAuth 认证，无法使用普通用户功能。

        </p>


        <div class="auth-only-callout">

            <strong>

                <i class="fas fa-info-circle mr-1"></i>

                OAuth 认证账户

            </strong>

            <br>

            此账户仅用于第三方应用的身份认证。

            您仅可以正常使用 OAuth 

            登录相关服务。

        </div>


        <div class="auth-only-button">

            <a href="{{ url('/') }}"
               class="btn btn-primary btn-block">

                <i class="fas fa-home mr-1"></i>

                返回首页

            </a>

        </div>


        <div class="auth-only-logout">

            <form action="{{ route('auth.logout') }}"
                  method="post">

                @csrf

                <button type="submit">

                    <i class="fas fa-sign-out-alt mr-1"></i>

                    退出登录

                </button>

            </form>

        </div>


    </div>

</div>


<div class="auth-only-footer">

    SIT-Minecraft

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
