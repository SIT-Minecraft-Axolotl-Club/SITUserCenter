<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录 - 统一认证</title>
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
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="https://skin.sitmc.club">SIT-Minecraft</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">请使用您的统一认证账户登录</p>

                @if ($errors->any())
                    <p style="color: red; font-size: 14px; text-align: center;">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </p>
                @endif

                <form action="{{ route('oa.handleLogin') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="学号" name="student_number" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user-graduate"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="OA 密码" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <!-- 验证码 -->
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="验证码" name="captcha" required>
                        <div class="input-group-append">
                            <img id="captchaImage" src="{{ $captcha }}" style="cursor:pointer; height: 38px;" title="点击刷新验证码" onclick="refreshCaptcha()">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" id="loginButton" class="btn btn-primary btn-block">登录</button>
                        </div>
                    </div>

                    <br>
                    <div class="mb-1">其它登录方式</div>
                    <a href="https://skin.sitmc.club/auth/login" class="btn btn-block bg-light border-secondary">
                        <i class="fab fa-mua"></i>&nbsp;
                        使用 校园邮箱/用户名 登录
                    </a>
                              <a href="https://skin.sitmc.club/auth/eduroam/login" class="btn btn-block bg-light border-secondary">
                            <i class="fab fa-mua"></i>&nbsp;
                            使用 Eduroam 登录
                          </a>
                    <br>
                    <div class="callout callout-info">
                        如果您尚未注册，我们将会自动帮您注册，注册账户即表明你同意
                        <a href="https://www.sitmc.club/privacy/" target="_blank">《SIT-Minecraft 隐私政策》</a>
                    </div>
                    <div class="callout callout-info">
                        使用该方式注册，您必须先登录「信息门户」（myportal.sit.edu.cn）修改初始密码
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function refreshCaptcha() {
            const img = document.getElementById('captchaImage');
            img.src = '{{ route("oa.showLoginForm") }}?refresh=1&_=' + new Date().getTime(); // 防缓存
        }

        document.addEventListener("DOMContentLoaded", function() {
            var loginForm = document.querySelector('form');
            loginForm.addEventListener('submit', function() {
                var loginButton = document.getElementById('loginButton');
                loginButton.disabled = true;
                loginButton.innerText = '等待中...';
            });
        });
    </script>
</body>
</html>
