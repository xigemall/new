<?php


namespace App\Services\Admin;


class Curl
{
    protected $ch;

    /**
     * GET
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return mixed|string
     */
    public function get(string $url, array $params = [], array $headers = [])
    {
        $initHeader = ["Accept" => "application/json"];
        $headers = array_merge($initHeader, $headers);
        $this->init();
        $this->setHeaders($headers);
        if ($params) {
            $query = http_build_query($params);
            $url = $url . '?' . $query;
        }
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $result = curl_exec($this->ch);
        if(curl_errno($this->ch)){
            return curl_error($this->ch);
        }
        curl_close($this->ch);
        return $this->toArray($result);
    }

    /**
     * POST
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return mixed|string
     */
    public function post(string $url, array $data = [], array $headers = [])
    {
        $initHeader = ["Content-type" => "application/json;charset='utf-8'", "Accept" => "application/json"];
        $headers = array_merge($initHeader,$headers);
        $this->init();
        $this->setHeaders($headers);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($this->ch);
        if(curl_errno($this->ch)){
            return curl_error($this->ch);
        }
        curl_close($this->ch);
        return $this->toArray($result);
    }


    protected function init()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    protected function setHeaders(array $headers = [])
    {
        $initHeader = [
            "Content-type" => "application/json",
        ];
        $headers = array_merge($initHeader, $headers);
        $httpHeader = [];
        foreach ($headers as $key => $value) {
            array_push($httpHeader, $key . ':' . $value);
        }
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $httpHeader);
    }

    protected function toArray(string $result)
    {
        $response = json_decode($result, true);
        if (is_null($response) || empty($result)) {
            return $result;
        }
        return $response;
    }
}