<?php
namespace App\Admin\Renderable\News;
use Dcat\Admin\Support\LazyRenderable;
use App\Models\Common\News;

class Preview extends LazyRenderable
{

    public function render()
    {
        $this->title = "title";
        $new   = (new News())->find($this->key);
        $imageUrl = ImageUrl($new->cover);

  return <<<HTML
<h1 class="title">{$new->title['RU']}</h1>
<hr>
<div class="cover"><img width="700" height="466"  src="{$imageUrl}"></div>
<div class="content">{$new->content['RU']}</div>
HTML;
    }


}
