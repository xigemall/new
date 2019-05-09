<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateDetail;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateDetailController extends Controller
{
    public function index(Content $content,$id)
    {
        $content->header('模板详情列表');
        $content->body($this->showGrid($id));
        return $content;
    }

    protected function showGrid(int $id)
    {
        dd($id);
        $data = Template::findOrFail($id);
//        $files = $this->getAllTemplateFile($data->file);
        $grid = new Grid(new TemplateDetail);
        $grid->column('name','234');
//        return Admin::grid($data, function (Grid $grid) {
//            $grid->column('name', '模板文件');
//        });
//        if($files){
//
//        }else{
//            $grid->column('','模板文件');
//        }

        return $grid;
    }
}
