<?php


namespace App\Services;



use App\Enums\LanguageConfigType;
use App\Models\Base\Language;
use App\Models\Base\LanguageConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\MyRedis;

class LangService extends BaseService
{

    public function getLangList()
    {
//         return Language::query()->where('status', true)->orderBy('order','ASC')->get(['name', 'slug','color', 'icon','value']);
        return Language::GetListCache();
    }


    public function getMLang($slug) {
         return LanguageConfig::query()->where('type','serve')->where('slug',$slug)->first()->content;
    }

    public function getV2Lang($local,$slug)
    {
        $list     = LanguageConfig::query()->where('type', 'serve')->where('slug',$slug)->pluck('content', 'slug');
        $itemLang = data_get($list, $slug);
        return data_get($itemLang, $local);
    }

    public function getLang($local, $slug)
    {
        $allLang = $this->allLang('serve');
        $itemLang = data_get($allLang, $slug);
        return data_get($itemLang, $local);
    }

    public function createLang($slug, $params)
    {

        abort_if(Str::containsAll($slug, ["{", "}"]), 400, "语言标识错误");

        $langContent = $slug;

        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                $langContent .= "---{" . $key . "}";
            }
        }

        foreach (Language::query()->get() as $lang) {
            $content[$lang->slug] = $langContent;
        }

        LanguageConfig::query()->firstOrCreate(['slug' => $slug], [
            'type' => LanguageConfigType::serve,
            'name' => $slug,
            'content' => $content,
            'group' => '自动生成'
        ]);
    }

    public function allLang($type): Collection
    {
        return LanguageConfig::query()->where('type', $type)->pluck('content', 'slug');
    }


    public function getIn18n()
    {
        $list  = $this->allLang("client");
        $local = $this->getLocal();
        return collect($list)->map(function ($item) use ($local) {
            return data_get($item, $local);
        })->all();
    }


}
