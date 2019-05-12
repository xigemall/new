<?php


namespace App\Services\Web;


use App\Models\Advertising;
use App\Models\AdvertisingSite;
use App\Models\Article;
use App\Models\Blogroll;
use App\Models\BlogrollSite;
use App\Models\Navigation;
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
        // 网站
        $site = Site::where('domain', $domain)->first();

        // 获取广告
        $advertising = $this->getAdvertising($site->id);
        $site->advertisings = $advertising;

        // 获取友情链接
        $blogroll = $this->getBlogroll($site->id);
        $site->blogrolls = $blogroll;

        $this->moveHtml($site);

        \View::addExtension('html', 'php');
        return view()->file(public_path('/static/' . $site->id . '/index.html'));
    }

    protected function moveHtml($site)
    {
        //创建blade文件地址
        $viewPath = $this->makeBladeFolder($site->id);
        $templatePath = $site->template->file;
        $files = $this->template->getAllTemplateFile($templatePath);
        foreach ($files as $k => $v) {
            $file = str_replace('uploads/', '', $templatePath);
            $oldName = str_replace($file . '/', '', $v);
            $name = str_replace('.html', '', $oldName);

            if (strstr($name, '-detail')) {
                // 详情
                $this->makeDetailArticle($site, $name, $viewPath, $v);
            } else {
                //列表
                $this->makeList($name, $v, $viewPath, $site);

            }
        }
    }

    /**
     * 列表文件处理
     * @param $name
     * @param $file
     * @param $viewPath
     * @param $site
     */
    public function makeList($name, $file, $viewPath, $site)
    {
        $newName = $name . '.blade.php';
        copy(public_path('uploads/' . $file), $viewPath . '/' . $newName);

        // 获取栏目文章
        $navigationArticles = $this->getNavigationArticles($name, $site->id);
        $site->navigationArticles = $navigationArticles;

        //解析文件
        $view = view($site->id . '.' . $name, ['site' => $site,]);
        $html = response($view)->getContent();

        $publicPath = public_path('static/' . $site->id);
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0777);
        }
        file_put_contents($publicPath . '/' . $name . '.html', $html);
    }

    /**
     * 详情文件处理
     * @param $site
     * @param $name
     * @param $viewPath
     * @param $file
     */
    public function makeDetailArticle($site, $name, $viewPath, $file)
    {
        $name = str_replace('-detail', '', $name);
        $viewPath = $this->makeBladeFolder($site->id . '/' . $name);

        // 获取栏目文章
        $navigationArticles = $this->getNavigationArticles($name, $site->id);
        if ($navigationArticles) {
            foreach ($navigationArticles as $v) {
                copy(public_path('uploads/' . $file), $viewPath . '/' . $v->id . '.blade.php');

                //解析文件
                $view = view($site->id . '.' . $name . '.' . $v->id, ['site' => $site, 'detail' => $v]);
                $html = response($view)->getContent();

                $publicPath = public_path('static/' . $site->id . '/' . $name);
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777);
                }
                file_put_contents($publicPath . '/' . $v->id . '.html', $html);
            }
        }
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

    /**
     * 获取广告
     * @param $siteId
     * @return mixed
     */
    protected function getAdvertising($siteId)
    {
        // 网站广告ID
        $advertisingId = AdvertisingSite::where('site_id', $siteId)->pluck('advertising_id')->all();
        // 全局与其它网站广告
        $advertising = Advertising::where('place', 0)->orWhereIn('id', $advertisingId)->get();
        return $advertising;
    }

    /**
     * 获取友情链接
     * @param $siteId
     * @return mixed
     */
    protected function getBlogroll($siteId)
    {
        $blogrollId = BlogrollSite::where('site_id', $siteId)->pluck('blogroll_id')->all();
        $blogroll = Blogroll::where('place', 0)->orWhereIn('id', $blogrollId)->get();
        return $blogroll;
    }

    /**
     * 获取栏目文章
     * @param string $navigationName
     * @param int $siteId
     * @return mixed
     */
    protected function getNavigationArticles(string $navigationName, int $siteId)
    {
        $navigationArticles = [];
        $navigationId = Navigation::where(['site_id' => $siteId, 'name' => $navigationName])->value('id');
        if ($navigationId) {
            $navigationArticles = Article::where(['site_id' => $siteId, 'navigation_id' => $navigationId])->get();
        }
        return $navigationArticles;
    }
}