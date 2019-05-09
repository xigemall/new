<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteRequest;
use App\Models\Navigation;
use App\Models\Site;
use App\Models\Template;
use App\Services\Admin\SiteService;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    protected $site;

    public function __construct(SiteService $site)
    {
        $this->site = $site;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Content $content)
    {
        $content->header('网站');
        $content->body($this->site->grid());
        return $content;
//        $data = Site::with(['navigations'])->get();
//        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = new Form(new Site());
        $form->text('title', '网站标题')->default('');
        $form->text('description', '网站描述')->default('');
        $form->text('keyword', '网站关键字')->default('');
        $form->text('domain', '网站域名')->default('');
        $form->image('logo', '网站LOGO图片')->default('');
        $form->image('ico', '网站ICO')->default('');
        $form->select('template_id', '模板选择')
            ->options(function () {
                $data = Template::select('id', 'name')->get();
                $newData = [];
                $data->map(function ($value, $key) use (&$newData) {
                    $newData[$value->id] = $value->name;
                })->all();
                return $newData;
            })
            ->ajax('/admin/template');
        // 关联栏目
        $form->text('navigations', '网站栏目')->required()->default('');
        return $form;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SiteRequest $request)
    {
        $data = $this->site->store($request);
//        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        $data = Site::with(['navigations'])->findOrFail($id);
//        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $form = new Form(Site::findOrFail($id));
        $form->text('title', '网站标题')->default($form->model()->title);
        $form->text('description', '网站描述')->default($form->model()->description);
        $form->text('keyword', '网站关键字')->default($form->model()->keyword);
        $form->text('domain', '网站域名')->default($form->model()->domain);
        $form->image('logo', '网站LOGO图片')->default($form->model()->logo);
        $form->image('ico', '网站ICO')->default($form->model()->ico);
        $form->select('template_id', '模板选择')
            ->options(function () {
                $data = Template::select('id', 'name')->get();
                $newData = [];
                $data->map(function ($value, $key) use (&$newData) {
                    $newData[$value->id] = $value->name;
                })->all();
                return $newData;
            })
            ->ajax('/admin/template')
        ->default($form->model()->template_id);
        // 关联栏目
        $navigations = implode(',',$form->model()->navigations()->pluck('name')->all());
        $form->text('navigations', '网站栏目')->required()->default($navigations);

        return $form;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SiteRequest $request, $id)
    {
        $this->site->update($request, $id);
        return redirect('/admin/site');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Site::findOrFail($id);
        DB::transaction(function () use ($data) {
            $data->navigations()->delete();
            $data->delete();
        });

        return response()->json('', 204);
    }

    /**
     * 获取网站栏目
     * @param $siteId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSiteNavigation($siteId)
    {
        $data = Navigation::where('site_id',$siteId)->get();
        return response()->json($data,200);
    }
}
