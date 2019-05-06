<?php


namespace App\Services\Admin;


use App\Models\Site;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;

class SiteService
{
    // 网站logo
    use SiteLogo;

    //网站ICO
    use SiteIco;

    public function grid()
    {
        $grid = new Grid(new Site());
        $grid->domain('域名');
        $grid->visit('访问量');
        $grid->SiteNavigationArticles('文章数')->display(function ($comments) {
            $count = count($comments);
            return "<span class='label label-warning'>{$count}</span>";
        });
        $grid->navigations('栏目数量')->display(function ($comments) {
            $count = count($comments);
            return "<span class='label label-warning'>{$count}</span>";
        });
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * 保存
     * @param $request
     * @return mixed
     */
    public function store($request)
    {
        DB::transaction(function () use ($request, &$data) {
            //logo临时路径移入正式路径
            $logoPath = $this->moveTmpLogoFile($request->input('logo'));
            // ico临时路径移入正式路径
            $icoPath = $this->moveTmpIcoFile($request->input('ico'));
            $request->offsetSet('logo', $logoPath);
            $request->offsetSet('ico', $icoPath);
            //网站保存
            $data = Site::create($request->input());
            //栏目保存
            $data->navigations()->createMany($request->input('navigations'));
        });
        return $data->load('template', 'navigations');
    }

    /**
     * 生产文件名
     * @return string
     */
    protected function getFileName()
    {
        $str = date('YmdHis') . str_random(8);
        return $str;
    }
}