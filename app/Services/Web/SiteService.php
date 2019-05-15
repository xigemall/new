<?php


namespace App\Services\Web;


use App\Models\Advertising;
use App\Models\AdvertisingSite;
use App\Models\Article;
use App\Models\Blogroll;
use App\Models\BlogrollSite;
use App\Models\Tag;

class SiteService
{

    /**
     * 获取广告
     * @param $siteId
     * @return mixed
     */
    public function getAdvertising($siteId)
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
    public function getBlogroll($siteId)
    {
        $blogrollId = BlogrollSite::where('site_id', $siteId)->pluck('blogroll_id')->all();
        $blogroll = Blogroll::where('place', 0)->orWhereIn('id', $blogrollId)->get();
        return $blogroll;
    }

    /**
     * 获取栏目文章
     * @param int $navigationId
     * @param int $siteId
     * @return mixed
     */
    public function getNavigationArticles(int $navigationId, int $siteId)
    {
        $navigationArticles = Article::where(['site_id' => $siteId, 'navigation_id' => $navigationId])->orderBy('id', 'desc')->paginate(10);
        return $navigationArticles;
    }

    /**
     * 获取推荐文章
     * @param $siteId
     * @param $navigationId
     * @return mixed
     */
    public function getRecommendArticles($siteId, $navigationId)
    {
        $articles = Article::where(['site_id' => $siteId, 'navigation_id' => $navigationId])->inRandomOrder()->limit(5)->get();
        return $articles;
    }

    /**
     * 获取热门文章
     * @param $siteId
     * @param $navigationId
     * @return mixed
     */
    public function getHotArticles($siteId, $navigationId)
    {
        $articles = Article::where(['site_id' => $siteId, 'navigation_id' => $navigationId])->orderBy('view_count', 'desc')->limit(5)->get();
        return $articles;
    }

    /**
     * 获取tags
     * @param int $siteId
     * @return mixed
     */
    public function getTags(int $siteId)
    {
        $articleId = Article::where(['site_id' => $siteId])->pluck('id')->all();
        $data = Tag::whereIn('article_id', $articleId)->inRandomOrder()->limit(20)->get();
        return $data;
    }
}