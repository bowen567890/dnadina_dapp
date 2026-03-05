<?php

namespace App\Admin\Controllers\Cms;


use App\Enums\LanguageConfigType;
use App\Models\Base\Language;
use App\Models\Base\LanguageConfig;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Str;

class LanguageController extends AdminController
{
    protected $title = "语言包";
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new LanguageConfig(), function (Grid $grid) {

            $local = request('local', null);

            $grid->column('id','No.')
               ->display(function($value){ return 'No.'.$value;})
               ->bold();

            $grid->column('type','类型')->width(100)->using(LanguageConfigType::asSelectArray())->filter();
            $grid->column('name','名称')->width(300);
            $grid->column('slug','标识')->width(300)->copyable()->filter(Grid\Column\Filter\Like::make());
            $grid->column('group', '分组')->width(180)->filter();
            $grid->combine('语言', ['type','name','slug','group']);
            $grid->column('translate','翻译')
              ->if(function(){return empty($this->content) ? false : true;})
              ->display('查看')
              ->expand(function(){
                $translate = json_encode($this->content,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return '<pre class="dump" style="background: #120f12;color: #fff!important;flex: 1 1 auto;min-height: 1px;padding: 1.25rem;";>'.$translate.'</pre>';
            });


            $grid->column('updated_at')->width(200)->sortable();
            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('slug','标识')->width(2);
                     $filter->like('name','名称')->width(2);
                $filter->equal('group','分组')->width(2)->select(LanguageConfig::query()->groupBy('group')->pluck('group','group'));
                $filter->where('local', function () {}, '语言')->select(Language::query()->where('status', true)->pluck('name', 'slug'))->width(2);
                $filter->where('content', function ($q) {$q->where('content', 'like', "%$this->input%");},'翻译内容')->width(2);
                $filter->between('created_at')->date()->width(2);
            });

          //  $grid->quickSearch(['name', 'slug'])->auto(false);
            $grid->disableRowSelector();
            $grid->disableEditButton();
            $grid->disableViewButton();
            $grid->showQuickEditButton();

        });
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {


        return Form::make(new LanguageConfig(), function (Form $form) {

            $form->radio('type')->options(LanguageConfigType::asSelectArray())->required();

            $form->text('name')->required();
            $form->text('slug')->required()->help("会自动转为大写");
            $form->text('group', '分组')->options(LanguageConfig::AllGroup())->required()->help("方便使用分组查询");
            $form->embeds('content', '内容', function (Form\EmbeddedForm $form) {

                foreach (Language::query()->where('status',1)->where('show',1)->get() as $lang) {
                    $form->textarea($lang->slug, $lang->name)->help("可以使用{0}来替换变量")->required();
                }

            });

            $form->saving(function (Form $form) {
                $form->slug = Str::upper($form->slug);

                $form->slug = str_replace("-", "_", $form->slug);

            });

        });
    }
}
