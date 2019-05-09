<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvertisingRequest;
use App\Models\Advertising;
use App\Models\Site;
use App\Models\Template;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdvertisingController extends Controller
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
//        $data = Advertising::with('AdvertisingSites.site')->get();
//        return response()->json($data, 200);
    }

    protected function getGridList()
    {
        $grid = new Grid(new Advertising());
        $grid->title('广告标题');
        $grid->place('广告位置')->display(function ($place) {
            return $place ? '其它网站' : '全局';
        });
        $grid->link('广告URL');
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
        $form = new Form(new Advertising());
        $form->text('title', '广告标题')->default('')->required();
        $form->image('img', '广告图片')->default('');
        $form->url('link', '链接')->default('')->required();

        $form->select('place', '位置')->options([0 => '全局', 1 => '其它网站']);

        $data = Site::select('id', 'title')->get();
        $options = [];
        $data->map(function ($value, $key) use (&$options) {
            $options[$value->id] = $value->title;
        })->all();

        $form->checkbox('site', '网站')->options($options)->stacked();

        return $form;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdvertisingRequest $request)
    {
        DB::transaction(function () use ($request, &$data) {
            // 保存广告图片
            if ($request->has('img')) {
                $imgFile = $this->uploadAdvertisingImg($request);
                $request->offsetSet('img', $imgFile);
            }

            $request->offsetSet('site', array_filter($request->input('site')));

            $data = Advertising::create($request->input());

            if ($request->input('place') && $request->input('site')) {
                $site = array_map(function ($v) {
                    return ['site_id' => $v];
                }, $request->input('site'));

                $data->AdvertisingSites()->createMany($site);
            }
        });
//        return redirect('/admin/advertising');
//        return response()->json($data->load('AdvertisingSites'), 201);

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Advertising::with('AdvertisingSites.site')->findOrFail($id);
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
        $form = new Form(Advertising::findOrFail($id));
        $form->text('title', '广告标题')->default($form->model()->title)->required();
        $form->hidden('img1')->default($form->model()->img);
        $form->display('img', '广告图片')->with(function ($value) use ($form) {
            $img = $form->model()->img;
            return "<img src=" . $img . " />";
        });
        $form->image('img', '广告图片')->default($form->model()->img);
        $form->url('link', '链接')->default($form->model()->link)->required();

        $form->select('place', '位置')->options([0 => '全局', 1 => '其它网站'])->default($form->model()->place);
//
        $data = Site::select('id', 'title')->get();
        $options = [];
        $data->map(function ($value, $key) use (&$options) {
            $options[$value->id] = $value->title;
        })->all();

        $form->checkbox('site', '网站')->options($options)->default($form->model()->AdvertisingSites()->pluck('site_id')->all())->stacked();

        return $form;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdvertisingRequest $request, $id)
    {
        $data = Advertising::findOrFail($id);
        $request->offsetSet('site', array_filter($request->input('site')));
        $request = $this->checkImg($request);
        DB::transaction(function () use ($request, &$data) {
            $data->update($request->input());

            $data->AdvertisingSites()->delete();
            if ($request->input('place') && $request->input('site')) {
                $site = array_map(function ($v) {
                    return ['site_id' => $v];
                }, $request->input('site'));

                $data->AdvertisingSites()->createMany($site);
            }
        });
        return redirect('/admin/advertising');
//        return response()->json($data->load('AdvertisingSites'), 201);
    }

    protected function checkImg($request)
    {
        if ($request->hasFile('img')) {
            $newFile = $this->uploadAdvertisingImg($request);
            $request->offsetSet('img', $newFile);
        } else {
            $request->offsetSet('img', $request->input('img1'));
        }
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Advertising::findOrFail($id);
        $data->AdvertisingSites()->delete();
        $data->delete();
        return response()->json('', 204);
    }

    /**
     * 上传广告图片
     * @param Request $request
     * @return string
     */
    protected function uploadAdvertisingImg(Request $request)
    {
        $path = 'images/advertising';
        $extension = $request->file('img')->getClientOriginalExtension();
        $fileName = $this->getFileName();
        $name = $fileName . '.' . $extension;
        $file = $request->file('img')->storeAs($path, $name, 'admin');
        return '/uploads/' . $file;
    }

    protected function getFileName()
    {
        return date('YmdHis') . str_random(6);
    }
}
