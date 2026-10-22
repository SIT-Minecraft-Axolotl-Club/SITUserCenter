# 从 Blessing Skin 6.0.2 升级到 6.0.3

6.0.3 保留上游 Blessing Skin 6.0.2 的功能集，在此基础上完成 Laravel 10 / PHP 8.3 兼容，并移除了本仓库以前单独添加的 SIT 定制功能。

## 一、先确认版本

- 原版本：Blessing Skin 6.0.2
- 新版本：6.0.3
- 框架：Laravel 10
- PHP：8.3
- 数据库：MySQL 8 / MariaDB 10.6+

## 二、升级前备份
至少确认以下内容有备份：

- `.env`
- `storage/textures/`
- `storage/install.lock`
- `plugins/`
- 数据库

## 三、部署 6.0.3 到新目录

不要直接覆盖旧目录：

```bash
cd /www/wwwroot
mkdir skin.sitmc.club.603
tar -xzf SITUserCenter-6.0.3-php83.tar.gz \
  -C skin.sitmc.club.603
```

复制运行数据：

```bash
cp -a skin.sitmc.club/.env skin.sitmc.club.603/.env
rsync -a skin.sitmc.club/storage/textures/ skin.sitmc.club.603/storage/textures/
cp -a skin.sitmc.club/storage/install.lock skin.sitmc.club.603/storage/ 2>/dev/null || true

> 如果旧站启用了`mojang-verification`、`yggdrasil-*` 等插件，先确认它们是否还兼容 6.0.3。6.0.3 主程序不再包含为这些插件添加的 Socialite 依赖。

## 四、迁移和缓存

```bash
cd /www/wwwroot/skin.sitmc.club.603

PHP=/www/server/php/83/bin/php

# 旧站已有 install.lock，但新目录可能没有
touch storage/install.lock

mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs

$PHP artisan config:clear
$PHP artisan migrate --force

rm -f storage/options.php

chown -R www:www storage bootstrap/cache plugins public/lang public/plugins 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache plugins public/lang public/plugins 2>/dev/null || true
chmod 640 .env

$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
```

如果 `route:cache` 因某个旧插件失败，先停用该插件，再执行：

```bash
$PHP artisan route:clear
```

## 五、切换站点

确认新目录可以正常访问后，切换目录：

或者停站后改目录名。然后：

```bash
systemctl restart php-fpm-83
nginx -t && systemctl reload nginx
```

## 六、升级后检查

- 首页 200
- `/auth/login` 200
- `/auth/register` 200
- `/skinlib` 200
- `/user` 需要登录
- 上传皮肤、预览、头像正常
- `/admin` 管理员可访问
- `storage/logs/laravel.log` 无持续报错

## 七、回滚

旧目录和数据库备份不要立即删除。如果新版有问题：

1. 把站点根目录切回旧目录
2. 恢复旧数据库备份
3. 重启 PHP-FPM 和 Nginx
