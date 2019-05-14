<?php


namespace App\Services\Admin;


use App\Models\Site;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;
use Overtrue\Pinyin\Pinyin;

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
        $grid->articles('文章数')->display(function ($comments) {
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
            //保存logo
            $logoPath = $this->uploadSiteLogo();
            // 保存ico
            $icoPath = $this->uploadSiteIco();
            $request->offsetSet('logo', $logoPath);
            $request->offsetSet('ico', $icoPath);
            //网站保存
            $data = Site::create($request->input());
            //栏目保存
            if ($request->input('navigations')) {
                $navigations = explode(',', $request->input('navigations'));
                $pinyin = new Pinyin();
                $navigationsArray = array_map(function ($v) use ($pinyin) {
                    $pinyiName = $pinyin->sentence($v);
                    $pinyiName = str_replace(' ', '', $pinyiName);
                    return ['name' => $v, 'pinyin' => $pinyiName];
                }, $navigations);
                $data->navigations()->createMany($navigationsArray);
            }

        });
        return $data->load('template', 'navigations');
    }

    /**
     * 修改网站
     * @param $request
     * @param $id
     * @return mixed
     */
    public function update($request, $id)
    {
        $data = Site::findOrFail($id);
        // 检查logo、ico是否变动
        $request = $this->checkFile($request);
        DB::transaction(function () use ($request, &$data) {
            $data->update($request->input());
            $dbNavNameString = implode(',', $data->navigations()->pluck('name')->all());
            // request栏目 与数据库的不一致
            if ($dbNavNameString != $request->input('navigations')) {
                $data->navigations()->delete();
                if ($request->input('navigations')) {
                    $navigations = explode(',', $request->input('navigations'));
                    $pinyin = new Pinyin();
                    $navigationsArray = array_map(function ($v) use ($pinyin) {
                        $pinyiName = $pinyin->sentence($v);
                        $pinyiName = str_replace(' ', '', $pinyiName);
                        return ['name' => $v, 'pinyin' => $pinyiName];
                    }, $navigations);
                    $data->navigations()->createMany($navigationsArray);
                }
            }
        });
        return $data->load('template', 'navigations');
    }

    /**
     *  修改时检查logo、ico是否有改动
     * @param $request
     * @param $data
     * @return mixed
     *
     */
    protected function checkFile($request)
    {
        // logo 修改了
        if ($request->hasFile('logo')) {
            $logoFile = $this->uploadSiteLogo();
            $request->offsetSet('logo', $logoFile);
        } else {
            $request->offsetSet('logo', $request->input('logo1'));
        }
        // logo 修改了
        if ($request->hasFile('ico')) {
            $logoFile = $this->uploadSiteIco();
            $request->offsetSet('ico', $logoFile);
        } else {
            $request->offsetSet('ico', $request->input('ico1'));
        }
        return $request;
    }

    /**
     * 生成logo、ico文件名
     * @return string
     */
    protected function getFileName()
    {
        $str = date('YmdHis') . str_random(8);
        return $str;
    }
}