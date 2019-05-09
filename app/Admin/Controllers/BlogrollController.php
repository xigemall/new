<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogrollRequest;
use App\Models\Blogroll;
use App\Models\Site;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogrollController extends Controller
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
//        $data = Blogroll::with('blogrollSites')->get();
//        return response()->json($data, 200);
    }

    protected function getGridList()
    {
        $grid = new Grid(new Blogroll());
        $grid->title('标题');
        $grid->link('链接地址');
        $grid->place('位置')->display(function ($place) {
            return $place ? '其它网站' : '全局';
        });
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
        $form = new Form(new Blogroll);
        $form->text('title', '标题')->default('')->required();
        $form->url('link', '链接地址')->default('');

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
    public function store(BlogrollRequest $request)
    {
        $request->offsetSet('site', array_filter($request->input('site')));
        DB::transaction(function () use ($request, &$data) {
            $data = Blogroll::create($request->input());
            if ($request->input('place') && $request->input('site')) {
                $site = array_map(function ($v) {
                    return ['site_id' => $v];
                }, $request->input('site'));
                $data->blogrollSites()->createMany($site);
            }
        });
//        return response()->json($data->load('blogrollSites'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Blogroll::with('blogrollSites')->findOrFail($id);
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
        $form = new Form(Blogroll::findOrFail($id));
        $form->text('title', '标题')->default($form->model()->title)->required();
        $form->url('link', '链接地址')->default($form->model()->link);

        $form->select('place', '位置')
            ->options([0 => '全局', 1 => '其它网站'])
            ->default($form->model()->place);

        $data = Site::select('id', 'title')->get();
        $options = [];
        $data->map(function ($value, $key) use (&$options) {
            $options[$value->id] = $value->title;
        })->all();

        $form->checkbox('site', '网站')
            ->options($options)
            ->default($form->model()->blogrollSites()->pluck('site_id')->all())
            ->stacked();

        return $form;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogrollRequest $request, $id)
    {
        $data = Blogroll::findOrFail($id);
        $request->offsetSet('site', array_filter($request->input('site')));
        DB::transaction(function () use ($request, &$data) {
            $data->update($request->input());
            $data->blogrollSites()->delete();
            if ($request->input('place') && $request->input('site')) {
                $site = array_map(function ($v) {
                    return ['site_id' => $v];
                }, $request->input('site'));
                $data->blogrollSites()->createMany($site);
            }
        });
        return redirect('/admin/blogroll');
//        return response()->json($data->load('blogrollSites'), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Blogroll::findOrFail($id);
        $data->blogrollSites()->delete();
        $data->delete();
        return response()->json('', 204);
    }
}
