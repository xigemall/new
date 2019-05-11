<?php


namespace App\Services\Web;


use App\Models\Site;
use App\Services\Admin\TemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteService
{
    protected $template;

    public function __construct(TemplateService $templateService)
    {
        $this->template = $templateService;
    }

    public function index()
    {
        //当前域名
        $domain = request()->url();
        $site = Site::where('domain', $domain)->first();
        return $this->moveHtml($site);
    }

    protected function moveHtml($site)
    {
        //文件地址
        $path = $this->makeBladeFolder($site->id);
        $templatePath = $site->template->file;
        $files = $this->template->getAllTemplateFile($templatePath);
        foreach ($files as $k => $v) {
            $file = str_replace('uploads/', '', $templatePath);
            $oldName = str_replace($file . '/', '', $v);
            $name = str_replace('.html', '', $oldName);
            $newName = $name . '.blade.php';
            copy(public_path('uploads/' . $v), $path . '/' . $newName);
            //解析文件
            $view = view($site->id . '.' . $name)->with(['site' => $site]);
            $html = response($view)->getContent();
            $newPath = public_path('static/' . $site->id);
            if (!is_dir($newPath)) {
                mkdir($newPath, 0777);
            }
            file_put_contents($newPath . '/' . $oldName, $html);
        }
//        return view($site->id.'.'.$name)->with(['site'=>$site]);

    }

    /**
     * 创建文件夹
     * @param $name
     * @return string
     */
    protected function makeBladeFolder($name)
    {
        $folder = resource_path('views/' . $name);
        if (!is_dir($folder)) {
            mkdir($folder, 0777);
        }
        return $folder;
    }


}