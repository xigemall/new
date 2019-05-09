<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WechatRequest;
use App\Models\Advertising;
use App\Models\Navigation;
use App\Models\Site;
use App\Models\Wechat;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WechatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Content $content)
    {
        $content->header('网站');
        $content->body($this->getGridList());
        return $content;
//        $data = Wechat::get();
//        return response()->json($data, 200);
    }

    protected function getGridList()
    {
        $grid = new Grid(new Wechat());
        $grid->name('名称');
        $grid->wechat_num('公众号');
        $grid->collect_num('采集数量');
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        return $grid;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = new Form(new Wechat());
        $form->text('name', '名称')->default('')->required();
        $form->text('wechat_num', '公众号')->default('')->required();
        $form->select('site', '网站')
            ->options(function () {
                $data = Site::select('id', 'title')->get();
                $newData = [];
                $data->map(function ($value, $key) use (&$newData) {
                    $newData[$value->id] = $value->title;
                })->all();
                return $newData;
            })->load('navigation','/admin/navigation');
        $form->select('navigation');
        $data = Site::select('id', 'title')->get();
        $form->html(view('add',['data'=>$data]));

        return $form;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(WechatRequest $request)
    {
        DB::transaction(function () use ($request, &$data) {
            $data = Wechat::create($request->input());
            $site = $this->getRequestSite($request->input('site'));
            $data->wechatCollectSiteNavigations()->createMany($site);
        });
        $newData = $data->load('wechatCollectSiteNavigations');
        return response()->json($newData,201);
    }

    protected function getRequestSite(array $site)
    {
        $data = [];
        foreach($site as $k=>$v){
            foreach ($v['navigation_id'] as $key=>$value){
                $item = ['site_id'=>$v['site_id'],'navigation_id'=>$value];
                array_push($data,$item);
            }
        }
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Wechat::with('wechatCollectSiteNavigations')->findOrFail($id);
        return response()->json($data,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(WechatRequest $request, $id)
    {
        $data = Wechat::findOrFail($id);
        DB::transaction(function () use ($request, &$data) {
            $data->update($request->input());
            $site = $this->getRequestSite($request->input('site'));
            $data->wechatCollectSiteNavigations()->delete();
            $data->wechatCollectSiteNavigations()->createMany($site);
        });
        $newData = $data->load('wechatCollectSiteNavigations');
        return response()->json($newData,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Wechat::findOrFail($id);
        $data->delete();
        return response()->json('',204);
    }
}
