<?php


namespace App\Services\Admin;


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

    public function getIdataApiArticle($id, $wechatNum)
    {
        $biz = $this->getBiz($wechatNum);
        if ($biz) {

        }
    }

    /**
     * 获取biz
     * @param $wechatNum
     * @return mixed
     */
    protected function getBiz($wechatNum)
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
        }
    }

    protected function getApiArticle($biz)
    {
        $url = 'http://api01.idataapi.cn:8000/post/weixinpro3';
        $params = [
            'biz'=>$biz,
            'apikey'=>$this->apikey,
            'pageToken'=>1,
        ];
        $result = $this->curl->get($url,$params);
    }

}