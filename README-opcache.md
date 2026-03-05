# OPcache 管理解决方案

## 问题描述
项目中使用了 `php artisan opcache:clear` 命令，但该命令未定义，导致以下错误：
```
There are no commands defined in the "opcache" namespace.
```

## 解决方案

### 1. 创建了 OPcache Clear 命令
**文件**: `app/Console/Commands/OpcacheClearCommand.php`

- 提供多种 OPcache 清理方法
- 自动回退机制，确保在不同环境下都能工作
- 支持 CLI 和 Web 环境

### 2. 创建了 Web 端点
**文件**: `public/opcache-clear.php`

- 提供 HTTP 接口清理 OPcache
- 内置安全机制，防止未授权访问
- 支持本地网络和密钥认证

### 3. 更新了缓存脚本
**文件**: `cache.sh`

- 增加了错误处理和回退机制
- 提供详细的执行状态反馈
- 在主命令失败时自动尝试备选方案

### 4. 完善了配置文件
**文件**: `config/opcache.php`

- 添加了详细的配置选项
- 包含安全设置
- 支持环境变量配置

## 使用方法

### 1. Artisan 命令
```bash
php artisan opcache:clear
```

### 2. 执行缓存脚本
```bash
bash cache.sh
```

### 3. HTTP 请求 (仅限本地)
```bash
curl http://localhost/opcache-clear.php
```

## 环境变量
可在 `.env` 文件中添加以下配置：

```env
OPCACHE_URL=http://localhost
OPCACHE_SECURITY_ENABLED=true
OPCACHE_SECRET_KEY=your_secret_key
```

## 安全说明

1. **本地访问限制**: HTTP 端点仅允许本地网络访问
2. **密钥验证**: 支持基于日期的动态密钥验证
3. **IP 白名单**: 可配置允许的 IP 地址范围

## 故障排除

如果命令仍然失败，请检查：

1. **PHP OPcache 扩展**: 确保已安装并启用
2. **CLI 配置**: 检查 `opcache.enable_cli` 设置
3. **权限问题**: 确保脚本有执行权限
4. **网络配置**: 确保可以访问 localhost

## 测试命令工作状态

```bash
# 测试 Artisan 命令
php artisan opcache:clear

# 测试完整缓存清理
bash cache.sh

# 查看 OPcache 状态 (需要创建 info.php)
echo "<?php phpinfo(); ?>" > public/info.php
curl http://localhost/info.php | grep -i opcache
```
