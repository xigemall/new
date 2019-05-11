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
        return $this->site->index();
    }

}
