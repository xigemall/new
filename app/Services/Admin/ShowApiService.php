<?php


namespace App\Services\Admin;


class ShowApiService
{
    protected $curl;
    //易源应用id
    protected $showApiAppId;
    protected $showApiSecret;


    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
        $this->showApiAppId = config('showapi.showapi_appid');
        $this->showApiAppSecret = config('showapi.showapi_secret');
    }

    public function getShowApiArticle(string $account, string $startDate = '', string $endDate = '')
    {
        $url = 'http://route.showapi.com/1849-1';
        // 开始日期与结束日期为空 使用当前的开始与结束日期
        if (empty($startDate) || empty($endDate)) {
            $startDate = date('Y-m-d 00:00:01');
            $endDate = date('Y-m-d 23:59:59');
        }
        $params = $this->getParams($account, $startDate, $endDate);
        $result = $this->curl->get($url,$params);

        if($result['showapi_res_code'] == 0){
            return $result['showapi_res_body'];
        }

    }

    /**
     * 获取参数
     * @param string $account
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    protected function getParams(string $account, string $startDate, string $endDate)
    {
        $params = [
            'showapi_appid' => $this->showApiAppId,
            'account' => $account,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'page'=>'1',
            'needContent'=>'1'
        ];
        $showApiSign = $this->getShowApiSign($params);
        $params['showapi_sign'] = $showApiSign;
        return $params;

    }

    /**
     *获取数字签名
     * @param array $params
     * @return string
     */
    protected function getShowApiSign(array $params)
    {
        $signStr = '';
        ksort($params);
        foreach ($params as $key => $val) {
            $signStr .= $key . $val;
        }
        $signStr .= $this->showApiAppSecret;//排好序的参数加上secret,进行md5
        $sign = strtolower(md5($signStr));
        return $sign;
    }
}