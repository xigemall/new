<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Services\Admin\TemplateService;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TemplateDetailController extends Controller
{
    protected $template;

    public function __construct(TemplateService $templateService)
    {
        $this->template = $templateService;
    }

    public function index(Content $content, $id)
    {
        $content->header('模板详情列表');
        $content->body($this->showGrid($id));
        return $content;
    }

    protected function showGrid(int $id)
    {
        $data = Template::findOrFail($id);
        $files = $this->template->getAllTemplateFile($data->file);
        return view('admin.template_detail.index')->with(['data' => $data, 'files' => $files]);
    }

    public function create(Content $content, $id)
    {
//        $content->header('模板添加');
//        $content->body($this->showCreate());
//        return $content;

        $content->header('模板添加');
        $content->row(function (Row $row) {
            $row->column(3, function (Column $column) {
                $this->showText($column);
            });
            $row->column(9, $this->showCreate());
        });
        return $content;
    }

    protected function showText($column)
    {
        $column->row('静态文件路径：  {{$static}}');
        $column->row('静态文件路径demo：  {{$static}}/style/font/reset.css');
        $column->row('<br/>');

        $column->row('网站');
        $column->row("网站首页：  {{url('/')}}");
        $column->row('网站标题：  {{$site->title}}');
        $column->row('网站描述：  {{$site->description}}');
        $column->row('网站关键字：  {{$site->keyword}}');
        $column->row('网站域名：  {{$site->domain}}');
        $column->row('网站LOGO图片：  {{$site->logo}}');
        $column->row('网站ICO：  {{$site->ico}}');
        $column->row('<br/>');

        //循环
        $column->row('循环');
        $column->row('@foreach($data as $k=>$v){}@endforeach');
        $column->row('<br/>');

        // 判断
        $column->row('判断');
        $column->row('@if $data @endif');
        $column->row('<br/>');

        //栏目列表
        $column->row('栏目');
        $column->row('栏目[]：  {{$navigations}}');
        $column->row('栏目url：  {{asset($v->pinyin)}} 或者 {{url($v->pinyin)}}');
        $column->row('栏目名称：  {{$v->name}');
        $column->row('<br/>');


        // 单个栏目
        $column->row('列表、详情栏目');
        $column->row('列表、详情栏目{}：  {{$navigation}}');
        $column->row('栏目拼音：  {{$navigation->pinyin}}');
        $column->row('栏目ID：  {{$navigation->id}}');
        $column->row('栏目名称：  {{$navigation->name}}');
        $column->row('<br/>');

        // 文章列表
        $column->row('文章列表：  {{$articles}}');
        $column->row('文章列表循环start：  @foreach($articles as $v)');

        $column->row('详情地址：  {{asset($navigation->pinyin."/".$v->id)}}');
        $column->row('标题：  {{$v->title)}}');
        $column->row('微信名称：  {{$v->name)}}');
        $column->row('观看数：  {{$v->view_count)}}');
        $column->row('正文：  {{$v->content)}}');
        $column->row('正文html：  {{$v->html)}}');
        $column->row('内容图片链接列表：  {{$v->image_urls)}}');
        $column->row('内容图片链接列表demo： [image1,image2]');
        $column->row('音频链接列表：  {{$v->audio_urls)}}');
        $column->row('音频链接列表demo： [audio1,audio1]');
        $column->row('视频链接列表：  {{$v->video_urls)}}');
        $column->row('视频链接列表demo： [video,video]');
        $column->row('创建时间： {{$v->created_at');

        $column->row('<br/>');
        $column->row('评论列表：  {{$v->comments)}}');
        $column->row('评论列表demo[]： @foreach');
        $column->row('文章id：  {{$v->id}}');
        $column->row('点赞数：  {{$v->likeCount}}');
        $column->row('评论的回复列表：  {{$v->replies}}');
        $column->row('公示日期：  {{$v->publishDateStr}}');
        $column->row('评价文本内容：  {{$v->content}}');
        $column->row('发布时间：UTC时间戳格式：  {{$v->publishDate}}');
        $column->row('评论者名称：  {{$v->commenterScreenName}}');
        $column->row('评论数：  {{$v->commentCount}}');
        $column->row('相关物图片url：  {{$v->avatarUrl}}');
        $column->row('评论列表demo[]： @endforeach');
        $column->row('<br/>');

        $column->row('文章列表循环end：  @endforeach');
        $column->row('<br/>');

        // 文章分页
        $column->row('文章分页');
        $column->row('文章分页：  {{ $articles->links() }}');
        $column->row('<br/>');

        // 文章详情
        $column->row('文章详情');
        $column->row('文章详情：  {{$article}}');
        $column->row('上一篇详情地址：  {{asset($navigation->pinyin."/".$prev->id)}}');
        $column->row('下一篇详情地址：  {{asset($navigation->pinyin."/".$next->id)}}');
        $column->row('<br/>');

        //广告
        $column->row('广告');
        $column->row('广告列表[]：  {{$advertisings}}');
        $column->row('广告标题：  {{$v->title}}');
        $column->row('广告图片：  {{asset($v->img)}}');
        $column->row('广告链接：  {{$v->link}}');
        $column->row('<br/>');

        //友情链接
        $column->row('友情链接');
        $column->row('友情链接列表[]：  {{$blogrolls}}');
        $column->row('标题：  {{$v->title}}');
        $column->row('链接地址：  {{$v->link}}');
        $column->row('<br/>');

        //推荐文章
        $column->row('推荐文章');
        $column->row('推荐文章列表[]：  {{$recommends}}');
        $column->row('<br/>');

        //热门文章
        $column->row('热门文章');
        $column->row('热门文章列表[]：  {{$hots}}');
        $column->row('<br/>');

    }

    protected function showCreate()
    {
        $form = new Form(new Template);
        $form->text('name', '文件名')->required()->placeholder('index.html');
        $form->textarea('html', '模板')->rows(30);
//        $form->ckeditor('html', '模板');
        return $form;

    }

    public function store(Request $request, $id)
    {
        $data = Template::findOrFail($id);
        $files = $this->template->getAllTemplateFile($data->file);
        if ($files) {
            $files = $this->getAllFileName($files, $data);
        }
        if ($request->has('edit')) {
            //编辑
            $files = array_filter($files, function ($v) use ($request) {
                return $v != $request->input('name');
            });
        }
        $message = [
            'name' => '文件名',
            'html' => '模板',
        ];
        $this->validate($request, [
            'name' => [
                'required',
                Rule::notIn($files),
            ],
            'html' => [
                'required',
                'string'
            ]
        ], [], $message);
        $path = str_replace('uploads/', '', $data->file);
        $name = $request->input('name');
        Storage::disk('admin')->put($path . '/' . $name, $request->input('html'));
    }

    /**
     * 获取全部文件名
     * @param array $files
     * @param $data
     * @return array
     */
    protected function getAllFileName(array $files, $data)
    {
        $path = $data->file . '/';
        $path = str_replace('uploads/', '', $path);
        $fileNames = array_map(function ($v) use ($path) {
            $fileName = str_replace($path, '', $v);
            return $fileName;
        }, $files);
        return $fileNames;
    }

    public function edit(Request $request, $id)
    {
        $file = $request->query('file');
        $content = new Content();
        $content->header('模板添加');
        $content->row(function (Row $row) use ($id, $file) {
            $row->column(3, function (Column $column) {
                $this->showText($column);
            });
            $row->column(9, $this->showEdit($id, $file));
        });
//        $content->body($this->showEdit($id, $file));
        return $content;

    }

    protected function showEdit($id, $file)
    {
        $data = Template::findOrFail($id);
        $path = str_replace('uploads/', '', $data->file);
        $name = str_replace($path . '/', '', $file);

        $form = new Form(new Template);
        $form->hidden('edit')->default($id);
        $form->text('name', '文件名')->required()->placeholder('index.html')->default($name);
        $html = file_get_contents(public_path('uploads/' . $file));
        $form->textarea('html', '模板')->rows(30)->default($html);
//        $form->ckeditor('html', '模板')->default($html);
        return $form;
    }

    public function delete(Request $request)
    {
        $path = $request->input('path');
        Storage::disk('admin')->delete($path);
        return 1;
    }
}
