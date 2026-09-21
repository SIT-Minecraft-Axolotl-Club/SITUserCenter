<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>活动签到</title>

    <!-- Bootstrap -->
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

<style>

@font-face {

    font-family:'Minecraft';

    src:url('https://cdn.jsdelivr.net/gh/South-Paw/typeface-minecraft@master/public/MinecraftRegular-Bmg3.woff2')
    format('woff2');

}


/* ==============================
   Eduroam 登录页 Minecraft主题
============================== */


body.login-page {


    background:#1a1a1a!important;

}


/* 登录整体 */

.login-box {


    width:360px;

}



/* Logo */

.login-logo,
.login-logo a {


    font-family:'Minecraft',sans-serif!important;

    color:#fff!important;

    text-shadow:
    3px 3px 0 #000!important;

}



.login-logo a:hover {


    text-decoration:none;

}



/* 卡片 */

.login-box .card {


    background:#2b2b2b!important;

    border:2px solid #000!important;

    border-radius:0!important;


    box-shadow:
    4px 4px 0 rgba(0,0,0,.6)!important;


}



/* 内容 */

.login-card-body {


    background:transparent!important;


}



/* 提示文字 */


.login-box-msg {


    color:#fff!important;

    font-family:'Minecraft',sans-serif;

    text-shadow:
    1px 1px #000;


}



/* 错误提示 */

.alert {


    border-radius:0!important;


}



.alert-danger {


    background:#661111!important;

    border:2px solid #aa3333!important;

    color:white!important;


}



.alert-success {


    background:#164d16!important;

    border:2px solid #3c8527!important;

    color:white!important;


}




/* 输入框 */


.login-box .form-control {


    background:
    rgba(255,255,255,.08)!important;


    border:

    1px solid rgba(255,255,255,.25)!important;


    color:white!important;


    border-radius:0!important;


}



.login-box .form-control::placeholder {


    color:#aaa!important;


}



.login-box .form-control:focus {


    background:
    rgba(255,255,255,.15)!important;


    border-color:#3c8527!important;


    box-shadow:
    0 0 0 .2rem rgba(60,133,39,.25)!important;


}




/* 输入框图标 */


.login-box .input-group-text {


    background:
    rgba(255,255,255,.08)!important;


    border:

    1px solid rgba(255,255,255,.25)!important;


    color:#ccc!important;


    border-radius:0!important;


}




/* 所有按钮 */

.login-box .btn {


    border-radius:0!important;

    font-family:'Minecraft',sans-serif!important;


}



/* 登录按钮 */


.login-box .btn-primary {


    background:#3c8527!important;


    border:

    2px solid #1e4513!important;


    color:white!important;


    box-shadow:


    inset -2px -4px rgba(0,0,0,.25),


    inset 2px 2px rgba(255,255,255,.35)!important;


}



.login-box .btn-primary:hover {


    background:#4aa330!important;


    transform:scale(1.02);


}



/* 其它登录按钮 */


.login-box a.bg-light {


    background:#222!important;


    border:

    2px solid #555!important;


    color:white!important;


}



.login-box a.bg-light:hover {


    background:#333!important;


}



/* 提示框 */


.login-box .callout {


    background:#222!important;


    border-left:

    5px solid #3c8527!important;


    color:#ccc!important;


    padding:15px;


}



.login-box .callout a {


    color:#3c8527!important;


}



.login-box .callout a:hover {


    color:#4aa330!important;


}



/* 图标 */

.login-box i {


    color:#ccc;


}



</style>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="https://skin.sitmc.club">SIT-Minecraft</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">请输入签到码进行签到</p>

                {{-- ✅ 成功提示 --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="关闭">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- ❌ 错误提示 --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle mr-2"></i>{{ $errors->first() }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="关闭">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('event.checkin', $event->id) }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="sign_code" name="sign_code" class="form-control" placeholder="签到码" required maxlength="7">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" id="submitButton" class="btn btn-primary btn-block">提交签到</button>
                        </div>
                    </div>
                    </br>
                </form>
            </div>
        </div>

        <p class="mt-3 text-center">
            <a href="{{ url('/events') }}"><i class="fas fa-arrow-left"></i> 返回活动列表</a>
        </p>
    </div>

    <!-- 必要的 JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector('form');
        form.addEventListener('submit', function() {
            var btn = document.getElementById('submitButton');
            btn.disabled = true;
            btn.innerText = '提交中...';
        });
    });
    </script>
</body>
</html>
