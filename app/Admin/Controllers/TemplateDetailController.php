<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateDetail;
use App\Services\Admin\TemplateService;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
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
        $content->header('模板添加');
        $content->body($this->showCreate());
        return $content;
    }

    protected function showCreate()
    {
        $form = new Form(new Template);
        $form->text('name', '文件名')->required()->placeholder('index.html');
        $form->ckeditor('html', '模板');
        return $form;
    }

    public function store(Request $request, $id)
    {
        $data = Template::findOrFail($id);
        $files = $this->template->getAllTemplateFile($data->file);
        if ($files) {
            $files = $this->getAllFileName($files, $data);
        }
        if($request->has('edit')){
            //编辑
            $files = array_filter($files,function($v)use($request){
               return  $v != $request->input('name');
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
        $content->body($this->showEdit($id, $file));
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
        $form->ckeditor('html', '模板')->default($html);
        return $form;
    }

    public function delete(Request $request)
    {
        $path = $request->input('path');
        Storage::disk('admin')->delete($path);
        return 1;
    }
}
