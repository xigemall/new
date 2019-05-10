<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Navigation;
use App\Models\Site;
use App\Services\Admin\ArticleService;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    protected $article;

    public function __construct(ArticleService $articleService)
    {
        $this->article = $articleService;
    }

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
    }

    protected function getGridList()
    {
        $grid = new Grid(new Article());
        $grid->title('标题');
        $grid->siteNavigationArticle()->site_id('所属网站')->display(function ($siteId) {
            return Site::findOrFail($siteId)->value('title');
        });
        $grid->siteNavigationArticle()->navigation_id('所属栏目')->display(function ($nav) {
            return Navigation::findOrFail($nav)->value('name');
        });
        $grid->view_count('阅读数');

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $form = new Form(Article::findOrFail($id));
//        $form->text('name', '名称')->default($form->model()->name)->required();
//        $form->text('wechat_num', '公众号')->default($form->model()->wechat_num)->required();
//        $data = Site::select('id', 'title')->get();
//        $form->html(view('admin.wechat.edit', ['data' => $form->model(), 'site' => $data]));
        return $form;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Article::findOrFail($id);
        $data->siteNavigationArticle()->delete();
        $data->delete();
        return response()->json($data, 204);
    }
}
