<?php


namespace App\Services\Admin;


use App\Models\Navigation;
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
                $navigationsArray = [];
                foreach ($navigations as $k => $v) {
                    $pinyiName = $pinyin->sentence($v);
                    $pinyiName = str_replace(' ', '', $pinyiName);
                    $navigationsArray[] = ['name' => $v, 'pinyin' => $pinyiName, 'sort' => $k];
                }
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
            $this->makeNavigations($request, $data);
        });
        return $data->load('template', 'navigations');
    }

    /**
     * 编辑修改栏目
     * @param $request
     * @param $site
     */
    protected function makeNavigations($request, $site)
    {
        $requestNavigationArray = explode(',', $request->input('navigations'));
        $dbNavigationArray = $site->navigations()->pluck('name')->all();

        //删除的
        $delNavigations = [];
        foreach ($dbNavigationArray as $v) {
            if (!in_array($v, $requestNavigationArray)) {
                $delNavigations[] = $v;
            }
        }

        if ($delNavigations) {
            $site->navigations()->whereIn('name', $delNavigations)->delete();
        }

        // 添加与编辑
        $pinyin = new Pinyin();
        foreach ($requestNavigationArray as $k => $v) {
            if (!in_array($v, $dbNavigationArray)) {
                $pinyiName = $pinyin->sentence($v);
                $pinyiName = str_replace(' ', '', $pinyiName);
                $data = ['name' => $v, 'pinyin' => $pinyiName, 'sort' => $k, 'site_id' => $site->id];
                Navigation::create($data);
            } else {
                Navigation::where(['site_id' => $site->id, 'name' => $v])->update(['sort' => $k]);
            }
        }
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