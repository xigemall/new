<?php

namespace App\Http\Controllers;

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
        $domain = $request->url();
        // 网站
        $site = Site::where('domain', $domain)->first();

        if (!$this->site->check($site)) {
            // 无静态文件
            $this->site->set($site);
        }

        \View::addExtension('html', 'php');
        return view()->file(public_path('/static/' . $site->id . '/index.html'));
    }

}
