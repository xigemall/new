<?php


namespace App\Services\Admin;


use App\Models\Template;
use Chumper\Zipper\Facades\Zipper;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class TemplateService
{
    /**
     * 模板列表
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('模板');
            $content->body($this->grid());
        });
    }

    /**
     * 列表布局
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Template::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->name('模板名称');
            $grid->description('模板描述');
            $grid->file('模板文件地址');
        });
    }

    /**
     * 新增界面
     * @return Form
     */
    public function create()
    {
        return Admin::form(Template::class, function (Form $form) {
            $form->text('name', '模板名称')->default('')->required();
            $form->text('description', '模板描述')->default('');
            $form->file('file', '模板文件')->required();
        });
    }

    /**
     * 新增保存
     * @param $request
     */
    public function store($request)
    {
        $this->saveTemplateFile($request);
    }

    /**
     * 保存模板文件
     */
    protected function saveTemplateFile()
    {
        // 保存上传的压缩文件
        $path = $this->makeCompressionFile();

        // 解压文件地址
        $compressionFilePath = storage_path($path);
        //解压文件
        $this->compressionFile($compressionFilePath);

    }

    /**
     * 保存上传的压缩文件
     * @return mixed
     */
    protected function makeCompressionFile()
    {
        $file = request()->file('file');
        // 文件后缀
        $originalExtension = $file->getClientOriginalExtension();
        //文件名
        $name = request('name') . '.' . $originalExtension;

        $path = request('file')->storeAs('files/template/' . request('name'), $name, 'admin');
        return $path;
    }

    protected function compressionFile(string $path)
    {

//        $filesPath = glob(public_path('uploads/files/template/test/'));
//        Zipper::make($path)->add($filesPath)->close();
    }
}