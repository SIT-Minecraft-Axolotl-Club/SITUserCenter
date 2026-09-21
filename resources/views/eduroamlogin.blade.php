<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>登录 - Eduroam</title>

    <link href="https://www.bootcdn.cn/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="/app/style.7eb5d06.css" rel="stylesheet" crossorigin="anonymous">

    <link rel="stylesheet"
          href="https://unpkg.com/@fortawesome/fontawesome-free@5.15.4/css/all.min.css"
          crossorigin>


    <link rel="shortcut icon" href="https://skin.sitmc.club/app/3.ico">


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

        <a href="https://skin.sitmc.club">

            SIT-Minecraft

        </a>

    </div>



    <div class="card">


        <div class="card-body login-card-body">


            <p class="login-box-msg">

                请使用您的 Eduroam 账户登录

            </p>



            @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="fas fa-times-circle mr-2"></i>

                @foreach ($errors->all() as $error)

                    {{ $error }}

                @endforeach


                <button type="button"
                        class="close"
                        data-dismiss="alert">

                    <span>&times;</span>

                </button>


            </div>

            @endif




            @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">


                <i class="fas fa-check-circle mr-2"></i>

                {{ session('success') }}


                <button type="button"
                        class="close"
                        data-dismiss="alert">

                    <span>&times;</span>

                </button>


            </div>

            @endif




            <form action="/auth/eduroam/login"
                  method="post">


                @csrf



                <div class="input-group mb-3">


                    <input type="text"
                           class="form-control"
                           placeholder="学号"
                           name="student_number"
                           required>


                    <div class="input-group-append">

                        <div class="input-group-text">

                            <span class="fas fa-user-graduate"></span>

                        </div>

                    </div>


                </div>





                <div class="input-group mb-3">


                    <input type="password"
                           class="form-control"
                           placeholder="OA 密码"
                           name="password"
                           required>



                    <div class="input-group-append">

                        <div class="input-group-text">

                            <span class="fas fa-lock"></span>

                        </div>

                    </div>


                </div>





                <button type="submit"
                        id="loginButton"
                        class="btn btn-primary btn-block">


                    登录


                </button>



                <br>



                <div class="mb-1">

                    其它登录方式

                </div>



                <a href="https://skin.sitmc.club/auth/login"
                   class="btn btn-block bg-light border-secondary">


                    <i class="fas fa-envelope"></i>

                    &nbsp; 使用校园邮箱/用户名登录


                </a>




                <br>



                <div class="callout callout-info">


                    如果您尚未注册，我们将会自动帮您注册。
                    注册账户即表明你同意

                    <a href="https://www.sitmc.club/privacy/"
                       target="_blank">

                        《SIT-Minecraft 隐私政策》

                    </a>


                </div>
                <div class="callout callout-info">


                    OA：这里指 

                    <a href="https://authserver.sit.edu.cn/authserver/login"
                       target="_blank">

                        上海应用技术大学信息门户

                    </a>，新生需要使用初始密码登录并修改初始密码后才可以通过认证。


                </div>


                <div class="callout callout-info">


                    该方法使用 Eduroam 进行验证，
                    关于 Eduroam 的更多信息请参阅


                    <a href="https://www.eduroam.edu.cn/ljeduroam1.htm"
                       target="_blank">

                        什么是 Eduroam？

                    </a>


                </div>



            </form>



        </div>


    </div>


</div>




<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>



<script>

document.addEventListener("DOMContentLoaded",function(){


    document.querySelector('form')
    .addEventListener('submit',function(){


        let btn=document.getElementById('loginButton');

        btn.disabled=true;

        btn.innerText='等待中...';


    });


});


</script>


</body>

</html>