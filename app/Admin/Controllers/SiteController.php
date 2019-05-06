<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteRequest;
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
//        $content->header('网站');
//        $content->body($this->site->grid());
//        return $content;
        $data = Site::with(['navigations'])->get();
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = new Form(new Site());
        $form->text('title', '网站标题');
        $form->text('description', '网站描述');
        $form->text('keyword', '网站关键字');
        $form->text('domain', '网站域名');
        $form->image('logo', '网站LOGO图片');
        $form->image('ico', '网站ICO');
        $form->select('template_id', '模板选择')
            ->options(function () {
                $data = Template::select('id', 'name')->get();
                $newData = $data->map(function ($value, $key) {
                    return [$value['id'] => $value['name']];
                })->all();
                array_unshift($newData, [0 => '随机']);
                $newData = array_collapse($newData);
                return $newData;
            })
            ->ajax('/admin/template');
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
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Site::with(['navigations'])->findOrFail($id);
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
        //
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
        $data = $this->site->update($request, $id);
        return response()->json($data, 201);
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
     * 上传网站LOGO
     * @param Request $request
     * @return string
     */
    public function uploadSiteLogo(Request $request)
    {
        $message = [
            'logo' => '网站LOGO图片',
        ];
        $this->validate($request,
            [
                'logo' => [
                    'required',
                    'file',
                    'image'
                ]
            ], [], $message);
        $fileName = $this->site->uploadSiteLogo();
        return $fileName;
    }

    /**
     * 上传网站ICO
     * @param Request $request
     * @return string
     */
    public function uploadSiteIco(Request $request)
    {
        $message = [
            'ico' => '网站ICO',
        ];
        $this->validate($request,
            [
                'ico' => [
                    'required',
                    'file',
                    'image'
                ]
            ], [], $message);
        $fileName = $this->site->uploadSiteIco();
        return $fileName;
    }
}
