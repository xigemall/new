<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WechatRequest;
use App\Models\Advertising;
use App\Models\Navigation;
use App\Models\Site;
use App\Models\Wechat;
use App\Models\WechatCollectSiteNavigation;
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
        $content->header('微信管理');
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
        $data = Site::select('id', 'title')->get();
        $form->html(view('admin.wechat.add', ['data' => $data]));
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
            $data->wechatCollectSiteNavigations()->create($request->input());
        });
//        $newData = $data->load('wechatCollectSiteNavigations');
//        return response()->json($newData,201);
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
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $form = new Form(Wechat::findOrFail($id));
        $form->text('name', '名称')->default($form->model()->name)->required();
        $form->text('wechat_num', '公众号')->default($form->model()->wechat_num)->required();
        $data = Site::select('id', 'title')->get();
        $form->html(view('admin.wechat.edit', ['data' => $form->model(), 'site' => $data]));
        return $form;
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
            $data->wechatCollectSiteNavigations->site_id = $request->input('site_id');
            $data->wechatCollectSiteNavigations->navigation_id = $request->input('navigation_id');
            $data->wechatCollectSiteNavigations->save();
        });
//        $newData = $data->load('wechatCollectSiteNavigations');
//        return response()->json($newData, 201);
        redirect('/admin/wechat');
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
        $data->wechatCollectSiteNavigations()->delete();
        $data->delete();
        return response()->json('', 204);
    }
}
