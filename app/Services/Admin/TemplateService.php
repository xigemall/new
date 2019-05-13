<?php


namespace App\Services\Admin;


use App\Models\Template;
use Chumper\Zipper\Facades\Zipper;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateService
{
    /**
     * 列表布局
     * @return Grid
     */
    public function grid()
    {
        $grid = new Grid(new Template);
        $grid->id('ID')->sortable();
        $grid->name('模板名称');
        $grid->description('模板描述');
        $grid->file('模板文件地址');

        $grid->actions(function ($actions) {
            $actions->append("<a href=\"/admin/template-detail/".$actions->getKey()."\">详情</a>");
            $actions->disableView();
            $actions->disableEdit();

        });
        return $grid;
    }

    /**
     * 新增保存
     * @param $request
     */
    public function store($request)
    {
        $data = Template::create($request->input());
        $path = $this->saveTemplateFile($data->id);
        $data->file = $path;
        $data->save();
        return $data;
    }

    /**
     * 保存模板文件
     */
    protected function saveTemplateFile($id)
    {
        // 上传文件临时路径
        $temporaryPath = 'files/template_tmp/' . $id;

        //上传文件路径
        $path = 'files/template/' . $id;

        // 保存上传的压缩文件
        $file = $this->makeCompressionFile($temporaryPath, $id);
        //解压文件
        $this->compressionFile($file, $path);
        return 'uploads/' . $path;
    }

    /**
     * 保存上传的压缩文件
     * @return mixed
     */
    protected function makeCompressionFile(string $path, $id)
    {
        $file = request()->file('file');
        // 文件后缀
        $originalExtension = $file->getClientOriginalExtension();
        //文件名
        $name = $id . '.' . $originalExtension;
        $fileName = request('file')->storeAs($path, $name, 'admin');
        return $fileName;
    }

    /**
     * 解压文件
     * @param string $file
     * @param string $path
     */
    protected function compressionFile(string $file, string $path)
    {
        $file = public_path('uploads/' . $file);
        $path = public_path('uploads/' . $path);
        Zipper::make($file)->extractTo($path);
    }


    /**
     * 删除模板文件
     * @param string $path
     */
    public function deleteFile(string $path)
    {
        $path = str_replace('uploads/', '', $path);
        Storage::disk('admin')->deleteDirectory($path);
    }

    /**
     * 获取模板文件
     * @param string $path
     * @return mixed
     */
    public function getAllTemplateFile(string $path)
    {
        $path = str_replace('uploads/', '', $path);
        $files = Storage::disk('admin')->files($path);
        return $files;
    }
}