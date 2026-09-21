<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>实名认证 - SIT-Minecraft</title>
    <!-- Bootstrap CSS -->
    <link href="https://www.bootcdn.cn/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/app/style.7eb5d06.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://unpkg.com/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" crossorigin>
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://skin.sitmc.club/app/3.ico">
    <link rel="icon" type="image/png" href="https://skin.sitmc.club/app/3.ico" sizes="192x192">
    <link rel="apple-touch-icon" href="https://skin.sitmc.club/app/3.ico" sizes="180x180">

    {{-- AdminLTE login-page 基础样式，确保页面居中和布局 --}}
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
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            {{-- 站点名称或Logo，链接到首页 --}}
            <a href="{{ url('/') }}">SIT-Minecraft</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body"> {{-- 使用 login-card-body 类 --}}
                {{-- 页面提示信息 --}}
                <p class="login-box-msg">为了进行活动签到，请先完成实名</p>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('success') }}
                    </div>
                @endif

                 @if(session('info'))
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('info') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $errors->first() }} {{-- 您也可以循环显示所有错误 $errors->all() --}}
                    </div>
                @endif

                {{-- 实名认证表单 --}}
                {{-- action 指向您在 routes/web.php 中定义的 POST 路由 --}}
                <form method="POST" action="{{ route('profile.updateRealName') }}"> {{-- 假设路由命名为 profile.updateRealName --}}
                    @csrf {{-- 添加 CSRF 保护字段 --}}

                    <div class="input-group mb-3"> {{-- 使用 input-group 和 mb-3 类 --}}
                        {{-- 真实姓名输入框 --}}
                        {{-- name="real_name_input" 必须与您后端验证和处理逻辑中的字段名一致 --}}
                        <input type="text" id="real_name_input" name="real_name_input" class="form-control" placeholder="真实姓名" required maxlength="255" value="{{ old('real_name_input') }}"> {{-- 保留输入值，设置 max length --}}
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span> {{-- 使用用户图标 --}}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {{-- 提交按钮 --}}
                            <button type="submit" id="submitRealNameButton" class="btn btn-primary btn-block">提交实名信息</button> {{-- 修改按钮文本和 ID --}}
                        </div>
                    </div>
                </form>

                {{-- 返回链接 --}}
                 <p class="mb-1 mt-3"> {{-- 调整间距 --}}
                     <a href="{{ url('/events') }}">返回活动列表</a> {{-- 链接回活动列表 --}}
                 </p>
                 
                 </br>
                 
                                     <div class="callout callout-info">
                       信息仅用于活动签到，我们不对您的实名进行任何验证，因为您个人导致的实名信息错误，无法导入学校系统进行学分录入请自行负责。
                        </div>
                    <div class="callout callout-info">
                       如果您需要修改已经存在的实名信息，直接输入新的信息即可。
                        </div>
            </div>
        </div>
        </br>
    </div>

    {{-- 引入与 AdminLTE 3.x 兼容的 JS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.0.5/dist/js/adminlte.min.js"></script>

    {{-- 提交时禁用按钮的 JS (可选) --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var realNameForm = document.querySelector('form'); // 获取页面中的第一个/唯一表单
            if (realNameForm) {
                realNameForm.addEventListener('submit', function() {
                    var submitButton = document.getElementById('submitRealNameButton'); // 使用正确的按钮 ID
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerText = '提交中...';
                        // 可以在这里添加一个加载中的 spinner 图标
                    }
                });
            }
        });
    </script>
</body>
</html>