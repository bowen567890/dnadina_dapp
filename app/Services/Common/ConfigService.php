<?php

namespace App\Services\Common;

use App\Models\Base\Setting;
use App\Services\BaseService;

class ConfigService extends BaseService
{
    public array $allConfig = [];

    public function __construct(){
        $this->update();
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getConfig($key,$default = null): mixed
    {
        return $this->allConfig[$key] ?? $default;
    }

    public function update(): void
    {
        $setting = Setting::query()->where('channel_id', 1)->firstOrFail();
        foreach (collect($setting) as $key => $value) {
            $this->allConfig[$key] = $value;
        }
    }

}
