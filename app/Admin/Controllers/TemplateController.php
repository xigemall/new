<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TemplateRequest;
use App\Models\Template;
use App\Services\Admin\TemplateService;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    protected $template;

    public function __construct(TemplateService $templateService)
    {
        $this->template = $templateService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Content $content)
    {
        $content->header('模板');
        $content->body($this->template->grid());
        return $content;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Content $content)
    {
        $form = new Form(new Template());
        $form->text('name', '模板名称')->default('')->required();
        $form->text('description', '模板描述')->default('');
        $form->file('file', '模板文件')->default('')->rules('mimes:zip')->required();
        return $form;
//        $content->header('模板');
//        $content->body(view('admin.template.add'));
//        return $content;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TemplateRequest $request)
    {
        $this->template->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Content $content)
    {
        $content->header('模板编辑');
        $content->body($this->template->showGrid($id));
        return $content;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $form = new Form(Template::findOrFail($id));
//        $form->text('name', '模板名称')->default($form->model()->name)->required();
//        $form->text('description', '模板描述')->default($form->model()->description);
//        $form->file('file', '模板文件')->default($form->model()->file)->rules('mimes:zip')->required();
//        return $form;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TemplateRequest $request, $id)
    {
//        $this->template->update($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Template::findOrFail($id);
        $data->delete();
        $this->template->deleteFile($data->file);
        return 1;
    }
}
