<?php


namespace App\Services\Admin;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IdataApiService
{
    protected $curl;
    //idata
    protected $apikey;


    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
        $this->apikey = config('idata.apikey');
    }

    /**
     * 获取idata微信文章
     * @param $id
     * @param $wechatNum
     * @return array
     */
    public function getIdataApiArticle($id, $wechatNum)
    {
        $biz = $this->getBiz($wechatNum);
        $data = [];
        if ($biz) {
            $result = $this->getApiArticle($biz, $wechatNum);
            if ($result) {
                $data = $result;
            }
        }
        return $data;
    }

    /**
     * 获取biz
     * @param $wechatNum
     * @return mixed
     */
    protected function getBiz($wechatNum)
    {
        $biz = Cache::rememberForever($wechatNum, function () use ($wechatNum) {
            return $this->getApiBiz($wechatNum);
        });
        return $biz;
    }

    /**
     * 获取API biz
     * @param string $wechatNum
     * @return string
     */
    protected function getApiBiz(string $wechatNum)
    {
        $url = 'http://api01.idataapi.cn:8000/profile/weixin';
        $params = [
            'apikey' => $this->apikey,
            'id' => $wechatNum,
            'pageToken' => 1,
            'get_biz' => 1,
        ];
        $result = $this->curl->get($url, $params);
        if ($result['retcode'] == 000000) {
            return $result['data'][0]['biz'];
        } else {
            Log::error('微信公众号:' . $wechatNum . '获取biz报错');
            Log::error($result);
            return '';
        }
    }

    /**
     * 获取文章
     * @param $biz
     * @param $wechatNum
     * @return array
     */
    protected function getApiArticle($biz, $wechatNum)
    {
        $url = 'http://api01.idataapi.cn:8000/post/weixinpro3';
        $params = [
            'biz' => $biz,
            'apikey' => $this->apikey,
            'pageToken' => 1,
        ];
        $result = $this->curl->get($url, $params);
        if ($result['retcode'] == 000000) {
            return $result['data'];
        } else {
            Log::error('微信公众号:' . $wechatNum . '获取文章报错');
            Log::error($result);
            return [];
        }
    }

}