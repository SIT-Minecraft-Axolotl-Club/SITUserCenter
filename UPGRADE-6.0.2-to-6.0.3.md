# 从 Blessing Skin 6.0.2 升级到 6.0.3

本文适用于从 **上游原版 Blessing Skin 6.0.2** 升级到 **6.0.3** 的通用部署场景。

6.0.3 保留上游 6.0.2 的功能集，并完成 Laravel 10 / PHP 8.3 兼容。它不依赖特定的面板、域名、目录或站点定制。

## 一、版本与环境

- 原版本：Blessing Skin 6.0.2
- 新版本：6.0.3
- 框架：Laravel 10
- PHP：8.3
- 数据库：MySQL 8 / MariaDB 10.6+
- Web 服务器：Nginx 或 Apache

PHP 扩展至少需要：

```text
openssl, pdo, pdo_mysql, mbstring, tokenizer, ctype, json,
fileinfo, zip, gd, imagick, curl, xml, intl
```

本文命令里的变量请按实际环境修改：

```bash
OLD=/var/www/blessing-skin
NEW=/var/www/blessing-skin-6.0.3
WEB_USER=www-data
PHP=/usr/bin/php8.3
```

- Debian/Ubuntu 的 Web 用户通常是 `www-data`
- CentOS/宝塔面板通常是 `www`
- PHP 路径以 `php -v` 和面板实际路径为准

## 二、升级前备份

先备份旧站和数据库：

```bash
tar -czf blessing-skin-6.0.2-backup-$(date +%F).tar.gz \
  "$OLD" \
  --exclude="$OLD/vendor" \
  --exclude="$OLD/node_modules"

mysqldump -u DB_USER -p DB_NAME > blessing-skin-$(date +%F).sql
```

至少确认以下内容有备份：

- `.env`
- `storage/textures/`
- `storage/oauth-private.key`
- `storage/oauth-public.key`
- `storage/install.lock`
- `plugins/`
- `public/plugins/`
- 数据库

不要在没有数据库备份的情况下执行 `migrate`。

## 三、部署 6.0.3 到新目录

不要直接覆盖旧目录。先解压到新目录：

```bash
mkdir -p "$NEW"
tar -xzf SITUserCenter-6.0.3-php83.tar.gz -C "$NEW"
```

如果使用 zip 包：

```bash
unzip SITUserCenter-6.0.3-php83.zip -d "$NEW"
```

复制旧站运行数据：

```bash
cp -a "$OLD/.env" "$NEW/.env"
rsync -a "$OLD/storage/textures/" "$NEW/storage/textures/"
cp -a "$OLD/storage/oauth-private.key" "$NEW/storage/" 2>/dev/null || true
cp -a "$OLD/storage/oauth-public.key" "$NEW/storage/" 2>/dev/null || true
cp -a "$OLD/storage/install.lock" "$NEW/storage/" 2>/dev/null || true
```

插件按需复制：

```bash
rsync -a "$OLD/plugins/" "$NEW/plugins/"
rsync -a "$OLD/public/plugins/" "$NEW/public/plugins/" 2>/dev/null || true
```

> 6.0.3 使用 Laravel 10，旧版 6.0.2 插件不一定兼容。复制插件后要逐个测试；不兼容的插件先停用。

## 四、配置 .env

新目录的 `.env` 来自旧站，重点检查：

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://你的域名
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=你的库名
DB_USERNAME=你的用户
DB_PASSWORD=你的密码
```

以下值必须保留旧站的，不能重新生成：

- `APP_KEY`
- `PWD_METHOD`
- `SALT`
- `PASSPORT_PRIVATE_KEY` / `PASSPORT_PUBLIC_KEY`（如果写在 `.env`）

否则已有用户可能无法登录，OAuth token 也会失效。

## 五、权限和目录

```bash
cd "$NEW"

mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs

chown -R "$WEB_USER":"$WEB_USER" \
  storage bootstrap/cache plugins public/lang public/plugins
chmod -R ug+rwX \
  storage bootstrap/cache plugins public/lang public/plugins
chmod 640 .env
```

如果 `public/lang` 或 `public/plugins` 不存在，可以忽略对应的 chown/chmod 报错。

## 六、数据库迁移和缓存

先确认 `storage/install.lock` 存在：

```bash
touch storage/install.lock
```

然后执行：

```bash
$PHP artisan config:clear
$PHP artisan migrate --force

# 旧站的 options 缓存可能导致新版本读到旧版本号
rm -f storage/options.php

$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
```

如果 `route:cache` 因旧插件失败，先停用该插件，再执行：

```bash
$PHP artisan route:clear
```

如果 `view:cache` 报目录不存在：

```bash
mkdir -p resources/views/overrides
$PHP artisan view:cache
```

不要默认启用 `options:cache`，它会冻结后台设置。

## 七、切换 Web 根目录

### Nginx

把站点 `root` 指向新目录的 `public`：

```nginx
root /var/www/blessing-skin-6.0.3/public;
index index.php index.html;

location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

检查并重载：

```bash
nginx -t
systemctl reload nginx
```

### Apache

把 `DocumentRoot` 指向：

```text
/var/www/blessing-skin-6.0.3/public
```

确认 `AllowOverride All` 已开启，然后重启 Apache。

### PHP-FPM

```bash
systemctl restart php8.3-fpm
```

宝塔环境通常对应：

```bash
systemctl restart php-fpm-83
```

## 八、升级后检查

- 首页返回 200
- `/auth/login` 返回 200
- `/auth/register` 返回 200
- `/skinlib` 返回 200
- `/user` 登录后正常
- 上传皮肤、预览、头像正常
- `/admin` 管理员可访问
- `storage/logs/laravel.log` 没有持续报错

如果出现 500，先看：

```bash
tail -n 100 storage/logs/laravel.log
```

## 九、回滚

旧目录和数据库备份不要立即删除。如果新版有问题：

1. 将 Web 根目录切回旧目录
2. 恢复旧数据库备份
3. 重启 PHP-FPM 和 Web 服务器

## 十、注意事项

1. 6.0.3 是 Laravel 10 版本，旧 6.0.2 插件需要确认兼容性。
2. 发布包已经包含 `vendor`、`public/app`、`resources/views/assets`，正常部署不需要再执行 `composer install` 或前端构建。
3. 如果从源码部署，执行：

```bash
composer install --no-dev --optimize-autoloader
```

4. 不要运行 `key:generate` 或 `passport:keys` 来“修复”已有站点。
5. `storage/install.lock` 决定程序是否认为已安装；旧站迁移时一定要带上。
6. 旧站如果有自定义路由、控制器或视图，不要直接覆盖到 6.0.3 的 `app/`、`routes/`、`resources/views/`，否则可能重新引入不兼容代码。
