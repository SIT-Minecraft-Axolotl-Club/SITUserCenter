<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIT-Minecraft | 视频会议</title>
    <!-- Bootstrap CSS -->
    <link href="https://www.bootcdn.cn/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ app_style_url() }}" rel="stylesheet" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://unpkg.com/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" crossorigin>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ app_favicon_url() }}">
    <link rel="icon" type="image/png" href="{{ app_favicon_url() }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ app_favicon_url() }}" sizes="180x180">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="https://skin.sitmc.club">SIT-Minecraft</a>
        </div>
        <div class="card">
                        <div class="card-body login-card-body">
                <p class="login-box-msg">请选择您需要使用的会议室</p>
                <a href="https://oauth.sitmc.club/?room=SIT-Minecraft-1" class="btn btn-block btn-outline-secondary btn-link-custom">社团会议-1</a>
                <a href="https://oauth.sitmc.club/?room=SIT-Minecraft-2" class="btn btn-block btn-outline-secondary btn-link-custom">社团会议-2</a>
                <a href="https://oauth.sitmc.club/?room=SIT-Minecraft-3" class="btn btn-block btn-outline-secondary btn-link-custom">社团会议-3</a>
                </br>
                                                        <div class="callout callout-info">
                        本服务由上海应用技术大学Minecraft社提供，QQ群： <a href='https://qm.qq.com/q/EuKJh8lWf0' target='_blank'>623697015</a>
            </div>
    </div>

        </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var loginForm = document.querySelector('form');
        loginForm.addEventListener('submit', function() {
            var loginButton = document.getElementById('loginButton');
            loginButton.disabled = true; // 禁用按钮
            loginButton.innerText = '等待中...'; // 更改按钮文字
        });
    });
    </script>
</body>
</html>
