<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Navigation;
use App\Models\Site;
use App\Services\Web\SiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{

    protected $site;

    public function __construct(SiteService $siteService)
    {
        $this->site = $siteService;
    }

    public function index(Request $request)
    {
        //当前域名
        $domain = $request->server('HTTP_HOST');
        $domain = 'http://' . $domain;
        // 网站
        $site = Site::where('domain', $domain)->first();
        $site->increment('visit');

//        \View::addExtension('html', 'php');
//        return view()->file(public_path('/static/' . $site->id . '/index.html'));

        $htmlFile = public_path($site->template->file . '/index.html');
        $bladeFile = resource_path('views/' . $site->template->id . '/index.blade.php');
        $path = resource_path('views/' . $site->template->id);
        if (!is_dir($path)) {
            Storage::disk('resource')->makeDirectory($site->template->id);
        }
        copy($htmlFile, $bladeFile);

        //获取广告
        $advertisings = $this->site->getAdvertising($site->id);
        // 获取友情链接
        $blogrolls = $this->site->getBlogroll($site->id);
        // 获取文章
        $articles = $this->site->getNavigationArticles($site->navigations[0]->id, $site->id);
        // 推荐文章
        $recommendArticles = $this->site->getRecommendArticles($site->id, $site->navigations[0]->id);
        //热门文章
        $hots = $this->site->getHotArticles($site->id, $site->navigations[0]->id);

        $data = [
            'static' => asset($site->template->file),
            'site' => $site,
            'navigations' => $site->navigations,
            //广告
            'advertisings' => $advertisings,
            'blogrolls' => $blogrolls,
            'articles' => $articles,
            'recommends' => $recommendArticles,
            'hots' => $hots,
        ];
        return view($site->template->id . '.index')->with($data);
    }

    /**
     * 列表
     * @param Request $request
     * @param $pinyin
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getList(Request $request, $pinyin)
    {
        //当前域名
        $domain = $request->server('HTTP_HOST');
        $domain = 'http://' . $domain;

        // 网站
        $site = Site::where('domain', $domain)->first();

        $navigation = Navigation::where(['site_id' => $site->id, 'pinyin' => $pinyin])->first();

        $htmlFile = public_path($site->template->file . '/list.html');
        $bladeFile = resource_path('views/' . $site->template->id . '/list.blade.php');
        $path = resource_path('views/' . $site->template->id);
        if (!is_dir($path)) {
            Storage::disk('resource')->makeDirectory($site->template->id);
        }
        copy($htmlFile, $bladeFile);

        //获取广告
        $advertisings = $this->site->getAdvertising($site->id);
        // 获取友情链接
        $blogrolls = $this->site->getBlogroll($site->id);
        // 获取文章
        $articles = $this->site->getNavigationArticles($navigation->id, $site->id);

        // 推荐文章
        $recommendArticles = $this->site->getRecommendArticles($site->id, $navigation->id);
        //热门文章
        $hots = $this->site->getHotArticles($site->id, $navigation->id);
        $data = [
            'static' => asset($site->template->file),
            'site' => $site,
            'navigations' => $site->navigations,
            'navigation' => $navigation,
            //广告
            'advertisings' => $advertisings,
            'blogrolls' => $blogrolls,
            'articles' => $articles,
            'recommends' => $recommendArticles,
            'hots' => $hots,
        ];
        return view($site->template->id . '.list')->with($data);
    }

    /**
     * 详情
     * @param Request $request
     * @param $pinyin
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDetail(Request $request, $pinyin, $id)
    {
        //当前域名
        $domain = $request->server('HTTP_HOST');
        $domain = 'http://' . $domain;
        // 网站
        $site = Site::where('domain', $domain)->first();
        $navigation = Navigation::where(['site_id' => $site->id, 'pinyin' => $pinyin])->first();

        $htmlFile = public_path($site->template->file . '/detail.html');
        $bladeFile = resource_path('views/' . $site->template->id . '/detail.blade.php');
        $path = resource_path('views/' . $site->template->id);
        if (!is_dir($path)) {
            Storage::disk('resource')->makeDirectory($site->template->id);
        }
        copy($htmlFile, $bladeFile);

        //获取广告
        $advertisings = $this->site->getAdvertising($site->id);
        // 获取友情链接
        $blogrolls = $this->site->getBlogroll($site->id);
        // 获取文章
        $article = Article::findOrFail($id);
        // 推荐文章
        $recommendArticles = $this->site->getRecommendArticles($site->id, $navigation->id);
        //热门文章
        $hots = $this->site->getHotArticles($site->id, $navigation->id);
        // 上一篇
        $prevArticle = Article::where(['site_id' => $site->id, 'navigation_id' => $navigation->id])->where('id','<',$id)->orderBy('id','desc')->limit(1)->first();
        // 下一篇
        $nextArticle = Article::where(['site_id' => $site->id, 'navigation_id' => $navigation->id])->where('id','>',$id)->orderBy('id','asc')->limit(1)->first();
        $data = [
            'static' => asset($site->template->file),
            'site' => $site,
            'navigations' => $site->navigations,
            'navigation' => $navigation,
            //广告
            'advertisings' => $advertisings,
            'blogrolls' => $blogrolls,
            'article' => $article,
            'recommends' => $recommendArticles,
            'hots' => $hots,
            'prev'=>$prevArticle,
            'next'=>$nextArticle,
        ];
        return view($site->template->id . '.detail')->with($data);

    }

}
