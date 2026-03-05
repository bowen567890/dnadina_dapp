<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class OpcacheClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opcache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear OPcache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 方法1: 直接调用 PHP 函数清理 OPcache (如果在 CLI 模式下启用了 OPcache)
        if (function_exists('opcache_reset')) {
            if (opcache_reset()) {
                $this->info('OPcache cleared successfully using opcache_reset()');
                return Command::SUCCESS;
            } else {
                $this->warn('Failed to clear OPcache using opcache_reset()');
            }
        } else {
            $this->warn('opcache_reset() function is not available');
        }

        // 方法2: 通过 HTTP 请求清理 OPcache (适用于 Web 服务器环境)
        try {
            $opcacheUrl = config('opcache.url') . '/opcache-clear';
            $response = Http::timeout(30)->get($opcacheUrl);
            
            if ($response->successful()) {
                $this->info('OPcache cleared successfully via HTTP request');
                return Command::SUCCESS;
            } else {
                $this->warn('Failed to clear OPcache via HTTP request: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->warn('HTTP request to clear OPcache failed: ' . $e->getMessage());
        }

        // 方法3: 创建一个临时文件来触发 OPcache 重载
        try {
            $tempFile = storage_path('opcache_reset_' . time() . '.php');
            file_put_contents($tempFile, '<?php opcache_reset(); echo "OPcache cleared"; ?>');
            
            // 执行临时文件
            $output = shell_exec("php $tempFile");
            unlink($tempFile);
            
            if (strpos($output, 'OPcache cleared') !== false) {
                $this->info('OPcache cleared using temporary file method');
                return Command::SUCCESS;
            }
        } catch (\Exception $e) {
            $this->warn('Temporary file method failed: ' . $e->getMessage());
        }

        $this->error('All OPcache clearing methods failed. Please check your PHP configuration.');
        return Command::FAILURE;
    }
}
