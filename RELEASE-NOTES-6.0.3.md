# SITUserCenter 6.0.3（PHP 8.3）

本版本保留上游 Blessing Skin 6.0.2 的功能集，并完成 PHP 8.3 兼容。

## 变更

- 升级到 Laravel 10，支持 PHP 8.3
- 移除以前单独添加的 SIT 定制功能：
  - 活动创建、报名、签到、活动导出
  - Eduroam 登录
  - OA 登录
  - 实名认证
  - `auth-only` 认证账户
  - Janus 独立 Node 应用
- 恢复上游登录/注册页面，不再注入 SIT 定制按钮
- 保留上游皮肤库、角色、衣柜、举报、管理后台等全部 6.0.2 功能

## 升级

从 6.0.2 升级请阅读：

[UPGRADE-6.0.2-to-6.0.3.md](UPGRADE-6.0.2-to-6.0.3.md)

## 安装包

- `SITUserCenter-6.0.3-php83.zip`
- `SITUserCenter-6.0.3-php83.tar.gz`

包内已包含 `vendor`（no-dev）、`public/app`、`resources/views/assets`，部署时不需要再执行 `composer install` 或前端构建。

## 环境

- PHP 8.3
- MySQL 8 / MariaDB 10.6+
- Nginx，站点根目录指向 `public/`

## 校验

```text
SHA256 (zip):    1BED64CD8A1AB190F385C9B3FF112419E233FD05A403E71ECD1447803E30F4A3
SHA256 (tar.gz): D500A4958D6306C4192D24144232686F3FCF8FA6E73BE03D860A12061C14421A
```
